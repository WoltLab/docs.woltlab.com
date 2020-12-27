---
title: Event Handling - JavaScript API
sidebar: sidebar
permalink: javascript_new-api_events.html
folder: javascript
---

## `EventKey`

This class offers a set of static methods that can be used to determine if some
common keys are being pressed. Internally it compares either the `.key` property
if it is supported or the value of `.which`.

```js
require(["EventKey"], function(EventKey) {
  elBySel(".some-input").addEventListener("keydown", function(event) {
    if (EventKey.Enter(event)) {
      // the `Enter` key was pressed
    }
  });
});
```

### `ArrowDown(event: KeyboardEvent): boolean`

Returns true if the user has pressed the `↓` key.

### `ArrowLeft(event: KeyboardEvent): boolean`

Returns true if the user has pressed the `←` key.

### `ArrowRight(event: KeyboardEvent): boolean`

Returns true if the user has pressed the `→` key.

### `ArrowUp(event: KeyboardEvent): boolean`

Returns true if the user has pressed the `↑` key.

### `Comma(event: KeyboardEvent): boolean`

Returns true if the user has pressed the `,` key.

### `Enter(event: KeyboardEvent): boolean`

Returns true if the user has pressed the `↲` key.

### `Escape(event: KeyboardEvent): boolean`

Returns true if the user has pressed the `Esc` key.

### `Tab(event: KeyboardEvent): boolean`

Returns true if the user has pressed the `↹` key.

## `EventHandler`

A synchronous event system based on string identifiers rather than DOM elements,
similar to the PHP event system in WoltLab Suite. Any components can listen to
events or trigger events itself at any time.

### Identifiying Events with the Developer Tools

The Developer Tools in WoltLab Suite 3.1 offer an easy option to identify existing
events that are fired while code is being executed. You can enable this watch
mode through your browser's console using `Devtools.toggleEventLogging()`:

```
> Devtools.toggleEventLogging();
<   Event logging enabled
< [Devtools.EventLogging] Firing event: bar @ com.example.app.foo
< [Devtools.EventLogging] Firing event: baz @ com.example.app.foo
```

### `add(identifier: string, action: string, callback: (data: Object) => void): string`

Adding an event listeners returns a randomly generated UUIDv4 that is used to
identify the listener. This UUID is required to remove a specific listener through
the `remove()` method.

### `fire(identifier: string, action: string, data?: Object)`

Triggers an event using an optional `data` object that is passed to each listener
by reference.

### `remove(identifier: string, action: string, uuid: string)`

Removes a previously registered event listener using the UUID returned by `add()`.

### `removeAll(identifier: string, action: string)`

Removes all event listeners registered for the provided `identifier` and `action`.

### `removeAllBySuffix(identifier: string, suffix: string)`

Removes all event listeners for an `identifier` whose action ends with the value
of `suffix`.

{% include links.html %}
