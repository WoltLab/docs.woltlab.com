---
title: Cronjob Package Installation Plugin
sidebar: sidebar
permalink: package_pip_cronjob.html
folder: package/pip
parent: package_pip
---

Registers new cronjobs.
The cronjob schedular works similar to the `cron(8)` daemon, which might not available to web applications on regular webspaces.
The main difference is that WoltLab Suite’s cronjobs do not guarantee execution at the specified points in time:
WoltLab Suite’s cronjobs are triggered by regular visitors in an AJAX request, once the next execution point lies in the past.

## Components

Each cronjob is described as an `<cronjob>` element with the mandatory attribute `name`.

### `<classname>`

The name of the class providing the cronjob's behaviour,
the class has to implement the `wcf\system\cronjob\ICronjob` interface.

### `<description>`

{% include languageCode.html requirement="optional" %}

Provides a human readable description for the administrator.

### `<start*>`

All of the five `startMinute`, `startHour`, `startDom` (Day Of Month), `startMonth`, `startDow` (Day Of Week) are required.
They correspond to the fields in `crontab(5)` of a cron daemon and accept the same syntax.

### `<canBeEdited>`

Controls whether the administrator may edit the fields of the cronjob.

### `<canBeDisabled>`

Controls whether the administrator may disable the cronjob.

### `<options>`

The options element can contain a comma-separated list of options of which at least one needs to be enabled for the template listener to be executed.

## Example

```xml
<?xml version="1.0" encoding="UTF-8"?>
<data xmlns="http://www.woltlab.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.woltlab.com http://www.woltlab.com/XSD/tornado/cronjob.xsd">
	<import>
		<cronjob name="com.example.package.example">
			<classname>wcf\system\cronjob\ExampleCronjob</classname>
			<description>Serves as an example</description>
			<description language="de">Stellt ein Beispiel dar</description>
			<startminute>0</startminute>
			<starthour>2</starthour>
			<startdom>*/2</startdom>
			<startmonth>*</startmonth>
			<startdow>*</startdow>
			<canbeedited>1</canbeedited>
			<canbedisabled>1</canbedisabled>
		</cronjob>
	</import>
</data>
```

