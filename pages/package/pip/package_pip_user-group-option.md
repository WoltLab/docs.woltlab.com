---
title: User Group Option Package Installation Plugin
sidebar: sidebar
permalink: package_pip_user-group-option.html
folder: package/pip
parent: package_pip
---

Registers new user group options (“permissions”).
The behaviour of this package installation plugin closely follows the [option](package_pip_option.html) PIP.

## Category Components

The category definition works exactly like the option PIP.

## Option Components

The fields `hidden`, `supporti18n` and `requirei18n` do not apply.
The following extra fields are defined:

### `<(admin|mod|user)defaultvalue>`

Defines the `defaultvalue`s for subsets of the groups:

| Type  | Description                                                                                    |
| ----- | ---------------------------------------------------------------------------------------------- |
| admin | Groups where the `admin.user.accessibleGroups` user group option includes every group.         |
| mod   | Groups where the `mod.general.canUseModeration` is set to `true`.                              |
| user  | Groups where the internal group type is neither `UserGroup::EVERYONE` nor `UserGroup::GUESTS`. |

### `<usersonly>`

Makes the option unavailable for groups with the group type `UserGroup::GUESTS`.

## Language Items

All relevant language items have to be put into the `wcf.acp.group` language item category.

### Categories

If you install a category named `user.foo`, you have to provide the language item `wcf.acp.group.option.category.user.foo`, which is used when displaying the options.
If you want to provide an optional description of the category, you have to provide the language item `wcf.acp.group.option.category.user.foo.description`.
Descriptions are only relevant for categories whose parent has a parent itself, i.e. categories on the third level.

### Options

If you install an option named `user.foo.canBar`, you have to provide the language item `wcf.acp.group.option.user.foo.canBar`, which is used as a label for setting the option value.
If you want to provide an optional description of the option, you have to provide the language item `wcf.acp.group.option.user.foo.canBar.description`.
