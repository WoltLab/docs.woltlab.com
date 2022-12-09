# Migrating from WoltLab Suite 5.5 - PHP

## Minimum requirements

The minimum requirements have been increased to the following:

- **PHP:** 8.1.2 (64 bit only); `intl` extension
- **MySQL:** 8.0.29
- **MariaDB:** 10.5.12

It is recommended to make use of the newly introduced features whereever possible.
Please refer to the PHP documentation for details.

## Inheritance

### Parameter / Return / Property Types

Parameter, return, and property types have been added to methods of various classes/interfaces.
This might cause errors during inheritance, because the types are not compatible with the newly added types in the parent class.

Return types may already be added in package versions for older WoltLab Suite branches to be forward compatible, because return types are covariant.

### final

The `final` modifier was added to several classes that were not usefully set up for inheritance in the first place to make it explicit that inheriting from these classes is unsupported.

## Application Boot

### Request-specific logic will no longer happen during boot

Historically the application boot in `WCF`’s constructor performed processing based on fundamentally request-specific values, such as the accessed URL, the request body, or cookies.
This is problematic, because this makes the boot dependent on the HTTP environment which may not be be available, e.g. when using the CLI interface for maintenance jobs.
The latter needs to emulate certain aspects of the HTTP environment for the boot to succeed.
Furthermore one of the goals of the introduction of PSR-7/PSR-15-based request processing that [was started in WoltLab Suite 5.5](../wsc54/php.md#initial-psr-7-support) is the removal of implicit global state in favor of explicitly provided values by means of a `ServerRequestInterface` and thus to achieve a cleaner architecture.

To achieve a clean separation this type of request-specific logic will incrementally be moved out of the application boot in `WCF`’s constructor and into the request processing stack that is launched by `RequestHandler`, e.g. by running appropriate PSR-15 middleware.

An example of this type of request-specific logic that was previously happening during application boot is the check that verifies whether a user is banned and denies access otherwise.
This check is based on a request-specific value, namely the user’s session which in turn is based on a provided (HTTP) cookie.
It is now [moved into the `CheckUserBan` middleware](https://github.com/WoltLab/WCF/commit/51154ba3f8f1d09b54560d5d1933f9053ef409cb).

This move implies that custom scripts that include WoltLab Suite Core’s `global.php`, without also invoking `RequestHandler` will no longer be able to rely on this type of access control having happened and will need to implement it themselves, e.g. by manually running the appropriate middlewares.

Notably the following checks have been moved into a middleware:

- Denying access to banned users ([WoltLab/WCF#4935](https://github.com/WoltLab/WCF/pull/4935))
- ACP authentication ([WoltLab/WCF#4939](https://github.com/WoltLab/WCF/pull/4939))

The initialization of the session itself and dependent subsystems (e.g. the user object and thus the current language) is still running during application boot for now.
However it is planned to also move the session initialization into the middleware in a future version and then providing access to the session by adding an attribute on the `ServerRequestInterface`, instead of querying the session via `WCF::getSession()`.
As such you should begin to stop relying on the session and user outside of `RequestHandler`’s middleware stack and should also avoid calling `WCF::getUser()` and `WCF::getSession()` outside of a controller, instead adding a `User` parameter to your methods to allow an appropriate user to be passed from the outside.

An example of a method that implicitly relies on these global values is the [VisitTracker's `trackObjectVisit()` method](https://github.com/WoltLab/WCF/blob/7cfd5578ede22e798b770262c0cdf1e9dfe25d36/wcfsetup/install/files/lib/system/visitTracker/VisitTracker.class.php#L199).
It only takes the object type, object ID and timestamp as the parameter and will determine the `userID` by itself.
The `trackObjectVisitByUserIDs()` method on the other hand does not rely on global values.
Instead the relevant user IDs need to be passed explicitly from the controller as parameters, thus making the information the method works with explicit.
This also makes the method reusable for use cases where an object should be marked as visited for a user other than the active user, without needing to temporarily switch the active user in the session.

The same is true for “permission checking” methods on `DatabaseObject`s.
Instead of having a `$myObject->canView()` method that uses `WCF::getSession()` or `WCF::getUser()` internally, the user should explicitly be passed to the method as a parameter, allowing for permission checks to happen in a different context, for example send sending notification emails.

Likewise event listeners should not access these request-specific values at all, because they are unable to know whether the event was fired based on these request-specific values or whether some programmatic action fired the event for another arbitrary user.
Instead they must retrieve the appropriate information from the event data only.

### Bootstrap Scripts

WoltLab Suite 6.0 adds package-specific bootstrap scripts allowing a package to execute logic during the application boot to prepare the environment before the request is passed through the middleware pipeline into the controller in `RequestHandler`.

Bootstrap scripts are stored in the `lib/bootstrap/` directory of WoltLab Suite Core with the package identifier as the file name.
They do not need to be registered explicitly, as one future goal of the bootstrap scripts is reducing the amount of system state that needs to be stored within the database.
Instead WoltLab Suite Core will automatically create a bootstrap loader that includes all installed bootstrap scripts as part of the package installation and uninstallation process.

Bootstrap scripts will be loaded and the bootstrap functions will executed based on a topological sorting of all installed packages.
A package can rely on all bootstrap scripts of its dependencies being loaded before its own bootstrap script is loaded.
It can also rely on all bootstrap functions of its dependencies having executed before its own bootstrap functions is executed.
However it cannot rely on any specific loading and execution order of non-dependencies.

As hinted at in the previous paragraph, executing the bootstrap scripts happens in two phases:

1. All bootstrap scripts will be `include()`d in topological order. The script is expected to return a `Closure` that is executed in phase 2.
2. Once all bootstrap scripts have been included, the returned `Closure`s will be executed in the same order the bootstrap scripts were loaded.

```php title="files/lib/bootstrap/com.example.foo.php"
<?php

// Phase (1).

return static function (): void {
    // Phase (2).
};
```

For the vast majority of packages it is expected that the phase (1) bootstrapping is not used, except to return the `Closure`.
Instead the logic should reside in the `Closure`s body that is executed in phase (2).

#### Registering `IEvent` listeners

An example use case for bootstrap scripts with WoltLab Suite 6.0 is registering event listeners for `IEvent`-based events that [were added with WoltLab Suite 5.5](../wsc54/php.md#events), instead of using the [eventListener PIP](../pip/../../package/pip/event-listener.md).
Registering event listeners within the bootstrap script allows you to leverage your IDE’s autocompletion for class names and and prevents forgetting the explicit uninstallation of old event listeners during a package upgrade.

```php title="files/lib/bootstrap/com.example.bar.php"
<?php

use wcf\system\event\EventHandler;
use wcf\system\event\listener\ValueDumpListener;
use wcf\system\foo\event\ValueAvailable;

return static function (): void {
    EventHandler::getInstance()->register(
        ValueAvailable::class,
        ValueDumpListener::class
    );

    EventHandler::getInstance()->register(
        ValueAvailable::class,
        static function (ValueAvailable $event): void {
            // For simple use cases a `Closure` instead of a class name may be used.
            \var_dump($event->getValue());
        }
    );
};
```

## Request Processing

As previously mentioned in the [Application Boot](#application-boot) section, WoltLab Suite 6.0 improves support for PSR-7/PSR-15-based request processing [that was initially announced with WoltLab Suite 5.5](../wsc54/php.md#initial-psr-7-support).

WoltLab Suite 5.5 added support for returning a PSR-7 `ResponseInterface` from a controller and recommended to migrate existing controllers based on `AbstractAction` to make use of `RedirectResponse` and `JsonResponse` instead of using `HeaderUtil::redirect()` or manually emitting JSON with appropriate headers.
Processing the request values still used PHP’s superglobals (specifically `$_GET` and `$_POST`).

WoltLab Suite 6.0 adds support for controllers based on PSR-15’s `RequestHandlerInterface`, supporting request processing based on a provided PSR-7 `ServerRequestInterface` object.

### Recommended changes for WoltLab Suite 6.0

It is recommended to use `RequestHandlerInterface`-based controllers whenever an `AbstractAction` would previously be used.
Furthermore any AJAX-based logic that would previously rely on `AJAXProxyAction` combined with a method in an `AbstractDatabaseObjectAction` should also be implemented using a dedicated `RequestHandlerInterface`-based controller.
Both `AbstractAction` and `AJAXProxyAction`-based AJAX requests should be considered soft-deprecated going forward.

When creating a `RequestHandlerInterface`-based controller, care should be taken to ensure no mutable state is stored in object properties of the controller itself.
The state of the controller object must be identical before, during and after a request was processed.
Any required values must be passed explicitly by means of method parameters and return values.
Likewise any functionality called by the controller’s `handle()` method should not rely on implicit global values, such as `WCF::getUser()`, as was explained in the previous [section about request-specific logic](#request-specific-logic-will-no-longer-happen-during-boot).

The recommended pattern for a `RequestHandlerInterface`-based controller looks as follows:

```php title="files/lib/action/MyFancyAction.class.php"
<?php

namespace wcf\action;

use Laminas\Diactoros\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class MyFancyAction implements RequestHandlerInterface
{
    public function __construct()
    {
        /* 0. Explicitly register services used by the controller, to
         *    make dependencies explicit and to avoid accidentally using
         *    global state outside of a controller.
         */
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /* 1. Perform permission checks and input validation. */

        /* 2. Perform the action. The action must not rely on global state,
         *    but instead only on explicitly passed values. It should assume
         *    that permissions have already been validated by the controller,
         *    allowing it to be reusable programmatically.
         */

        /* 3. Perform post processing. */

        /* 4. Prepare the response, e.g. by querying an updated object from
         *    the database.
         */

        /* 5. Send the response. */
        return new Response();
    }
}
```

It is recommended to leverage [Valinor](./libraries.md#input-validation) for structural validation of input values if using the [FormBuilder](../../php/api/form_builder/overview.md) is not a good fit, specifically for any values that are provided implicitly and are expected to be correct.
WoltLab Suite includes a middleware that will automatically convert unhandled `MappingError`s into a response with status HTTP 400 Bad Request.

XSRF validation will implicitly be performed for any request that uses a HTTP verb other than `GET`.
Likewise any requests with a JSON body will automatically be decoded by a middleware and stored as the `ServerRequestInterface`’s parsed body.

#### Querying RequestHandlerInterface-based controllers via JavaScript

The new `WoltLabSuite/Core/Ajax/Backend` module may be used to easily query a `RequestHandlerInterface`-based controller.
The JavaScript code must not make any assumptions about the URI structure to reach the controller.
Instead the endpoint must be generated using `LinkHandler` and explicitly provided, e.g. by storing it in a `data-endpoint` attribute:

```smarty
<button
    class="button fancyButton"
    data-endpoint="{link controller='MyFancy'}{/link}"
>Click me!</button>
```

```ts
const button = document.querySelector('.fancyButton');
button.addEventListener('click', async (event) => {
    const request = prepareRequest(button.dataset.endpoint)
        .get(); // or: .post(…)

    const response = await request.fetchAsResponse(); // or: .fetchAsJson()
});
```

#### FormBuilder

The `Psr15DialogForm` class combined with the `usingFormBuilder()` method of [`dialogFactory()`](./dialogs.md) provides a “batteries-included” solution to create a AJAX- and [FormBuilder](../../php/api/form_builder/overview.md)-based `RequestHandlerInterface`-based controller.

Within the JavaScript code the endpoint is queried using:

```ts
const { ok, result } = await dialogFactory()
    .usingFormBuilder()
    .fromEndpoint(url);
```

The returned `Promise` will resolve when the dialog is closed, either by successfully submitting the form or by manually closing it and thus aborting the process.
If the form was submitted successfully `ok` will be `true` and `result` will contain the controller’s response.
If the dialog was closed without successfully submitting the form, `ok` will be `false` and `result` will be set to `undefined`.

Within the PHP code, the form may be created as usual, but use `Psr15DialogForm` as the form document.
The controller must return `$dialogForm->toJsonResponse()` for `GET` requests and validate the `ServerRequestInterface` using `$dialogForm->validateRequest($request)` for `POST` requests.
The latter will return a `ResponseInterface` to be returned if the validation fails, otherwise `null` is returned.
If validation succeeded, the controller must perform the resulting action and return a `JsonResponse` with the `result` key:

```php
if ($request->getMethod() === 'GET') {
    return $dialogForm->toJsonResponse();
} elseif ($request->getMethod() === 'POST') {
    $response = $dialogForm->validatePsr7Request($request);
    if ($response !== null) {
        return $response;
    }

    $data = $form->getData();

    // Use $data.

    return new JsonResponse([
        'result' => [
            'some' => 'value',
        ],
    ]);
} else {
    return new TextResponse('The used HTTP method is not allowed.', 405, [
        'allow' => 'POST, GET',
    ]);
}
```

#### Example

A complete example, showcasing all the patterns can be found in [WoltLab/WCF#5106](https://github.com/WoltLab/WCF/pull/5106).
This example showcases how to:

- Store used services within the controller’s constructor.
- Perform validation of inputs using Valinor.
- Perform permission checks.
- Use the FormBuilder.
- Delegate the actual processing to a reusable command that does not rely on global state.
- Store the request endpoint as a `data-*` attribute.

## Package System

### Required “minversion” for required packages

The `minversion` attribute of the `<requiredpackage>` tag is now required.

### Rejection of “pl” versions

Woltlab Suite 6.0 no longer accepts package versions with the “pl” suffix as valid.

### Removal of API compatibility

WoltLab Suite 6.0 removes support for the deprecated API compatibility functionality.
Any packages with a `<compatibility>` tag in their package.xml are assumed to not have been updated for WoltLab Suite 6.0 and will be rejected during installation.
Furthermore any packages without an explicit requirement for `com.woltlab.wcf` in at least version `5.4.22` are also assumed to not have been updated for WoltLab Suite 6.0 and will also be rejected.
The latter check is intended to reject old and most likely incompatible packages where the author forgot to add either an `<excludedpackage>` or a `<compatibility>` tag before releasing it.

### Package Installation Plugins

#### Database

The `$name` parameter of `DatabaseTableIndex::create()` is no longer optional.
Relying on the auto-generated index name is strongly discouraged, because of unfixable inconsistent behavior between the SQL PIP and the PHP DDL API.
See [WoltLab/WCF#4505](https://github.com/WoltLab/WCF/issues/4505) for further background information.

The autogenerated name can still be requested by passing an empty string as the `$name`.
This should only be done for backwards compatibility purposes and to migrate an index with an autogenerated name to an index with an explicit name.
An example script can be found in [WoltLab/com.woltlab.wcf.conversation@a33677ca051f](https://github.com/WoltLab/com.woltlab.wcf.conversation/commit/a33677ca051f76e1ddda1de7f8dc62a5484de16e).

## Internationalization

WoltLab Suite 6.0 [increases the System Requirements](#minimum-requirements) to require [PHP’s intl extension](https://www.php.net/manual/en/book.intl.php) to be installed and enabled, allowing you to rely on the functionality provided by it to better match the rules and conventions of the different languages and regions of the world.

One example would be the formatting of numbers.
WoltLab Suite included a feature to group digits within large numbers since early versions using the `StringUtil::addThousandsSeparator()` method.
While this method was able to account for *some* language-specific differences, e.g. by selecting an appropriate separator character based on a phrase, it failed to account for all the differences in number formatting across countries and cultures.

As an example, English as written in the United States of America uses commas to create groups of three digits within large numbers: 123,456,789.
English as written in India on the other hand also uses commas, but digits are not grouped into groups of three.
Instead the right-most three digits form a group and then another comma is added every *two* digits: 12,34,56,789.

Another example would be German as used within Germany and Switzerland.
While both countries use groups of three, the separator character differs.
Germany uses a dot (123.456.789), whereas Switzerland uses an apostrophe (123’456’789).
The correct choice of separator could already be configured using the afore-mentioned phrase, but this is both inconvenient and fails to account for other differences between the two countries.
It also made it hard to keep the behavior up to date when rules change.

PHP’s intl extension on the other hand builds on the official Unicode rules, by relying on the ICU library published by the Unicode consortium.
As such it is aware of the rules of all relevant languages and regions of the world and it is already kept up to date by the operating system’s package manager.

For the four example regions (en_US, en_IN, de_DE, de_CH) intl’s `NumberFormatter` class will format the number 123456789 as follows, correctly implementing the rules:

```
php > var_dump((new NumberFormatter('en_US', \NumberFormatter::DEFAULT_STYLE))->format(123_456_789));
string(11) "123,456,789"
php > var_dump((new NumberFormatter('en_IN', \NumberFormatter::DEFAULT_STYLE))->format(123_456_789));
string(12) "12,34,56,789"
php > var_dump((new NumberFormatter('de_DE', \NumberFormatter::DEFAULT_STYLE))->format(123_456_789));
string(11) "123.456.789"
php > var_dump((new NumberFormatter('de_CH', \NumberFormatter::DEFAULT_STYLE))->format(123_456_789));
string(15) "123’456’789"
```

WoltLab Suite’s `StringUtil::formatNumeric()` method is updated to leverage the `NumberFormatter` internally.
However your package might have special requirements regarding formatting, for example when formatting currencies where the position of the currency symbol differs across languages.
In those cases your package should manually create an appropriately configured class from Intl’s feature set.
The correct locale can be queried by the new `Language::getLocale()` method.

Another use case that showcases the `Language::getLocale()` method might be localizing a country name using [`locale_get_display_region()`](https://www.php.net/manual/en/locale.getdisplayregion.php):

```
php > var_dump(\wcf\system\WCF::getLanguage()->getLocale());
string(5) "en_US"
php > var_dump(locale_get_display_region('_DE', \wcf\system\WCF::getLanguage()->getLocale()));
string(7) "Germany"
php > var_dump(locale_get_display_region('_US', \wcf\system\WCF::getLanguage()->getLocale()));
string(13) "United States"
php > var_dump(locale_get_display_region('_IN', \wcf\system\WCF::getLanguage()->getLocale()));
string(5) "India"
php > var_dump(locale_get_display_region('_BR', \wcf\system\WCF::getLanguage()->getLocale()));
string(6) "Brazil"
```

See [WoltLab/WCF#5048](https://github.com/WoltLab/WCF/pull/5048) for details.

## Indicating parameters that hold sensitive information

PHP 8.2 adds native support for redacting parameters holding sensitive information in stack traces.
Parameters with the `#[\SensitiveParameter]` attribute will show a placeholder value within the stack trace and the error log.

WoltLab Suite’s exception handler contains logic to manually apply the sanitization for PHP versions before 8.2.

It is strongly recommended to add this attribute to all parameters holding sensitive information.
Examples for sensitive parameters include passwords/passphrases, access tokens, plaintext values to be encrypted, or private keys.

As attributes are fully backwards and forwards compatible it is possible to apply the attribute to packages targeting older WoltLab Suite or PHP versions without causing errors.

Example:

```php
function checkPassword(
    #[\SensitiveParameter]
    $password,
): bool {
    // …
}
```

See the [PHP RFC: Redacting parameters in back traces](https://wiki.php.net/rfc/redact_parameters_in_back_traces) for more details.

## Conditions

### AbstractIntegerCondition

Deriving from `AbstractIntegerCondition` now requires to explicitly implement `protected function getIdentifier(): string`, instead of setting the `$identifier` property.
This is to ensure that all conditions specify a unique identifier, instead of accidentally relying on a default value.
The `$identifier` property will no longer be used and may be removed.

See [WoltLab/WCF#5077](https://github.com/WoltLab/WCF/pull/5077) for details.

## Rebuild Workers

Rebuild workers should no longer be registered using the `com.woltlab.wcf.rebuildData` object type definition.
You can attach an event listener to the `wcf\system\worker\event\RebuildWorkerCollecting` event inside a [bootstrap script](#bootstrap-scripts) to lazily register workers.
The class name of the worker is registered using the event’s `register()` method:

```php title="files/lib/bootstrap/com.example.bar.php"
<?php

use wcf\system\event\EventHandler;
use wcf\system\worker\event\RebuildWorkerCollecting;

return static function (): void {
    $eventHandler = EventHandler::getInstance();

    $eventHandler->register(RebuildWorkerCollecting::class, static function (RebuildWorkerCollecting $event) {
        $event->register(\bar\system\worker\BazWorker::class, 0);
    });
};
```
