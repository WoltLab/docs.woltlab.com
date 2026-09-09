# Sitemaps

WoltLab Suite is capable of automatically creating a sitemap.
This sitemap contains all static pages registered via the page package installation plugin and which may be indexed by search engines (checking the `allowSpidersToIndex` parameter and page permissions) and do not expect an object ID.
Other pages have to be added to the sitemap as a separate object.

The only prerequisite for sitemap objects is that the objects are instances of `wcf\data\DatabaseObject` and that there is a `wcf\data\DatabaseObjectList` implementation.

## Implementing the Sitemap Object

First, we implement the PHP class, which provides us all database objects and optionally checks the permissions for a single object.
The class must implement the interface `wcf\system\sitemap\object\ISitemapObjectObjectType`.
However, in order to have some methods already implemented and ensure backwards compatibility, you should use the abstract class `wcf\system\sitemap\object\AbstractSitemapObjectObjectType`.
The abstract class takes care of generating the `DatabaseObjectList` class name and list directly and implements optional methods with the default values.
The only method that you have to implement yourself is the `getObjectClass()` method which returns the fully qualified name of the `DatabaseObject` class.
The `DatabaseObject` class must implement the interface `wcf\data\ILinkableObject`.

Other optional methods are:

* The `getLastModifiedColumn()` method returns the name of the column in the database where the last modification date is stored.
  If there is none, this method must return `null`.
* The `canView()` method checks whether the passed `DatabaseObject` is visible to the current user with the current user always being a guest.
  The passed object is always an instance of the class returned by `getObjectClass()`.
* The `getObjectListClass()` method returns a non-standard `DatabaseObjectList` class name.
* The `getObjectList()` method returns the `DatabaseObjectList` instance.
  You can, for example, specify additional query conditions in the method.

As an example, the implementation for users looks like this:

{jinja{ codebox(
  title="files/lib/system/sitemap/object/UserSitemapObject.class.php",
  language="php",
  filepath="php/api/sitemaps/UserSitemapObject.class.php"
) }}

## Registering the Sitemap Object

Since version 6.3, sitemap objects are registered through the PSR-14 event `wcf\event\sitemap\SitemapObjectCollecting` inside a [bootstrap script](../../package/bootstrap-scripts.md).
Each object is represented by an instance of `wcf\system\sitemap\object\RegisteredSitemapObject`:

```php title="files/lib/bootstrap/com.example.plugin.php"
<?php

use wcf\event\sitemap\SitemapObjectCollecting;
use wcf\system\event\EventHandler;
use wcf\system\sitemap\object\RegisteredSitemapObject;

return static function (): void {
    EventHandler::getInstance()->register(
        SitemapObjectCollecting::class,
        static function (SitemapObjectCollecting $event) {
            $event->register(new RegisteredSitemapObject(
                'com.example.plugin.sitemap.object.user',
                new \wcf\system\sitemap\object\UserSitemapObject(),
                changeFreq: 'monthly',
                rebuildTime: 259200,
            ));
        }
    );
};
```

The constructor of `RegisteredSitemapObject` accepts the following parameters:

| Parameter | Type | Description |
|-----------|------|-------------|
| `objectName` | `string` | Unique name of the sitemap object, it is also used as the file name of the generated sitemap and as the suffix of the language item. |
| `processor` | `ISitemapObjectObjectType` | Instance of the class implemented in the previous step. |
| `priority` | `float` | [Priority](https://www.sitemaps.org/protocol.html#prioritydef) of the pages, defaults to `0.5`. It should not be changed unless there is an important reason to do so. |
| `changeFreq` | `string` | [Change frequency](https://www.sitemaps.org/protocol.html#changefreqdef) of the pages, defaults to `monthly`. |
| `rebuildTime` | `int` | Number of seconds after which the sitemap should be regenerated, defaults to `604800`. |
| `packageID` | `?int` | Package that owns the generated sitemap files, defaults to the core. Apps and plugins that ship their own package should pass their own package id, so that the files are removed when the package is uninstalled. |
| `isDisabled` | `bool` | Whether the sitemap object is disabled by default, defaults to `false`. |
| `name` | `string` | Localized name of the sitemap object shown in the ACP. If it is empty, the phrase `wcf.acp.sitemap.objectType.{objectName}` is used instead. |

`priority`, `changeFreq` and `rebuildTime` are the default values, `changeFreq` and `rebuildTime` can be changed by the administrator in the ACP.

Finally, you have to create the language variable for the sitemap object.
The language variable follows the pattern `wcf.acp.sitemap.objectType.{objectName}` and is in the category `wcf.acp.sitemap`.

### Registration Through the Object Type (Deprecated)

!!! warning "The object type definition `com.woltlab.wcf.sitemap.object` is deprecated since version 6.3 and should no longer be used for new sitemap objects."

Before version 6.3, sitemap objects were registered as an object type:

```xml
<type>
        <name>com.example.plugin.sitemap.object.user</name>
        <definitionname>com.woltlab.wcf.sitemap.object</definitionname>
        <classname>wcf\system\sitemap\object\UserSitemapObject</classname>
        <priority>0.5</priority>
        <changeFreq>monthly</changeFreq>
        <rebuildTime>259200</rebuildTime>
</type>
```

Object types that are still registered this way continue to work and are merged with the objects registered through the event, but they are shadowed by an object registered through the event using the same name.
