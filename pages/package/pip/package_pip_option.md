---
title: Option Package Installation Plugin
sidebar: sidebar
permalink: package_pip_option.html
folder: package/pip
parent: package_pip
---

Registers new options.
Options allow the administrator to configure the behaviour of installed packages.
The specified values are exposed as PHP constants.

## Category Components

Each category is described as an `<category>` element with the mandatory attribute `name`.

### `<parent>`

<span class="label label-info">Optional</span>

The category’s parent category.

### `<showorder>`

<span class="label label-info">Optional</span>

Specifies the order of this option within the parent category.

### `<options>`

<span class="label label-info">Optional</span>

The options element can contain a comma-separated list of options of which at least one needs to be enabled for the category to be shown to the administrator.

## Option Components

Each option is described as an `<option>` element with the mandatory attribute `name`.
The `name` is transformed into a PHP constant name by uppercasing it.

### `<categoryname>`

The option’s category.

### `<optiontype>`

The type of input to be used for this option.
Valid types are defined by the `wcf\system\option\*OptionType` classes.

### `<defaultvalue>`

The value that is set after installation of a package.
Valid values are defined by the `optiontype`.

### `<validationpattern>`

<span class="label label-info">Optional</span>

Defines a regular expression that is used to validate the value of a free form option (such as `text`).

### `<showorder>`

<span class="label label-info">Optional</span>

Specifies the order of this option within the category.

### `<selectoptions>`

<span class="label label-info">Optional</span>
{% include callout.html content="Defined only for `select`, `multiSelect` and `radioButton` types." type="warning" %}

Specifies a newline-separated list of selectable values.
Each line consists of an internal handle, followed by a colon (`:`, U+003A), followed by a language item.
The language item is shown to the administrator, the internal handle is what is saved and exposed to the code.

### `<enableoptions>`

<span class="label label-info">Optional</span>
{% include callout.html content="Defined only for `boolean`, `select` and `radioButton` types." type="warning" %}

Specifies a comma-separated list of options which should be visually enabled when this option is enabled.
A leading exclamation mark (`!`, U+0021) will disable the specified option when this option is enabled.
For `select` and `radioButton` types the list should be prefixed by the internal [`selectoptions`](#selectoptions) handle followed by a colon (`:`, U+003A).

This setting is a visual helper for the administrator only.
It does not have an effect on the server side processing of the option.

### `<hidden>`

<span class="label label-info">Optional</span>

If `hidden` is set to `1` the option will not be shown to the administrator.
It still can be modified programmatically.

### `<options>`

<span class="label label-info">Optional</span>

The options element can contain a comma-separated list of options of which at least one needs to be enabled for the option to be shown to the administrator.

### `<supporti18n>`

<span class="label label-info">Optional</span>

Specifies whether this option supports localized input.

### `<requirei18n>`

<span class="label label-info">Optional</span>

Specifies whether this option requires localized input (i.e. the administrator must specify a value for every installed language).

### `<*>`

<span class="label label-info">Optional</span>

Additional fields may be defined by specific types of options.
Refer to the documentation of these for further explanation.

## Language Items

All relevant language items have to be put into the `wcf.acp.option` language item category.

### Categories

If you install a category named `example.sub`, you have to provide the language item `wcf.acp.option.category.example.sub`, which is used when displaying the options.
If you want to provide an optional description of the category, you have to provide the language item `wcf.acp.option.category.example.sub.description`.
Descriptions are only relevant for categories whose parent has a parent itself, i.e. categories on the third level.

### Options

If you install an option named `module_example`, you have to provide the language item `wcf.acp.option.module_example`, which is used as a label for setting the option value.
If you want to provide an optional description of the option, you have to provide the language item `wcf.acp.option.module_example.description`.

## Example

```xml
<?xml version="1.0" encoding="UTF-8"?>
<data xmlns="http://www.woltlab.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.woltlab.com http://www.woltlab.com/XSD/tornado/option.xsd">
	<import>
		<categories>
			<category name="example" />
			<category name="example.sub">
				<parent>example</parent>
				<options>module_example</options>
			</category>
		</categories>
		
		<options>
			<option name="module_example">
				<categoryname>module.community</categoryname>
				<optiontype>boolean</optiontype>
				<defaultvalue>1</defaultvalue>
			</option>
			
			<option name="example_integer">
				<categoryname>example.sub</categoryname>
				<optiontype>integer</optiontype>
				<defaultvalue>10</defaultvalue>
				<minvalue>5</minvalue>
				<maxvalue>40</maxvalue>
			</option>
			
			<option name="example_select">
				<categoryname>example.sub</categoryname>
				<optiontype>select</optiontype>
				<defaultvalue>DESC</defaultvalue>
				<selectoptions>ASC:wcf.global.sortOrder.ascending
DESC:wcf.global.sortOrder.descending</selectoptions>
			</option>
		</options>
	</import>
	
	<delete>
		<option name="outdated_example" />
	</delete>
</data>
```
