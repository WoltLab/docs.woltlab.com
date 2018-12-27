---
title: User Option Package Installation Plugin
sidebar: sidebar
permalink: package_pip_user-option.html
folder: package/pip
parent: package_pip
---

Registers new user options (profile fields / user settings).
The behaviour of this package installation plugin closely follows the [option](package_pip_option.html) PIP.

## Category Components

The category definition works exactly like the option PIP.

## Option Components

The fields `hidden`, `supporti18n` and `requirei18n` do not apply.
The following extra fields are defined:

### `<required>`

Requires that a value is provided.

### `<askduringregistration>`

If set to `1` the field is shown during user registration in the frontend.

### `<editable>`

Bitfield with the following options (constants in `wcf\data\user\option\UserOption`)

| Name                                     | Value |
| ---------------------------------------- | ----- |
| EDITABILITY_OWNER                        | 1     |
| EDITABILITY_ADMINISTRATOR                | 2     |
| EDITABILITY_OWNER_DURING_REGISTRATION    | 4     |

### `<visible>`

Bitfield with the following options (constants in `wcf\data\user\option\UserOption`)

| Name                     | Value |
| ------------------------ | ----- |
| VISIBILITY_OWNER         | 1     |
| VISIBILITY_ADMINISTRATOR | 2     |
| VISIBILITY_REGISTERED    | 4     |
| VISIBILITY_GUEST         | 8     |

### `<searchable>`

If set to `1` the field is searchable.

### `<outputclass>`

PHP class responsible for output formatting of this field.
the class has to implement the `wcf\system\option\user\IUserOptionOutput` interface.

## Language Items

All relevant language items have to be put into the `wcf.user.option` language item category.

### Categories

If you install a category named `example`, you have to provide the language item `wcf.user.option.category.example`, which is used when displaying the options.
If you want to provide an optional description of the category, you have to provide the language item `wcf.user.option.category.example.description`.
Descriptions are only relevant for categories whose parent has a parent itself, i.e. categories on the third level.

### Options

If you install an option named `exampleOption`, you have to provide the language item `wcf.user.option.exampleOption`, which is used as a label for setting the option value.
If you want to provide an optional description of the option, you have to provide the language item `wcf.user.option.exampleOption.description`.
