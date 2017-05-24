---
title: WCF 2.1.x - Package Components
sidebar: sidebar
permalink: migration_wcf-21_package.html
folder: migration/wcf-21
---

## package.xml

### Short Instructions

Instructions can now omit the filename, causing them to use the default filename if defined by the package installation plugin (in short: `PIP`). Unless overridden it will default to the PIP's class name with the first letter being lower-cased, e.g. `EventListenerPackageInstallationPlugin` implies the filename `eventListener.xml`. The file is always assumed to be in the archive's root, files located in subdirectories need to be explicitly stated, just as it worked before.

Every PIP can define a custom filename if the default value cannot be properly derived. For example the `ACPMenu`-pip would default to `aCPMenu.xml`, requiring the class to explicitly override the default filename with `acpMenu.xml` for readability.

### Example

```xml
<instructions type="install">
    <!-- assumes `eventListener.xml` -->
    <instruction type="eventListener" />
    <!-- assumes `install.sql` -->
    <instruction type="sql" />
    <!-- assumes `language/*.xml` -->
    <instruction type="language" />

    <!-- exceptions -->

    <!-- assumes `files.tar` -->
    <instruction type="file" />
    <!-- no default value, requires relative path -->
    <instruction type="script">acp/install_com.woltlab.wcf_3.0.php</instruction>
</instructions>
```

### Exceptions

{% include callout.html content="These exceptions represent the built-in PIPs only, 3rd party plugins and apps may define their own exceptions." type="info" %}

| PIP | Default Value |
|-------|-------|
| `acpTemplate` | `acptemplates.tar` |
| `file` | `files.tar` |
| `language` | `language/*.xml` |
| `script` | (No default value) |
| `sql` | `install.sql` |
| `template` | `templates.tar` |

## acpMenu.xml

### Renamed Categories

The following categories have been renamed, menu items need to be adjusted to reflect the new names:

| Old Value | New Value |
|-------|-------|
| `wcf.acp.menu.link.system` | `wcf.acp.menu.link.configuration` |
| `wcf.acp.menu.link.display` | `wcf.acp.menu.link.customization` |
| `wcf.acp.menu.link.community` | `wcf.acp.menu.link.application` |

### Submenu Items

Menu items can now offer additional actions to be accessed from within the menu using an icon-based navigation. This step avoids filling the menu with dozens of `Add …` links, shifting the focus on to actual items. Adding more than one action is not recommended and you should at maximum specify two actions per item.

### Example

```xml
<!-- category -->
<acpmenuitem name="wcf.acp.menu.link.group">
    <parent>wcf.acp.menu.link.user</parent>
    <showorder>2</showorder>
</acpmenuitem>

<!-- menu item -->
<acpmenuitem name="wcf.acp.menu.link.group.list">
    <controller>wcf\acp\page\UserGroupListPage</controller>
    <parent>wcf.acp.menu.link.group</parent>
    <permissions>admin.user.canEditGroup,admin.user.canDeleteGroup</permissions>
</acpmenuitem>
<!-- menu item action -->
<acpmenuitem name="wcf.acp.menu.link.group.add">
    <controller>wcf\acp\form\UserGroupAddForm</controller>
    <!-- actions are defined by menu items of menu items -->
    <parent>wcf.acp.menu.link.group.list</parent>
    <permissions>admin.user.canAddGroup</permissions>
    <!-- required FontAwesome icon name used for display -->
    <icon>fa-plus</icon>
</acpmenuitem>
```

### Common Icon Names

You should use the same icon names for the (logically) same task, unifying the meaning of items and making the actions predictable.

| Meaning | Icon Name | Result |
|-------|-------|-------|
| Add or create | `fa-plus` | <i class="fa fa-plus"></i> |
| Search | `fa-search` | <i class="fa fa-search"></i> |
| Upload | `fa-upload` | <i class="fa fa-upload"></i> |

## box.xml

The [box][package_pip_box] PIP has been added.

## cronjob.xml

{% include callout.html content="Legacy cronjobs are assigned a non-deterministic generic name, the only way to assign them a name is removing them and then adding them again." type="warning" %}

Cronjobs can now be assigned a name using the name attribute as in `<cronjob name="com.woltlab.wcf.refreshPackageUpdates">`, it will be used to identify cronjobs during an update or delete.

## eventListener.xml

{% include callout.html content="Legacy event listeners are assigned a non-deterministic generic name, the only way to assign them a name is removing them and then adding them again." type="warning" %}

Event listeners can now be assigned a name using the name attribute as in `<eventlistener name="sessionPageAccessLog">`, it will be used to identify event listeners during an update or delete.

## menu.xml

The [menu][package_pip_menu] PIP has been added.

## menuItem.xml

The [menuItem][package_pip_menu-item] PIP has been added.

## objectType.xml

The definition `com.woltlab.wcf.user.dashboardContainer` has been removed, it was previously used to register pages that qualify for dashboard boxes. Since WoltLab Suite 3.0, all pages registered through the `page.xml` are valid containers and therefore there is no need for this definition anymore.

The definitions `com.woltlab.wcf.page` and `com.woltlab.wcf.user.online.location` have been superseded by the `page.xml`, they're no longer supported.

## option.xml

The `module.display` category has been renamed into `module.customization`.

## page.xml

The [page][package_pip_page] PIP has been added.

## pageMenu.xml

The `pageMenu.xml` has been superseded by the `page.xml` and is no longer available.

{% include links.html %}
