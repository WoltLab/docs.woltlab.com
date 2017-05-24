---
title: Package Installation Plugins
sidebar: sidebar
permalink: php_api_package_installation_plugins.html
folder: php/api
---

A package installation plugin (PIP) defines the behavior to handle a specific [instruction](package_package-xml.html#instruction) during package installation, update or uninstallation.

## `AbstractPackageInstallationPlugin`

Any package installation plugin has to implement the [IPackageInstallationPlugin](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/package/plugin/IPackageInstallationPlugin.class.php) interface.
It is recommended however, to extend the abstract implementation [AbstractPackageInstallationPlugin](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/package/plugin/AbstractPackageInstallationPlugin.class.php) of this interface instead of directly implementing the interface.
The abstract implementation will always provide sane methods in case of any API changes.

### Class Members

Package Installation Plugins have a few notable class members easing your work:

#### `$installation`

This member contains an instance of [PackageInstallationDispatcher](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/package/PackageInstallationDispatcher.class.php) which provides you with all meta data related to the current package being processed.
The most common usage is the retrieval of the package ID via `$this->installation->getPackageID()`.

#### `$application`

Represents the abbreviation of the target application, e.g. `wbb` (default value: `wcf`), used for the name of database table in which the installed data is stored.


## `AbstractXMLPackageInstallationPlugin`

[AbstractPackageInstallationPlugin](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/package/plugin/AbstractPackageInstallationPlugin.class.php) is the default implementation for all package installation plugins based upon a single XML document.
It handles the evaluation of the document and provide you an object-orientated approach to handle its data.

### Class Members

#### `$className`

Value must be the qualified name of a class deriving from [DatabaseObjectEditor](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/data/DatabaseObjectEditor.class.php) which is used to create and update objects.

#### `$tagName`

Specifies the tag name within a `<import>` or `<delete>` section of the XML document used for each installed object.

#### `prepareImport(array $data)`

The passed array `$data` contains the parsed value from each evaluated tag in the `<import>` section:

- `$data['elements']` contains a list of tag names and their value.
- `$data['attributes']` contains a list of attributes present on the tag identified by [$tagName](#tagname).

This method should return an one-dimensional array, where each key maps to the corresponding database column name (key names are case-sensitive).
It will be passed to either `DatabaseObjectEditor::create()` or `DatabaseObjectEditor::update()`.

Example:

```php
<?php
return [
	'environment' => $data['elements']['environment'],
	'eventName' => $data['elements']['eventname'],
	'name' => $data['attributes']['name']
];
```

#### `validateImport(array $data)`

The passed array `$data` equals the data returned by [prepareImport()](#prepareimportarray-data).
This method has no return value, instead you should throw an exception if the passed data is invalid.


#### `findExistingItem(array $data)`

The passed array `$data` equals the data returned by [prepareImport()](#prepareimportarray-data).
This method is expected to return an array with two keys:

- `sql` contains the SQL query with placeholders.
- `parameters` contains an array with values used for the SQL query.

#### 2.5.3. Example

```php
<?php
$sql = "SELECT	*
	FROM	wcf".WCF_N."_".$this->tableName."
	WHERE	packageID = ?
		AND name = ?
		AND templateName = ?
		AND eventName = ?
		AND environment = ?";
$parameters = [
	$this->installation->getPackageID(),
	$data['name'],
	$data['templateName'],
	$data['eventName'],
	$data['environment']
];

return [
	'sql' => $sql,
	'parameters' => $parameters
];
```

#### `handleDelete(array $items)`

The passed array `$items` contains the original node data, similar to [prepareImport()](#prepareimportarray-data).
You should make use of this data to remove the matching element from database.

Example:
```php
<?php
$sql = "DELETE FROM	wcf".WCF_N."_".$this->tableName."
	WHERE		packageID = ?
			AND environment = ?
			AND eventName = ?
			AND name = ?
			AND templateName = ?";
$statement = WCF::getDB()->prepareStatement($sql);
foreach ($items as $item) {
	$statement->execute([
		$this->installation->getPackageID(),
		$item['elements']['environment'],
		$item['elements']['eventname'],
		$item['attributes']['name'],
		$item['elements']['templatename']
	]);
}
```

#### `postImport()`

Allows you to (optionally) run additionally actions after all elements were processed.


## `AbstractOptionPackageInstallationPlugin`

[AbstractOptionPackageInstallationPlugin](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/package/plugin/AbstractOptionPackageInstallationPlugin.class.php) is an abstract implementation for options, used for:

- ACL Options
- Options
- User Options
- User Group Options

### Differences to `AbstractXMLPackageInstallationPlugin`

#### `$reservedTags`

`$reservedTags` is a list of reserved tag names so that any tag encountered but not listed here will be added to the database column `additionalData`.
This allows options to store arbitrary data which can be accessed but were not initially part of the PIP specifications.
