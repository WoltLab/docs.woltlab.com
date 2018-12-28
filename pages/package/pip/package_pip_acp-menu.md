---
title: ACP Menu Package Installation Plugin
sidebar: sidebar
permalink: package_pip_acp-menu.html
folder: package/pip
parent: package_pip
---

Registers new ACP menu items.

## Components

Each item is described as an `<acpmenuitem>` element with the mandatory attribute `name`.

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

### `<icon>`

{% include tip.html content="Use an icon only for top-level and 4th-level items." %}

Name of the Font Awesome icon class.

### `<options>`

<span class="label label-info">Optional</span>

The options element can contain a comma-separated list of options of which at least one needs to be enabled for the tab to be shown.

### `<permissions>`

<span class="label label-info">Optional</span>

The permissions element can contain a comma-separated list of permissions of which the active user needs to have at least one for the tab to be shown.

## Example

```xml
<?xml version="1.0" encoding="UTF-8"?>
<data xmlns="http://www.woltlab.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.woltlab.com http://www.woltlab.com/XSD/vortex/acpMenu.xsd">
	<import>
		<acpmenuitem name="foo.acp.menu.link.example">
			<parent>wcf.acp.menu.link.application</parent>
		</acpmenuitem>

		<acpmenuitem name="foo.acp.menu.link.example.list">
			<controller>foo\acp\page\ExampleListPage</controller>
			<parent>foo.acp.menu.link.example</parent>
			<permissions>admin.foo.canManageExample</permissions>
			<showorder>1</showorder>
		</acpmenuitem>

		<acpmenuitem name="foo.acp.menu.link.example.add">
			<controller>foo\acp\form\ExampleAddForm</controller>
			<parent>foo.acp.menu.link.example.list</parent>
			<permissions>admin.foo.canManageExample</permissions>
			<icon>fa-plus</icon>
		</acpmenuitem>
	</import>
</data>
```
