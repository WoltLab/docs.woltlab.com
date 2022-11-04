# Migrating from WoltLab Suite 5.4 - PHP

## Initial PSR-7 support

WoltLab Suite will *incrementally* add support for object oriented request/response handling based off the [PSR-7](https://www.php-fig.org/psr/psr-7/) and [PSR-15](https://www.php-fig.org/psr/psr-15/) standards in the upcoming versions.

WoltLab Suite 5.5 adds initial support by allowing to define the response using objects implementing the PSR-7 `ResponseInterface`.
If a controller returns such a response object from its `__run()` method, this response will automatically emitted to the client.

Any PSR-7 implementation is supported, but WoltLab Suite 5.5 ships with [laminas-diactoros](https://docs.laminas.dev/laminas-diactoros/) as the recommended “batteries included” implementation of PSR-7.

Support for PSR-7 requests and PSR-15 middlewares is expected to follow in future versions.

See [WoltLab/WCF#4437](https://github.com/WoltLab/WCF/pull/4437) for details.

### Recommended changes for WoltLab Suite 5.5

With the current support in WoltLab Suite 5.5 it is recommended to migrate the `*Action` classes to make use of PSR-7 responses.
Control and data flow is typically fairly simple in `*Action` classes with most requests ending up in either a redirect or a JSON response, commonly followed by a call to `exit;`.

Experimental support for `*Page` and `*Form` is available.
It is recommended to wait for a future version before migrating these types of controllers.

#### Migrating Redirects

Previously:

```php title="lib/action/ExampleRedirectAction.class.php"
<?php

namespace wcf\action;

use wcf\system\request\LinkHandler;
use wcf\util\HeaderUtil;

final class ExampleRedirectAction extends AbstractAction
{
    public function execute()
    {
        parent::execute();

        // Redirect to the landing page.
        HeaderUtil::redirect(
            LinkHandler::getInstance()->getLink()
        );

        exit;
    }
}
```

Now:

```php title="lib/action/ExampleRedirectAction.class.php"
<?php

namespace wcf\action;

use Laminas\Diactoros\Response\RedirectResponse;
use wcf\system\request\LinkHandler;

final class ExampleRedirectAction extends AbstractAction
{
    public function execute()
    {
        parent::execute();

        // Redirect to the landing page.
        return new RedirectResponse(
            LinkHandler::getInstance()->getLink()
        );
    }
}
```

#### Migrating JSON responses

Previously:

```php title="lib/action/ExampleJsonAction.class.php"
<?php

namespace wcf\action;

use wcf\util\JSON;

final class ExampleJsonAction extends AbstractAction
{
    public function execute()
    {
        parent::execute();

        \header('Content-type: application/json; charset=UTF-8');

        echo JSON::encode([
            'foo' => 'bar',
        ]);

        exit;
    }
}
```

Now:

```php title="lib/action/ExampleJsonAction.class.php"
<?php

namespace wcf\action;

use Laminas\Diactoros\Response\JsonResponse;

final class ExampleJsonAction extends AbstractAction
{
    public function execute()
    {
        parent::execute();

        return new JsonResponse([
            'foo' => 'bar',
        ]);
    }
}
```

## Events

Historically, events were tightly coupled with a single class, with the event object being an object of this class, expecting the event listener to consume public properties and method of the event object.
The `$parameters` array was introduced due to limitations of this pattern, avoiding moving all the values that might be of interest to the event listener into the state of the object.
Events were still tightly coupled with the class that fired the event and using the opaque parameters array prevented IDEs from assisting with autocompletion and typing.

WoltLab Suite 5.5 introduces the concept of dedicated, reusable event classes.
Any newly introduced event will receive a dedicated class, implementing the `wcf\system\event\IEvent` interface.
These event classes may be fired from multiple locations, making them reusable to convey that a conceptual action happened, instead of a specific class doing something.
An example for using the new event system could be a user logging in:
Instead of listening on a the login form being submitted and the Facebook login action successfully running, an event `UserLoggedIn` might be fired whenever a user logs in, no matter how the login is performed.

Additionally, these dedicated event classes will benefit from full IDE support.
All the relevant values may be stored as real properties on the event object.

Event classes should not have an `Event` suffix and should be stored in an `event` namespace in a matching location.
Thus, the `UserLoggedIn` example might have a FQCN of `\wcf\system\user\authentication\event\UserLoggedIn`.

Event listeners for events implementing `IEvent` need to follow [PSR-14](https://www.php-fig.org/psr/psr-14/), i.e. they need to be callable.
In practice, this means that the event listener class needs to implement `__invoke()`.
No interface has to be implemented in this case.

Previously:

```php
$parameters = [
    'value' => \random_int(1, 1024),
];

EventHandler::getInstance()->fireAction($this, 'valueAvailable', $parameters);
```

```php title="lib/system/event/listener/ValueDumpListener.class.php"
<?php

namespace wcf\system\event\listener;

use wcf\form\ValueForm;

final class ValueDumpListener implements IParameterizedEventListener
{
    /**
     * @inheritDoc
     * @param ValueForm $eventObj
     */
    public function execute($eventObj, $className, $eventName, array &$parameters)
    {
        var_dump($parameters['value']);
    }
}
```

Now:

```php
EventHandler::getInstance()->fire(new ValueAvailable(\random_int(1, 1024)));
```

```php title="lib/system/foo/event/ValueAvailable.class.php"
<?php

namespace wcf\system\foo\event;

use wcf\system\event\IEvent;

final class ValueAvailable implements IEvent
{
    /**
     * @var int
     */
    private $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
```

```php title="lib/system/event/listener/ValueDumpListener.class.php"
<?php

namespace wcf\system\event\listener;

use wcf\system\foo\event\ValueAvailable;

final class ValueDumpListener
{
    public function __invoke(ValueAvailable $event): void
    {
        \var_dump($event->getValue());
    }
}
```

See [WoltLab/WCF#4000](https://github.com/WoltLab/WCF/pull/4000) and [WoltLab/WCF#4265](https://github.com/WoltLab/WCF/pull/4265) for details.

## Authentication

The [`UserLoggedIn`](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/user/authentication/event/UserLoggedIn.class.php) event was added.
You should fire this event if you implement a custom login process (e.g. when adding additional external authentication providers).

Example:

```php
EventHandler::getInstance()->fire(
    new UserLoggedIn($user)
);
```

See [WoltLab/WCF#4356](https://github.com/WoltLab/WCF/pull/4356) for details.

## Embedded Objects in Comments

[WoltLab/WCF#4275](https://github.com/WoltLab/WCF/pull/4275) added support for embedded objects like mentions for comments and comment responses.
To properly render embedded objects whenever you are using comments in your packages, you have to use `ViewableCommentList`/`ViewableCommentResponseList` in these places or `ViewableCommentRuntimeCache`/`ViewableCommentResponseRuntimeCache`.
While these runtime caches are only available since version 5.5, the viewable list classes have always been available so that changing `CommentList` to `ViewableCommentList`, for example, is a backwards-compatible change.

## Emails

### Mailbox

The `Mailbox` and `UserMailbox` classes no longer store the passed `Language` and `User` objects, but the respective ID instead.
This change reduces the size of the serialized email when stored in the background queue.

If you inherit from the `Mailbox` or `UserMailbox` classes, you might experience issues if you directly access the `$this->language` or `$this->user` properties.
Adjust your class to use composition instead of inheritance if possible.
Use the `getLanguage()` or `getUser()` getters if using composition is not possible.

See [WoltLab/WCF#4389](https://github.com/WoltLab/WCF/pull/4389) for details.

### SMTP

The `SmtpEmailTransport` no longer supports a value of `may` for the `$starttls` property.

Using the `may` value is unsafe as it allows for undetected MITM attacks.
The use of `encrypt` is recommended, unless it is certain that the SMTP server does not support TLS.

See [WoltLab/WCF#4398](https://github.com/WoltLab/WCF/pull/4398) for details.

## Search

### Search Form

After the overhaul of the search form, search providers are no longer bound to `SearchForm` and `SearchResultPage`.
The interface `ISearchObjectType` and the abstract implementation `AbstractSearchableObjectType` have been replaced by `ISearchProvider` and `AbstractSearchProvider`.

Please use [`ArticleSearch`](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/search/ArticleSearch.class.php) as a template for your own implementation

See [WoltLab/WCF#4605](https://github.com/WoltLab/WCF/pull/4605) for details.

### Exceptions

A new [`wcf\system\search\exception\SearchFailed`](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/search/exception/SearchFailed.class.php) exception was added.
This exception should be thrown when executing the search query fails for (mostly) temporary reasons, such as a network partition to a remote service.
It is not meant as a blanket exception to wrap everything.
For example it must not be returned obvious programming errors, such as an access to an undefined variable (`ErrorException`).

Catching the `SearchFailed` exception allows consuming code to gracefully handle search requests that are not essential for proceeding, without silencing other types of error.

See [WoltLab/WCF#4476](https://github.com/WoltLab/WCF/issues/4476) and [WoltLab/WCF#4483](https://github.com/WoltLab/WCF/pull/4483) for details.

## Package Installation Plugins

### Database

WoltLab Suite 5.5 changes the factory classes for common configurations of database columns within the PHP-based DDL API to contain a private constructor, preventing object creation.

This change affects the following classes:

- `DefaultFalseBooleanDatabaseTableColumn`
- `DefaultTrueBooleanDatabaseTableColumn`
- `NotNullInt10DatabaseTableColumn`
- `NotNullVarchar191DatabaseTableColumn`
- `NotNullVarchar255DatabaseTableColumn`
- `ObjectIdDatabaseTableColumn`

- `DatabaseTablePrimaryIndex`

The static `create()` method never returned an object of the factory class, but instead in object of the base type (e.g. `IntDatabaseTableColumn` for `NotNullInt10DatabaseTableColumn`).
Constructing an object of these factory classes is considered a bug, as the class name implies a specific column configuration, that might or might not hold if the object is modified afterwards.

See [WoltLab/WCF#4564](https://github.com/WoltLab/WCF/pull/4564) for details.

WoltLab Suite 5.5 adds the `IDefaultValueDatabaseTableColumn` interface which is used to check whether specifying a default value is legal.
For backwards compatibility this interface is implemented by `AbstractDatabaseTableColumn`.
You should explicitly add this interface to custom table column type classes to avoid breakage if the interface is removed from `AbstractDatabaseTableColumn` in a future version.
Likewise you should explicitly check for the interface before attempting to access the methods related to the default value of a column.

See [WoltLab/WCF#4733](https://github.com/WoltLab/WCF/pull/4733) for details.

### File Deletion

Three new package installation plugins have been added to delete ACP templates with [acpTemplateDelete](../../package/pip/acp-template-delete.md), files with [fileDelete](../../package/pip/file-delete.md), and templates with [templateDelete](../../package/pip/template-delete.md).


### Language

[WoltLab/WCF#4261](https://github.com/WoltLab/WCF/pull/4261) has added support for deleting existing phrases with the `language` package installation plugin.

The current structure of the language XML files

{jinja{ codebox(
    language="xml",
    title="language/en.xml",
    filepath="migration/wsc54/en_old.xml",
) }}

is deprecated and should be replaced with the new structure with an explicit `<import>` element like in the other package installation plugins:

{jinja{ codebox(
    language="xml",
    title="language/en.xml",
    filepath="migration/wsc54/en_new.xml",
) }}

Additionally, to now also support deleting phrases with this package installation plugin, support for a `<delete>` element has been added:

{jinja{ codebox(
    language="xml",
    title="language/en.xml",
    filepath="migration/wsc54/en_new_with_delete.xml",
) }}

Note that when deleting phrases, the category does not have to be specified because phrase identifiers are unique globally.

!!! warning "Mixing the old structure and the new structure is not supported and will result in an error message during the import!"

## Board and Thread Subscriptions

WoltLab Suite Forum 5.5 updates the subscription logic for boards and threads to properly support the ignoring of threads.
See the [dedicated migration guide](forum_subscriptions.md) for details.

## Miscellaneous Changes

### View Counters

With WoltLab Suite 5.5 it is expected that view/download counters do not increase for disabled content.

See [WoltLab/WCF#4374](https://github.com/WoltLab/WCF/pull/4374) for details.

### Form Builder

- [`ValueIntervalFormFieldDependency`](../../php/api/form_builder/dependencies.md#valueintervalformfielddependency)
- [`ColorFormField`](../../php/api/form_builder/form_fields.md#colorformfield)
- [`MultipleBoardSelectionFormField`](../../php/api/form_builder/form_fields.md#multipleboardselectionformfield)
