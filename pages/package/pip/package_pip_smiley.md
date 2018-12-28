---
title: Smiley Package Installation Plugin
sidebar: sidebar
permalink: package_pip_smiley.html
folder: package/pip
parent: package_pip
---

Installs new smileys.

## Components

Each smiley is described as an `<smiley>` element with the mandatory attribute `name`.

### `<title>`

Short human readable description of the smiley.

### `<path(2x)?>`

{% include important.html content="The files must be installed using the [file](package_pip_file.html) PIP." %}

File path relative to the root of WoltLab Suite Core.
`path2x` is optional and being used for High-DPI screens.

### `<aliases>`

<span class="label label-info">Optional</span>

List of smiley aliases.
Aliases must be separated by a line feed character (`\n`, U+000A).

### `<showorder>`

<span class="label label-info">Optional</span>

Determines at which position of the smiley list the smiley is shown.

## Example

```xml
<?xml version="1.0" encoding="UTF-8"?>
<data xmlns="http://www.woltlab.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.woltlab.com http://www.woltlab.com/XSD/tornado/smiley.xsd">
	<import>
		<smiley name=":example:">
			<title>example</title>
			<path>images/smilies/example.png</path>
			<path2x>images/smilies/example@2x.png</path2x>
			<aliases><![CDATA[:alias:
:more_aliases:]]></aliases>
		</smiley>
	</import>
</data>
```
