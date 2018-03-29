---
title: Core Object Package Installation Plugin
sidebar: sidebar
permalink: package_pip_core-object.html
folder: package/pip
parent: package_pip
---

Registers `wcf\system\SingletonFactory` objects to be accessible in templates.

## Components

Each item is described as a `<coreobject>` element with the mandatory element `objectname`.

### `<objectname>`

The fully qualified class name of the class.

## Example

```xml
<?xml version="1.0" encoding="UTF-8"?>
<data xmlns="http://www.woltlab.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.woltlab.com http://www.woltlab.com/XSD/tornado/coreObject.xsd">
	<import>
		<coreobject>
			<objectname>wcf\system\example\ExampleHandler</objectname>
		</coreobject>
	</import>
</data>
```

This object can be accessed in templates via `$__wcf->getExampleHandler()` (in general: the method name begins with `get` and ends with the unqualified class name).
