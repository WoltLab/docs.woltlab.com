---
title: Migrating from WSC 3.1 - User Content Provider
sidebar: sidebar
permalink: migration_wsc-31_content-provider.html
folder: migration/wsc-31
---

## User Content Provider 

User Content Providers help the WoltLab Suite to find user generated content. They provide a class with which you can find content from a particular user and delete objects.


### PHP Class

First, we create the PHP class that provides our interface to provide the data. The class must implement interface `wcf\system\user\content\provider\IUserContentProvider` in any case. Mostly we process data which is based on [`wcf\data\DatabaseObject`](php_database-objects.html). In this case, the WoltLab Suite provides an abstract class `wcf\system\user\content\provider\AbstractDatabaseUserContentProvider` that can be used to automatically generates the standardized classes to generate the list and deletes objects via the DatabaseObjectAction. For example, if we would create a content provider for comments, the class would look like this: 

```php
<?php
namespace wcf\system\user\content\provider;
use wcf\data\comment\Comment;

/**
 * User content provider for comments.
 *
 * @author	Joshua Ruesweg
 * @copyright	2001-2018 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	WoltLabSuite\Core\System\User\Content\Provider
 * @since	3.2
 */
class CommentUserContentProvider extends AbstractDatabaseUserContentProvider {
	/**
	 * @inheritdoc
	 */
	public static function getDatabaseObjectClass() {
		return Comment::class;
	}
}
```

### Object Type

Now the appropriate object type must be created for the class. This object type must be from the definition `com.woltlab.wcf.content.userContentProvider` and include the previous created class as FQN in the parameter `classname`. Also the following parameters can be used in the object type: 

#### nicevalue 

{% include callout.html content="Optional" type="info" %}

The nice value is used to determine the order in which the remove content worker are execute the provider. Content provider with lower nice values are executed first.

#### hidden

{% include callout.html content="Optional" type="info" %}

Specifies whether or not this content provider can be actively selected in the Content Remove Worker. If it cannot be selected, it will not be executed automatically! 

#### requiredobjecttype

{% include callout.html content="Optional" type="info" %}

The specified list of comma-separated object types are automatically removed during content removal when this object type is being removed. Heads up: The order of removal is undefined by default, specify a `nicevalue` if the order is important.