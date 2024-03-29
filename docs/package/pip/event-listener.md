# Event Listener Package Installation Plugin

Registers event listeners.
An explanation of events and event listeners can be found [here](../../php/api/events.md).

## Components

Each event listener is described as an `<eventlistener>` element with a `name` attribute.
As the `name` attribute has only be introduced with WSC 3.0, it is not yet mandatory to allow backwards compatibility.
If `name` is not given, the system automatically sets the name based on the id of the event listener in the database.

### `<eventclassname>`

The event class name is the name of the class in which the event is fired.

### `<eventname>`

The event name is the name given when the event is fired to identify different events within the same class.
You can either give a single event name or a comma-separated list of event names in which case the event listener listens to all of the listed events.

!!! info "Since the introduction of [the new event system with version 5.5](../../migration/wsc54/php.md#events), the event name is optional and defaults to `:default`."

### `<listenerclassname>`

The listener class name is the name of the class which is triggered if the relevant event is fired.
The PHP class has to implement the `wcf\system\event\listener\IParameterizedEventListener` interface.

!!! warning "Legacy event listeners are only required to implement the deprecated `wcf\system\event\IEventListener` interface. When writing new code or update existing code, you should always implement the `wcf\system\event\listener\IParameterizedEventListener` interface!"

### `<inherit>`

The inherit value can either be `0` (default value if the element is omitted) or `1` and determines if the event listener is also triggered for child classes of the given event class name.
This is the case if `1` is used as the value.

### `<environment>`

The value of the environment element must be one of `user`, `admin` or `all` and defaults to `user` if no value is given.
The value determines if the event listener will be executed in the frontend (`user`), the backend (`admin`) or both (`all`).

### `<nice>`

The nice value element can contain an integer value out of the interval `[-128,127]` with `0` being the default value if the element is omitted.
The nice value determines the execution order of event listeners.
Event listeners with smaller nice values are executed first.
If the nice value of two event listeners is equal, they are sorted by the listener class name.

!!! info "If you pass a value out of the mentioned interval, the value will be adjusted to the closest value in the interval."

### `<options>`

!!! info "The use of `<options>` has been deprecated in WoltLab Suite 6.0. Use a regular `{if}` statement in the template instead."

The options element can contain a comma-separated list of options of which at least one needs to be enabled for the event listener to be executed.

### `<permissions>`

!!! info "The use of `<permissions>` has been deprecated in WoltLab Suite 6.0. Use a regular `{if}` statement in the template instead."

The permissions element can contain a comma-separated list of permissions of which the active user needs to have at least one for the event listener to be executed.


## Example

{jinja{ codebox(
  title="eventListener.xml",
  language="xml",
  filepath="package/pip/eventListener.xml"
) }}
