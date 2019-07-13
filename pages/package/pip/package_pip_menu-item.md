---
title: Menu Item Package Installation Plugin
sidebar: sidebar
permalink: package_pip_menu-item.html
folder: package/pip
parent: package_pip
---

Adds menu items to existing menus.

## Components

Each item is described as an `<item>` element with the mandatory attribute `identifier` that should follow the naming pattern `<packageIdentifier>.<PageName>`, e.g. `com.woltlab.wcf.Dashboard`.

### `<menu>`

The target menu that the item should be added to, requires the internal identifier set by creating a menu through the [menu.xml][package_pip_menu].

### `<title>`

{% include languageCode.html %}

The title is displayed as the link title of the menu item and can be fully customized by the administrator, thus is immutable after deployment. Supports multiple `<title>` elements to provide localized values.

### `<page>`

The page that the link should point to, requires the internal identifier set by creating a page through the [page.xml][package_pip_page].

## Example

```xml
<?xml version="1.0" encoding="UTF-8"?>
<data xmlns="http://www.woltlab.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.woltlab.com http://www.woltlab.com/XSD/tornado/menuItem.xsd">
    <import>
        <item identifier="com.woltlab.wcf.Dashboard">
            <menu>com.woltlab.wcf.MainMenu</menu>
            <title language="de">Dashboard</title>
            <title language="en">Dashboard</title>
            <page>com.woltlab.wcf.Dashboard</page>
        </item>
    </import>

    <delete>
        <item identifier="com.woltlab.wcf.FooterLinks" />
    </delete>
</data>
```

{% include links.html %}
