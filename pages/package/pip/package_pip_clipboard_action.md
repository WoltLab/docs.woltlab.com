---
title: Clipboard Action Package Installation Plugin
sidebar: sidebar
permalink: package_pip_clipboard_action.html
folder: package/pip
parent: package_pip
---

Registers clipboard actions.

## Components

Each clipboard action is described as an `<action>` element with the mandatory attribute `name`.

### `<actionclassname>`

The name of the class used by the clipboard API to process the concrete action.
The class has to implement the `wcf\system\clipboard\action\IClipboardAction` interface, best by extending `wcf\system\clipboard\action\AbstractClipboardAction`.

### `<pages>`

Element with `<page>` children whose value contains the class name of the controller of the page on which the clipboard action is available.

### `<showorder>`

<span class="label label-info">Optional</span>

Determines at which position of the clipboard action list the action is shown.


## Example

```xml
<?xml version="1.0" encoding="UTF-8"?>
<data xmlns="http://www.woltlab.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.woltlab.com http://www.woltlab.com/XSD/tornado/clipboardAction.xsd">
	<import>
		<action name="delete">
			<actionclassname>wcf\system\clipboard\action\ExampleClipboardAction</actionclassname>
			<showorder>1</showorder>
			<pages>
				<page>wcf\acp\page\ExampleListPage</page>
			</pages>
		</action>
		<action name="foo">
			<actionclassname>wcf\system\clipboard\action\ExampleClipboardAction</actionclassname>
			<showorder>2</showorder>
			<pages>
				<page>wcf\acp\page\ExampleListPage</page>
			</pages>
		</action>
		<action name="bar">
			<actionclassname>wcf\system\clipboard\action\ExampleClipboardAction</actionclassname>
			<showorder>3</showorder>
			<pages>
				<page>wcf\acp\page\ExampleListPage</page>
			</pages>
		</action>
	</import>
</data>
```
