# Migrating from WoltLab Suite 5.5 - PHP

## Minimum requirements

The minimum requirements have been increased to the following:

- **PHP:** 8.1.2 (64 bit only); `intl` extension
- **MySQL:** 8.0.29
- **MariaDB:** 10.5.12

It is recommended to make use of the newly introduced features whereever possible.
Please refer to the PHP documentation for details.

## Inheritance

Parameter, return, and property types have been added to methods of various classes/interfaces.
This might cause errors during inheritance, because the types are not compatible with the newly added types in the parent class.

Return types may already be added in package versions for older WoltLab Suite branches to be forward compatible, because return types are covariant.

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

## Package System

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
