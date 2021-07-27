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

## DatabasePackageInstallationPlugin

`DatabasePackageInstallationPlugin` is a new idempotent package installation plugin (thus it is available in the sync function in the devtools) to update the database schema using the PHP-based database API.
`DatabasePackageInstallationPlugin` is similar to `ScriptPackageInstallationPlugin` by requiring a PHP script that is included during the execution of the script.
The script is expected to return an array of `DatabaseTable` objects representing the schema changes so that in contrast to using `ScriptPackageInstallationPlugin`, no `DatabaseTableChangeProcessor` object has to be created.
The PHP file must be located in the `acp/database/` directory for the devtools sync function to recognize the file.

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

The `StyleCompiler` class is marked `final` now.
The internal SCSS compiler object being stored in the `$compiler` property was a design issue that leaked compiler state across multiple compiled styles, possibly causing misgenerated stylesheets.
As the removal of the `$compiler` property effectively broke compatibility within the `StyleCompiler` and as the `StyleCompiler` never was meant to be extended, it was marked final.

See [WoltLab/WCF#3929](https://github.com/WoltLab/WCF/pull/3929) for details.

## Tags

Use of the `wcf1_tag_to_object.languageID` column is deprecated.
The `languageID` column is redundant, because its value can be derived from the `tagID`.
With WoltLab Suite 5.4, it will no longer be part of any indices, allowing more efficient index usage in the general case.

If you need to filter the contents of `wcf1_tag_to_object` by language, you should perform an `INNER JOIN wcf1_tag tag ON tag.tagID = tag_to_object.tagID` and filter on `wcf1_tag.languageID`.

See [WoltLab/WCF#3904](https://github.com/WoltLab/WCF/pull/3904) for details.

## Avatars

The `ISafeFormatAvatar` interface was added to properly support fallback image types for use in emails.
If your custom `IUserAvatar` implementation supports image types without broad support (i.e. anything other than PNG, JPEG, and GIF), then you should implement the `ISafeFormatAvatar` interface to return a fallback PNG, JPEG, or GIF image.

See [WoltLab/WCF#4001](https://github.com/WoltLab/WCF/pull/4001) for details.

## `lineBreakSeparatedText` Option Type

Currently, several of the (user group) options installed by our packages use the `textarea` option type and split its value by linebreaks to get a list of items, for example for allowed file extensions.
To improve the user interface when setting up the value of such options, we have added the `lineBreakSeparatedText` option type as a drop-in replacement where the individual items are explicitly represented as distinct items in the user interface.

## Ignoring of Users

WoltLab Suite 5.4 distinguishes between blocking direct contact only and hiding all contents when ignoring users.
To allow for detecting the difference, the `UserProfile::getIgnoredUsers()` and `UserProfile::isIgnoredUser()` methods received a new `$type` parameter.
Pass either `UserIgnore::TYPE_BLOCK_DIRECT_CONTACT` or `UserIgnore::TYPE_HIDE_MESSAGES` depending on whether the check refers to a non-directed usage or content.

See [WoltLab/WCF#4064](https://github.com/WoltLab/WCF/pull/4064) and [WoltLab/WCF#3981](https://github.com/WoltLab/WCF/issues/3981) for details.

## `Database::prepare()`

`Database::prepare(string $statement, int $limit = 0, int $offset = 0): PreparedStatement` works the same way as `Database::prepareStatement()` but additionally also replaces all occurences of `app1_` with `app{WCF_N}_` for all installed apps.
This new method makes it superfluous to use `WCF_N` when building queries.
