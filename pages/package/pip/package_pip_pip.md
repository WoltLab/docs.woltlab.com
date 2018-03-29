---
title: Package Installation Plugin Package Installation Plugin
sidebar: sidebar
permalink: package_pip_pip.html
folder: package/pip
parent: package_pip
---

Registers new package installation plugins.

## Components

Each package installation plugin is described as an `<pip>` element with a `name` attribute and a PHP classname as the text content.

{% include callout.html content="The package installation plugin’s class file must be installed into the `wcf` application and must not include classes outside the `\wcf\*` hierarchy to allow for proper uninstallation!" type="warning" %}

## Example

```xml
<?xml version="1.0" encoding="UTF-8"?>
<data xmlns="http://www.woltlab.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.woltlab.com http://www.woltlab.com/XSD/tornado/packageInstallationPlugin.xsd">
	<import>
		<pip name="custom">wcf\system\package\plugin\CustomPackageInstallationPlugin</pip>
	</import>
	<delete>
		<pip name="outdated" />
	</delete>
</data>
```
