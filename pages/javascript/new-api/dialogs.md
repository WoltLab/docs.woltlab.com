---
title: Dialogs - JavaScript API
sidebar: sidebar
permalink: javascript_new-api_dialogs.html
folder: javascript
---

## Introduction

Dialogs are full screen overlays that cover the currently visible window area
using a semi-opague backdrop and a prominently placed dialog window in the
foreground. They shift the attention away from the original content towards the
dialog and usually contain additional details and/or dedicated form inputs.

## `_dialogSetup()`

The lazy initialization is performed upon the first invocation from the callee,
using the magic `_dialogSetup()` method to retrieve the basic configuration for
the dialog construction and any event callbacks.

```js
// App/Foo.js
define(["Ui/Dialog"], function(UiDialog) {
  "use strict";

  function Foo() {};
  Foo.prototype = {
    bar: function() {
      // this will issue an ajax request with the parameter `value` set to `1`
      UiDialog.open(this);
    },

    _dialogSetup: function() {
      return {
        id: "myDialog",
        source: "<p>Hello World!</p>",
        options: {
          onClose: function() {
            // the fancy dialog was closed!
          }
        }
      }
    }
  };

  return Foo;
});
```

### `id: string`

The `id` is used to identify a dialog on runtime, but is also part of the first-
time setup when the dialog has not been opened before. If `source` is `undefined`,
the module attempts to construct the dialog using an element with the same id.

### `source: any`

There are six different types of value that `source` does allow and each of them
changes how the initial dialog is constructed:

1. `undefined`  
  The dialog exists already and the value of `id` should be used to identify the
  element.
2. `null`  
  The HTML is provided using the second argument of `.open()`.
3. `() => void`  
  If the `source` is a function, it is executed and is expected to start the
  dialog initialization itself.
4. `Object`  
  Plain objects are interpreted as parameters for an Ajax request, in particular
  `source.data` will be used to issue the request. It is possible to specify the
  key `source.after` as a callback `(content: Element, responseData: Object) => void`
  that is executed after the dialog was opened.
5. `string`  
  The string is expected to be plain HTML that should be used to construct the
  dialog.
6. `DocumentFragment`  
  A new container `<div>` with the provided `id` is created and the contents of
  the `DocumentFragment` is appended to it. This container is then used for the
  dialog.

### `options: Object`

All configuration options and callbacks are handled through this object.

#### `options.backdropCloseOnClick: boolean`

_Defaults to `true`._

Clicks on the dialog backdrop will close the top-most dialog. This option will
be force-disabled if the option `closeable` is set to `false`.

#### `options.closable: boolean`

_Defaults to `true`._

Enables the close button in the dialog title, when disabled the dialog can be
closed through the `.close()` API call only.

#### `options.closeButtonLabel: string`

_Defaults to `Language.get("wcf.global.button.close")`._

The phrase that is displayed in the tooltip for the close button.

#### `options.closeConfirmMessage: string`

_Defaults to `""`._

Shows a [confirmation dialog][javascript_new-api_ui] using the configured message
before closing the dialog. The dialog will not be closed if the dialog is
rejected by the user.

#### `options.title: string`

_Defaults to `""`._

The phrase that is displayed in the dialog title.

#### `options.onBeforeClose: (id: string) => void`

_Defaults to `null`._

The callback is executed when the user clicks on the close button or, if enabled,
on the backdrop. The callback is responsible to close the dialog by itself, the
default close behavior is automatically prevented.

#### `options.onClose: (id: string) => void`

_Defaults to `null`._

The callback is notified once the dialog is about to be closed, but is still
visible at this point. It is not possible to abort the close operation at this
point.

#### `options.onShow: (content: Element) => void`

_Defaults to `null`._

Receives the dialog content element as its only argument, allowing the callback
to modify the DOM or to register event listeners before the dialog is presented
to the user. The dialog is already visible at call time, but the dialog has not
been finalized yet.

## `setTitle(id: string | Object, title: string)`

Sets the title of a dialog.

## `setCallback(id: string | Object, key: string, value: (data: any) => void | null)`

Sets a callback function after the dialog initialization, the special value
`null` will remove a previously set callback. Valid values for `key` are
`onBeforeClose`, `onClose` and `onShow`.

## `rebuild(id: string | Object)`

Rebuilds a dialog by performing various calculations on the maximum dialog
height in regards to the overflow handling and adjustments for embedded forms.
This method is automatically invoked whenever a dialog is shown, after invoking
the `options.onShow` callback.

## `close(id: string | Object)`

Closes an open dialog, this will neither trigger a confirmation dialog, nor does
it invoke the `options.onBeforeClose` callback. The `options.onClose` callback
will always be invoked, but it cannot abort the close operation.

## `getDialog(id: string | Object): Object`

{% include callout.html content="This method returns an internal data object by reference, any modifications made do have an effect on the dialogs behavior and in particular no validation is performed on the modification. It is strongly recommended to use the `.set*()` methods only." type="warning" %}

Returns the internal dialog data that is attached to a dialog. The most important
key is `.content` which holds a reference to the dialog's inner content element.

## `isOpen(id: string | Object): boolean`

Returns true if the dialog exists and is open.

{% include links.html %}
