---
title: User Menu Package Installation Plugin
sidebar: sidebar
permalink: package_pip_user-menu.html
folder: package/pip
parent: package_pip
---

Registers new user menu items.

## Components

Each item is described as an `<usermenuitem>` element with the mandatory attribute `name`.

### `<parent>`

<span class="label label-info">Optional</span>

The item’s parent item.

### `<showorder>`

<span class="label label-info">Optional</span>

Specifies the order of this item within the parent item.

### `<controller>`

The fully qualified class name of the target controller.
If not specified this item serves as a category.

### `<link>`

Additional components if `<controller>` is set,
the full external link otherwise.

### `<iconclassname>`

{% include tip.html content="Use an icon only for top-level items." %}

Name of the Font Awesome icon class.

### `<options>`

<span class="label label-info">Optional</span>

The options element can contain a comma-separated list of options of which at least one needs to be enabled for the menu item to be shown.

### `<permissions>`

<span class="label label-info">Optional</span>

The permissions element can contain a comma-separated list of permissions of which the active user needs to have at least one for the menu item to be shown.

### `<classname>`

The name of the class providing the user menu item’s behaviour,
the class has to implement the `wcf\system\menu\user\IUserMenuItemProvider` interface.



## Example

```xml
<?xml version="1.0" encoding="UTF-8"?>
<data xmlns="http://www.woltlab.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.woltlab.com http://www.woltlab.com/XSD/vortex/userMenu.xsd">
	<import>
		<usermenuitem name="wcf.user.menu.foo">
			<iconclassname>fa-home</iconclassname>
		</usermenuitem>
		
		<usermenuitem name="wcf.user.menu.foo.bar">
			<controller>wcf\page\FooBarListPage</controller>
			<parent>wcf.user.menu.foo</parent>
			<permissions>user.foo.canBar</permissions>
			<classname>wcf\system\menu\user\FooBarMenuItemProvider</classname>
		</usermenuitem>
		
		<usermenuitem name="wcf.user.menu.foo.baz">
			<controller>wcf\page\FooBazListPage</controller>
			<parent>wcf.user.menu.foo</parent>
			<permissions>user.foo.canBaz</permissions>
			<options>module_foo_bar</options>
		</usermenuitem>
	</import>
</data>
```
