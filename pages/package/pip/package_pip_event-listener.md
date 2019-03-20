---
title: Event Listener Package Installation Plugin
sidebar: sidebar
permalink: package_pip_event-listener.html
folder: package/pip
parent: package_pip
---

Registers event listeners.
An explanation of events and event listeners can be found [here](php_api_events.html).

## Components

Each event listener is described as an `<eventlistener>` element with a `name` attribute.
As the `name` attribute has only be introduced with WSC 3.0, it is not yet mandatory to allow backwards compatibility.
If `name` is not given, the system automatically sets the name based on the id of the event listener in the database.

### `<eventclassname>`

The event class name is the name of the class in which the event is fired.

### `<eventname>`

The event name is the name given when the event is fired to identify different events within the same class.
You can either give a single event name or a comma-separated list of event names in which case the event listener listens to all of the listed events.

### `<listenerclassname>`

The listener class name is the name of the class which is triggered if the relevant event is fired.
The PHP class has to implement the `wcf\system\event\listener\IParameterizedEventListener` interface.

{% include callout.html content="Legacy event listeners are only required to implement the deprecated `wcf\system\event\IEventListener` interface. When writing new code or update existing code, you should always implement the `wcf\system\event\listener\IParameterizedEventListener` interface!" type="warning" %}

### `<inherit>`

The inherit value can either be `0` (default value if the element is omitted) or `1` and determines if the event listener is also triggered for child classes of the given event class name.
This is the case if `1` is used as the value.

### `<environment>`

The value of the environment element can either be `admin` or `user` and is `user` if no value is given.
The value determines if the event listener will be executed in the frontend (`user`) or the backend (`admin`).

### `<nice>`

The nice value element can contain an integer value out of the interval `[-128,127]` with `0` being the default value if the element is omitted.
The nice value determines the execution order of event listeners.
Event listeners with smaller nice values are executed first.
If the nice value of two event listeners is equal, they are sorted by the listener class name.

{% include callout.html content="If you pass a value out of the mentioned interval, the value will be adjusted to the closest value in the interval." type="info" %}

### `<options>`

The options element can contain a comma-separated list of options of which at least one needs to be enabled for the event listener to be executed.

### `<permissions>`

The permissions element can contain a comma-separated list of permissions of which the active user needs to have at least one for the event listener to be executed.


## Example

```xml
<?xml version="1.0" encoding="UTF-8"?>
<data xmlns="http://www.woltlab.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.woltlab.com http://www.woltlab.com/XSD/tornado/eventListener.xsd">
	<import>
		<eventlistener name="inheritedAdminExample">
			<eventclassname>wcf\acp\form\UserAddForm</eventclassname>
			<eventname>assignVariables,readFormParameters,save,validate</eventname>
			<listenerclassname>wcf\system\event\listener\InheritedAdminExampleListener</listenerclassname>
			<inherit>1</inherit>
			<environment>admin</environment>
		</eventlistener>
		
		<eventlistener name="nonInheritedUserExample">
			<eventclassname>wcf\form\SettingsForm</eventclassname>
			<eventname>assignVariables</eventname>
			<listenerclassname>wcf\system\event\listener\NonInheritedUserExampleListener</listenerclassname>
		</eventlistener>
	</import>
	
	<delete>
		<eventlistener name="oldEventListenerName" />
	</delete>
</data>

```
