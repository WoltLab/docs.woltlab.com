---
title: Menu Package Installation Plugin
sidebar: sidebar
permalink: package_pip_menu.html
folder: package/pip
parent: package_pip
---

Deploy and manage menus that can be placed anywhere on the site.

## Components

Each item is described as a `<menu>` element with the mandatory attribute `identifier` that should follow the naming pattern `<packageIdentifier>.<MenuName>`, e.g. `com.woltlab.wcf.MainMenu`.

### `<title>`

{% include languageCode.html %}

The internal name displayed in the admin panel only, can be fully customized by the administrator and is immutable. Only one value is accepted and will be picked based on the site's default language, but you can provide localized values by including multiple `<title>` elements.

### `<box>`

The following elements of the [box PIP](package_pip_box.html) are supported, please refer to the documentation to learn more about them:

* `<position>`
* `<showHeader>`
* `<visibleEverywhere>`
* `<visibilityExceptions>`
* `cssClassName`

## Example

```xml
<?xml version="1.0" encoding="UTF-8"?>
<data xmlns="http://www.woltlab.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.woltlab.com http://www.woltlab.com/XSD/tornado/menu.xsd">
    <import>
        <menu identifier="com.woltlab.wcf.FooterLinks">
            <title language="de">Footer-Links</title>
            <title language="en">Footer Links</title>

            <box>
                <position>footer</position>
                <cssClassName>boxMenuLinkGroup</cssClassName>
                <showHeader>0</showHeader>
                <visibleEverywhere>1</visibleEverywhere>
            </box>
        </menu>
    </import>

    <delete>
        <menu identifier="com.woltlab.wcf.FooterLinks" />
    </delete>
</data>
```
