---
title: Language Package Installation Plugin
sidebar: sidebar
permalink: package_pip_language.html
folder: package/pip
parent: package_pip
---

Registers new language items.

## Components

{% include languageCode.html attribute="languagecode" %}

The top level `<language>` node must contain a `languagecode` attribute.

### `<category>`

Each category must contain a `name` attribute containing two or three components consisting of alphanumeric character only, separated by a single full stop (`.`, U+002E).

#### `<item>`

Each language item must contain a `name` attribute containing at least three components consisting of alphanumeric character only, separated by a single full stop (`.`, U+002E). The `name` of the parent `<category>` node followed by a full stop must be a prefix of the `<item>`’s `name`.

{% include tip.html content="Wrap the text content inside a CDATA to avoid escaping of special characters." %}
{% include warning.html content="Do not use the `{lang}` tag inside a language item." %}

The text content of the `<item>` node is the value of the language item. Language items that are not in the `wcf.global` category support template scripting.

## Example

```xml
<?xml version="1.0" encoding="UTF-8"?>
<language xmlns="http://www.woltlab.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.woltlab.com http://www.woltlab.com/XSD/tornado/language.xsd" languagecode="de">
	<category name="wcf.example">
		<item name="wcf.example.foo"><![CDATA[<strong>Look!</strong>]]></item>
	</category>
</language>
```
