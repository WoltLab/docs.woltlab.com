---
title: Migrating from WSC 3.1 - PHP
sidebar: sidebar
permalink: migration_wsc-31_php.html
folder: migration/wsc-31
---

## Form Builder

WoltLab Suite Core 5.2 introduces a new, simpler and quicker way of creating forms:
[form builder](php_api_form_builder.html).
You can find examples of how to migrate existing forms to form builder [here](migration_wsc-31_form-builder.html).

In the near future, to ensure backwards compatibility within WoltLab packages, we will only use form builder for new forms or for major rewrites of existing forms that would break backwards compatibility anyway.

## Like System 
WoltLab Suite Core 5.2 replaced the like system with the reaction system. You can find the migration guide [here](migration_wsc-31_like.html).

## User Content Providers

User content providers help the WoltLab Suite to find user generated content. They provide a class with which you can find content from a particular user and delete objects.


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
 * @since	5.2
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

#### `nicevalue` 

<span class="label label-info">Optional</span>

The nice value is used to determine the order in which the remove content worker are execute the provider. Content provider with lower nice values are executed first.

#### `hidden`

<span class="label label-info">Optional</span>

Specifies whether or not this content provider can be actively selected in the Content Remove Worker. If it cannot be selected, it will not be executed automatically! 

#### `requiredobjecttype`

<span class="label label-info">Optional</span>

The specified list of comma-separated object types are automatically removed during content removal when this object type is being removed. Heads up: The order of removal is undefined by default, specify a `nicevalue` if the order is important.



## PHP Database API

WoltLab Suite 5.2 introduces a new way to update the database scheme:
[database PHP API](package_database-php-api.html).