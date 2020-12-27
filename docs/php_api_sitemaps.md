---
title: Sitemaps
sidebar: sidebar
permalink: php_api_sitemaps.html
folder: php/api
---

{% include callout.html content="This feature is available with WoltLab Suite 3.1 or newer only." type="warning" %}

Since version 3.1, WoltLab Suite Core is capable of automatically creating a sitemap.
This sitemap contains all static pages registered via the page package installation plugin and which may be indexed by search engines (checking the `allowSpidersToIndex` parameter and page permissions) and do not expect an object ID.
Other pages have to be added to the sitemap as a separate object.

The only prerequisite for sitemap objects is that the objects are instances of `wcf\data\DatabaseObject` and that there is a `wcf\data\DatabaseObjectList` implementation.

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
* The `getObjectListClass()` method returns a non-standard `DatabaseObjectList` class name.
* The `getObjectList()` method returns the `DatabaseObjectList` instance.
  You can, for example, specify additional query conditions in the method.

As an example, the implementation for users looks like this:

```php
<?php
namespace wcf\system\sitemap\object;
use wcf\data\user\User;
use wcf\data\DatabaseObject;
use wcf\system\WCF;

/**
 * User sitemap implementation.
 *
 * @author	Joshua Ruesweg
 * @copyright	2001-2017 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	WoltLabSuite\Core\Sitemap\Object
 * @since	3.1
 */
class UserSitemapObject extends AbstractSitemapObjectObjectType {
	/**
	 * @inheritDoc
	 */
	public function getObjectClass() {
		return User::class;
	}

	/**
	 * @inheritDoc
	 */
	public function getLastModifiedColumn() {
		return 'lastActivityTime';
	}

	/**
	 * @inheritDoc
	 */
	public function canView(DatabaseObject $object) {
		return WCF::getSession()->getPermission('user.profile.canViewUserProfile');
	}
}
```

Next, the sitemap object must be registered as an object type:

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

In addition to the fully qualified class name, the object type definition `com.woltlab.wcf.sitemap.object` and the object type name, the parameters `priority`, `changeFreq` and `rebuildTime` must also be specified.
`priority` ([https://www.sitemaps.org/protocol.html#prioritydef](https://www.sitemaps.org/protocol.html#prioritydef)) and `changeFreq` ([https://www.sitemaps.org/protocol.html#changefreqdef](https://www.sitemaps.org/protocol.html#changefreqdef)) are specifications in the sitemaps protocol and can be changed by the user in the ACP.
The `priority` should be `0.5` by default, unless there is an important reason to change it.
The parameter `rebuildTime` specifies the number of seconds after which the sitemap should be regenerated.

Finally, you have to create the language variable for the sitemap object.
The language variable follows the pattern `wcf.acp.sitemap.objectType.{objectTypeName}` and is in the category `wcf.acp.sitemap`.