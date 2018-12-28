---
title: User Profile Menu Package Installation Plugin
sidebar: sidebar
permalink: package_pip_user-profile-menu.html
folder: package/pip
parent: package_pip
---

Registers new user profile tabs.

## Components

Each tab is described as an `<userprofilemenuitem>` element with the mandatory attribute `name`.

### `<classname>`

The name of the class providing the tab’s behaviour,
the class has to implement the `wcf\system\menu\user\profile\content\IUserProfileMenuContent` interface.

### `<showorder>`

<span class="label label-info">Optional</span>

Determines at which position of the tab list the tab is shown.

### `<options>`

<span class="label label-info">Optional</span>

The options element can contain a comma-separated list of options of which at least one needs to be enabled for the tab to be shown.

### `<permissions>`

<span class="label label-info">Optional</span>

The permissions element can contain a comma-separated list of permissions of which the active user needs to have at least one for the tab to be shown.

## Example

```xml
<?xml version="1.0" encoding="utf-8"?>
<data xmlns="http://www.woltlab.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.woltlab.com http://www.woltlab.com/XSD/tornado/userProfileMenu.xsd">
	<import>
		<userprofilemenuitem name="example">
			<classname>wcf\system\menu\user\profile\content\ExampleProfileMenuContent</classname>
			<showorder>3</showorder>
			<options>module_example</options>
		</userprofilemenuitem>
	</import>
</data>
```
