# User Notification Event Package Installation Plugin

Registers new user notification events.

## Components

Each package installation plugin is described as an `<event>` element with the mandatory child `<name>`.

### `<objectType>`

!!! warning "The `(name, objectType)` pair must be unique."

The given object type must implement the `com.woltlab.wcf.notification.objectType` definition.

### `<classname>`

The name of the class providing the event's behaviour,
the class has to implement the `wcf\system\user\notification\event\IUserNotificationEvent` interface.

### `<preset>`

Defines whether this event is enabled by default.

### `<presetmailnotificationtype>`

!!! info "Avoid using this option, as sending unsolicited mail can be seen as spamming."

One of `instant` or `daily`.
Defines whether this type of email notifications is enabled by default.

### `<options>`

<span class="label label-info">Optional</span>

The options element can contain a comma-separated list of options of which at least one needs to be enabled for the notification type to be available.

### `<permissions>`

<span class="label label-info">Optional</span>

The permissions element can contain a comma-separated list of permissions of which the active user needs to have at least one for the notification type to be available.

## Example

{jinja{ codebox(
  title="userNotificationEvent.xml",
  language="xml",
  filepath="package/pip/userNotificationEvent.xml"
) }}
