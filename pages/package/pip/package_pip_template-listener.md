---
title: Template Listener Package Installation Plugin
sidebar: sidebar
permalink: package_pip_template-listener.html
folder: package/pip
parent: package_pip
---

Registers template listeners.
Template listeners supplement [event listeners](package_pip_event-listener.html), which modify server side behaviour, by adding additional template code to display additional elements.
The added template code behaves as if it was part of the original template (i.e. it has access to all local variables).

## Components

Each event listener is described as an `<templatelistener>` element with a `name` attribute.
As the `name` attribute has only be introduced with WSC 3.0, it is not yet mandatory to allow backwards compatibility.
If `name` is not given, the system automatically sets the name based on the id of the event listener in the database.

### `<templatename>`

The template name is the name of the template in which the event is fired. It correspondes to the `eventclassname` field of event listeners.

### `<eventname>`

The event name is the name given when the event is fired to identify different events within the same template.

### `<templatecode>`

The given template code is literally copied into the target template during compile time.
The original template is not modified.
If multiple template listeners listen to a single event their output is concatenated using the line feed character (`\n`, U+000A) in the order defined by the [`niceValue`](#niceValue).

{% include callout.html content="It is recommend that the only code is an `{include}` of a template to enable changes by the administrator. Names of templates included by a template listener start with two underscores by convention." type="warning" %}

### `<environment>`

The value of the environment element can either be `admin` or `user` and is `user` if no value is given.
The value determines if the template listener will be executed in the frontend (`user`) or the backend (`admin`).

### `<nice>`

<span class="label label-info">Optional</span>

The nice value element can contain an integer value out of the interval `[-128,127]` with `0` being the default value if the element is omitted.
The nice value determines the execution order of template listeners.
Template listeners with smaller nice values are executed first.
If the nice value of two template listeners is equal, the order is undefined.

{% include callout.html content="If you pass a value out of the mentioned interval, the value will be adjusted to the closest value in the interval." type="info" %}

### `<options>`

<span class="label label-info">Optional</span>

The options element can contain a comma-separated list of options of which at least one needs to be enabled for the template listener to be executed.

### `<permissions>`

<span class="label label-info">Optional</span>

The permissions element can contain a comma-separated list of permissions of which the active user needs to have at least one for the template listener to be executed.

## Example

```xml
<?xml version="1.0" encoding="UTF-8"?>
<data xmlns="http://www.woltlab.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.woltlab.com http://www.woltlab.com/XSD/templatelistener.xsd">
	<import>
		<templatelistener name="example">
			<environment>user</environment>
			<templatename>headIncludeJavaScript</templatename>
			<eventname>javascriptInclude</eventname>
			<templatecode><![CDATA[{include file='__myCustomJavaScript'}]]></templatecode>
		</templatelistener>
	</import>
	
	<delete>
		<templatelistener name="oldTemplateListenerName" />
	</delete>
</data>
```
