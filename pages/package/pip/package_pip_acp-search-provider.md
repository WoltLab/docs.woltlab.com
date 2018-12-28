---
title: ACP Search Provider Package Installation Plugin
sidebar: sidebar
permalink: package_pip_acp-search-provider.html
folder: package/pip
parent: package_pip
---

Registers data provider for the admin panel search.

## Components

Each acp search result provider is described as an `<acpsearchprovider>` element with the mandatory attribute `name`.

### `<classname>`

The name of the class providing the search results,
the class has to implement the `wcf\system\search\acp\IACPSearchResultProvider` interface.

### `<showorder>`

<span class="label label-info">Optional</span>

Determines at which position of the search result list the provided results are shown.

## Example

```xml
<?xml version="1.0" encoding="UTF-8"?>
<data xmlns="http://www.woltlab.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.woltlab.com http://www.woltlab.com/XSD/tornado/acpSearchProvider.xsd">
	<import>
		<acpsearchprovider name="com.woltlab.wcf.example">
			<classname>wcf\system\search\acp\ExampleACPSearchResultProvider</classname>
			<showorder>1</showorder>
		</acpsearchprovider>
	</import>
</data>
```
