# Migrating from WSC 5.3 - PHP

## Minimum requirements

The minimum requirements have been increased to the following:

- **PHP:** 7.2.24
- **MySQL:** 5.7.31 or 8.0.19
- **MariaDB:** 10.1.44

Most notably PHP 7.2 contains usable support for scalar types by the addition of nullable types in PHP 7.1 and parameter type widening in PHP 7.2.

It is recommended to make use of scalar types and other newly introduced features whereever possible.
Please refer to the PHP documentation for details.

## Flood Control

To prevent users from creating massive amounts of contents in short periods of time, i.e., spam, existing systems already use flood control mechanisms to limit the amount of contents created within a certain period of time.
With WoltLab Suite 5.4, we have added a general API that manages such rate limiting.
Leveraging this API is easily done.

1. Register an object type for the definition `com.woltlab.wcf.floodControl`: `com.example.foo.myContent`.
2. Whenever the active user creates content of this type, call
   ```php
   FloodControl::getInstance()->registerContent('com.example.foo.myContent');
   ```
   You should only call this method if the user creates the content themselves.
   If the content is automatically created by the system, for example when copying / duplicating existing content, no activity should be registered.
3. To check the last time when the active user created content of the relevant type, use
   ```php
   FloodControl::getInstance()->getLastTime('com.example.foo.myContent');
   ```
   If you want to limit the number of content items created within a certain period of time, for example within one day, use
   ```php
   $data = FloodControl::getInstance()->countContent('com.example.foo.myContent', new \DateInterval('P1D'));
   // number of content items created within the last day
   $count = $data['count'];
   // timestamp when the earliest content item was created within the last day
   $earliestTime = $data['earliestTime'];
   ```
   The method also returns `earliestTime` so that you can tell the user in the error message when they are able again to create new content of the relevant type.
   !!! info "Flood control entries are only stored for 31 days and older entries are cleaned up daily."

The previously mentioned methods of `FloodControl` use the active user and the current timestamp as reference point.
`FloodControl` also provides methods to register content or check flood control for other registered users or for guests via their IP address.
For further details on these methods, please refer to the [documentation in the FloodControl class](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/flood/FloodControl.class.php).

!!! warning "Do not interact directly with the flood control database table but only via the `FloodControl` class!"

## PHP Database API

The PHP API to add and change database tables during package installations and updates in the `wcf\system\database\table` namespace now also supports renaming existing table columns with the new `IDatabaseTableColumn::renameTo()` method:

```php
PartialDatabaseTable::create('wcf1_test')
        ->columns([
                NotNullInt10DatabaseTableColumn::create('oldName')
                        ->renameTo('newName')
        ]);
```

!!! info "Like with every change to existing database tables, packages can only rename columns that they installed." 

## Captcha

The reCAPTCHA v1 implementation was completely removed.
This includes the `\wcf\system\recaptcha\RecaptchaHandler` class (not to be confused with the one in the `captcha` namespace).

The reCAPTCHA v1 endpoints have already been turned off by Google and always return a HTTP 404.
Thus the implementation was completely non-functional even before this change.

See [WoltLab/WCF#3781](https://github.com/WoltLab/WCF/pull/3781) for details.

## Search

The generic implementation in the `AbstractSearchEngine::parseSearchQuery()` method was dangerous, because it did not have knowledge about the search engine’s specifics.
The implementation was completely removed: `AbstractSearchEngine::parseSearchQuery()` now always throws a `\BadMethodCallException`.

If you implemented a custom search engine and relied on this method, you can inline the previous implementation to preserve existing behavior.
You should take the time to verify the rewritten queries against the manual of the search engine to make sure it cannot generate malformed queries or security issues.

See [WoltLab/WCF#3815](https://github.com/WoltLab/WCF/issues/3815) for details.

## Styles

The `StyleCompiler` is marked `final` now.
It was noticed that the `$compiler` property was a design issue that leaked compiler state across multiple compiled styles, possibly causing misgenerated stylesheets.
As the removal of the `$compiler` property effectively broke compatibility within the `StyleCompiler` and as the `StyleCompiler` never was meant to be extended it was marked final.

See [WoltLab/WCF#3929](https://github.com/WoltLab/WCF/pull/3929) for details.
