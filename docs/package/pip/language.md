# Language Package Installation Plugin

Registers new language items.

## Components

!!! info "The `languagecode` attribute is required and should specify the [ISO-639-1](https://en.wikipedia.org/wiki/ISO_639-1) language code."

The top level `<language>` node must contain a `languagecode` attribute.

### `<category>`

Each category must contain a `name` attribute containing two or three components consisting of alphanumeric character only, separated by a single full stop (`.`, U+002E).

#### `<item>`

Each language item must contain a `name` attribute containing at least three components consisting of alphanumeric character only, separated by a single full stop (`.`, U+002E). The `name` of the parent `<category>` node followed by a full stop must be a prefix of the `<item>`’s `name`.

!!! info "Wrap the text content inside a CDATA to avoid escaping of special characters."
!!! warning "Do not use the `{lang}` tag inside a language item."

The text content of the `<item>` node is the value of the language item. Language items that are not in the `wcf.global` category support template scripting.

## Example

```xml
<?xml version="1.0" encoding="UTF-8"?>
<language xmlns="http://www.woltlab.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.woltlab.com http://www.woltlab.com/XSD/2019/language.xsd" languagecode="de">
	<category name="wcf.example">
		<item name="wcf.example.foo"><![CDATA[<strong>Look!</strong>]]></item>
	</category>
</language>
```
