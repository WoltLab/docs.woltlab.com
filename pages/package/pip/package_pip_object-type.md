---
title: Object Type Package Installation Plugin
sidebar: sidebar
permalink: package_pip_object-type.html
folder: package/pip
parent: package_pip
---

Registers an object type.
Read about object types in the [objectTypeDefinition](package_pip_object-type-definition.html) PIP.

## Components

Each item is described as a `<type>` element with the mandatory child `<name>` that should follow the naming pattern `<packageIdentifier>.<definition>`, e.g. `com.woltlab.wcf.example`.

### `<definitionname>`

The `<name>` of the [objectTypeDefinition](package_pip_object-type-definition.html).

### `<classname>`

The name of the class providing the object types's behaviour,
the class has to implement the `<interfacename>` interface of the object type definition.

### `<*>`

<span class="label label-info">Optional</span>

Additional fields may be defined for specific definitions of object types.
Refer to the documentation of these for further explanation.

## Example

```xml
<?xml version="1.0" encoding="UTF-8"?>
<data xmlns="http://www.woltlab.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.woltlab.com http://www.woltlab.com/XSD/vortex/objectType.xsd">
	<import>
		<type>
			<name>com.woltlab.wcf.example</name>
			<definitionname>com.woltlab.wcf.rebuildData</definitionname>
			<classname>wcf\system\worker\ExampleRebuildWorker</classname>
			<nicevalue>130</nicevalue>
		</type>
	</import>
</data>
```
