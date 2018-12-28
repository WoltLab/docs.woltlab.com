---
title: BBCode Package Installation Plugin
sidebar: sidebar
permalink: package_pip_bbcode.html
folder: package/pip
parent: package_pip
---

Registers new BBCodes.

## Components

Each bbcode is described as an `<bbcode>` element with the mandatory attribute `name`.
The `name` attribute must contain alphanumeric characters only and is exposed to the user.

### `<htmlopen>`

{% include callout.html content="Optional: Must not be provided if the BBCode is being processed a PHP class (`<classname>`)." type="info" %}

The contents of this tag are literally copied into the opening tag of the bbcode.

### `<htmlclose>`

{% include callout.html content="Optional: Must not be provided if `<htmlopen>` is not given." type="info" %}

Must match the `<htmlopen>` tag.
Do not provide for self-closing tags.

### `<classname>`

The name of the class providing the bbcode output,
the class has to implement the `wcf\system\bbcode\IBBCode` interface.

BBCodes can be statically converted to HTML during input processing using a
`wcf\system\html\metacode\converter\*MetaConverter` class. This class does not
need to be registered.

### `<wysiwygIcon>`

<span class="label label-info">Optional</span>

Name of the Font Awesome icon class to show in the editor toolbar.

### `<buttonLabel>`

{% include callout.html content="Optional: Must be provided if an icon is given." type="info" %}

Explanatory text to show when hovering the icon.

### `<sourcecode>`

{% include warning.html content="Do not set this to `1` if you don't specify a PHP class for processing. You must perform XSS sanitizing yourself!" %}

If set to `1` contents of this BBCode will not be interpreted,
but literally passed through instead.

### `<isBlockElement>`

Set to `1` if the output of this BBCode is a HTML block element (according to the HTML specification).

### `<attributes>`

Each bbcode is described as an `<attribute>` element with the mandatory attribute `name`.
The `name` attribute is a 0-indexed integer.

#### `<html>`

{% include callout.html content="Optional: Must not be provided if the BBCode is being processed a PHP class (`<classname>`)." type="info" %}

The contents of this tag are copied into the opening tag of the bbcode.
`%s` is replaced by the attribute value.

#### `<validationpattern>`

<span class="label label-info">Optional</span>

Defines a regular expression that is used to validate the value of the attribute.

#### `<required>`

<span class="label label-info">Optional</span>

Specifies whether this attribute must be provided.

#### `<useText>`

<span class="label label-info">Optional</span>
{% include callout.html content="Should only be set to `1` for the attribute with name `0`." type="info" %}

Specifies whether the text content of the BBCode should become this attribute's value.

## Example

```
<?xml version="1.0" encoding="UTF-8"?>
<data xmlns="http://www.woltlab.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.woltlab.com http://www.woltlab.com/XSD/vortex/bbcode.xsd">
	<import>
		<bbcode name="foo">
			<classname>wcf\system\bbcode\FooBBCode</classname>
			<attributes>
				<attribute name="0">
					<validationpattern>^\d+$</validationpattern>
					<required>1</required>
				</attribute>
			</attributes>
		</bbcode>
		
		<bbcode name="example">
			<htmlopen>div</htmlopen>
			<htmlclose>div</htmlclose>
			<isBlockElement>1</isBlockElement>
			<wysiwygicon>fa-bath</wysiwygicon>
			<buttonlabel>wcf.editor.button.example</buttonlabel>
		</bbcode>
	</import>
</data>
```
