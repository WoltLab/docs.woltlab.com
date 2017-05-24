---
title: Documentation
sidebar: sidebar
permalink: php_code-style_documentation.html
folder: php
parent: php_code-style
---

{% include callout.html content="The following documentation conventions are used by us for our own packages. While you do not have to follow every rule, you are encouraged to do so." type="info" %}


## Database Objects

### Database Table Columns as Properties

As the database table columns are not explicit properties of the classes extending `wcf\data\DatabaseObject` but rather stored in `DatabaseObject::$data` and accessible via `DatabaseObject::__get($name)`, the IDE we use, PhpStorm, is neither able to autocomplete such property access nor to interfere the type of the property.
 
To solve this problem, `@property-read` tags must be added to the class documentation which registers the database table columns as public read-only properties:

```
 * @property-read	propertyType	$propertyName	property description
```

The properties have to be in the same order as the order in the database table.

The following table provides templates for common description texts so that similar database table columns have similar description texts.

| property | description template and example |
|----------|----------------------------------|
| unique object id | `unique id of the {object name}`<br>**example:** `unique id of the acl option`|
| id of the delivering package | `id of the package which delivers the {object name}`<br>**example:** `id of the package which delivers the acl option`|
| show order for nested structure | `position of the {object name} in relation to its siblings`<br>**example:** `position of the ACP menu item in relation to its siblings`|
| show order within different object | `position of the {object name} in relation to the other {object name}s in the {parent object name}`<br>**example:** `position of the label in relation to the other labels in the label group`|
| required permissions | `comma separated list of user group permissions of which the active user needs to have at least one to see (access, …) the {object name}`<br>**example:**`comma separated list of user group permissions of which the active user needs to have at least one to see the ACP menu item`|
| required options | `comma separated list of options of which at least one needs to be enabled for the {object name} to be shown (accessible, …)`<br>**example:**`comma separated list of options of which at least one needs to be enabled for the ACP menu item to be shown`|
| id of the user who has created the object | ``id of the user who created (wrote, …) the {object name} (or `null` if the user does not exist anymore (or if the {object name} has been created by a guest))``<br>**example:**``id of the user who wrote the comment or `null` if the user does not exist anymore or if the comment has been written by a guest``|
| name of the user who has created the object | ``name of the user (or guest) who created (wrote, …) the {object name}``<br>**example:**``name of the user or guest who wrote the comment``|
| additional data | `array with additional data of the {object name}`<br>**example:**`array with additional data of the user activity event`|
| time-related columns | `timestamp at which the {object name} has been created (written, …)`<br>**example:**`timestamp at which the comment has been written`|
| boolean options | ``is `1` (or `0`) if the {object name} … (and thus …), otherwise `0` (or `1`)``<br>**example:**``is `1` if the ad is disabled and thus not shown, otherwise `0` ``|
| `$cumulativeLikes` | ``cumulative result of likes (counting `+1`) and dislikes (counting `-1`) for the {object name}``<br>**example:**``cumulative result of likes (counting `+1`) and dislikes (counting `-1`) for the article``|
| `$comments` | `number of comments on the {object name}`<br>**example:**`number of comments on the article`|
| `$views` | `number of times the {object name} has been viewed`<br>**example:**`number of times the article has been viewed`|
| text field with potential language item name as value | `{text type} of the {object name} or name of language item which contains the {text type}`<br>**example:**`description of the cronjob or name of language item which contains the description`|
| `$objectTypeID` | ``id of the `{object type definition name}` object type``<br>**example:**``id of the `com.woltlab.wcf.modifiableContent` object type``|


## Database Object Editors

### Class Tags

Any database object editor class comment must have to following tags to properly support autocompletion by IDEs:

```php
/**
 * …
 * @method static	{DBO class name}	create(array $parameters = [])
 * @method		{DBO class name}	getDecoratedObject()
 * @mixin		{DBO class name}
 */
```

The only exception to this rule is if the class overwrites the `create()` method which itself has to be properly documentation then.

The first and second line makes sure that when calling the `create()` or `getDecoratedObject()` method, the return value is correctly recognized and not just a general `DatabaseObject` instance.
The third line tells the IDE (if `@mixin` is supported) that the database object editor decorates the database object and therefore offers autocompletion for properties and methods from the database object class itself.


## Runtime Caches

### Class Tags

Any class implementing the [IRuntimeCache](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/cache/runtime/IRuntimeCache.class.php) interface must have the following class tags:

```php
/**
 * …
 * @method	{DBO class name}[]	getCachedObjects()
 * @method	{DBO class name}	getObject($objectID)
 * @method	{DBO class name}[]	getObjects(array $objectIDs)
 */
```

These tags ensure that when calling any of the mentioned methods, the return value refers to the concrete database object and not just generically to [DatabaseObject](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/data/DatabaseObject.class.php).
