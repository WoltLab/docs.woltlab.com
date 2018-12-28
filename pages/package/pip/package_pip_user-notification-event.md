---
title: User Notification Event Package Installation Plugin
sidebar: sidebar
permalink: package_pip_user-notification-event.html
folder: package/pip
parent: package_pip
---

Registers new user notification events.

## Components

Each package installation plugin is described as an `<event>` element with the mandatory child `<name>`.

### `<objectType>`

{% include warning.html content="The `(name, objectType)` pair must be unique." %}

The given object type must implement the `com.woltlab.wcf.notification.objectType` definition.

### `<classname>`

The name of the class providing the event's behaviour,
the class has to implement the `wcf\system\user\notification\event\IUserNotificationEvent` interface.

### `<preset>`

Defines whether this event is enabled by default.

### `<presetmailnotificationtype>`

{% include callout.html content="Avoid using this option, as sending unsolicited mail can be seen as spamming." type="info" %}

One of `instant` or `daily`.
Defines whether this type of email notifications is enabled by default.

### `<options>`

<span class="label label-info">Optional</span>

The options element can contain a comma-separated list of options of which at least one needs to be enabled for the notification type to be available.

### `<permissions>`

<span class="label label-info">Optional</span>

The permissions element can contain a comma-separated list of permissions of which the active user needs to have at least one for the notification type to be available.

## Example

```xml
<?xml version="1.0" encoding="UTF-8"?>
<data xmlns="http://www.woltlab.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.woltlab.com http://www.woltlab.com/XSD/vortex/userNotificationEvent.xsd">
	<import>
		<event>
			<name>like</name>
			<objecttype>com.woltlab.example.comment.like.notification</objecttype>
			<classname>wcf\system\user\notification\event\ExampleCommentLikeUserNotificationEvent</classname>
			<preset>1</preset>
			<options>module_like</options>
		</event>
	</import>
</data>
```
