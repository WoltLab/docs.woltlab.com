---
title: User Interface - JavaScript API
sidebar: sidebar
permalink: javascript_new-api_ui.html
folder: javascript
---

## `Ui/Alignment`

Calculates the alignment of one element relative to another element, with support
for boundary constraints, alignment restrictions and additional pointer elements.

### `set(element: Element, referenceElement: Element, options: Object)`

Calculates and sets the alignment of the element `element`.

#### `verticalOffset: number`

_Defaults to `0`._

Creates a gap between the element and the reference element, in pixels.

#### `pointer: boolean`

_Defaults to `false`._

Sets the position of the pointer element, requires an existing child of the
element with the CSS class `.elementPointer`.

#### `pointerOffset: number`

_Defaults to `4`._

The margin from the left/right edge of the element and is used to avoid the
arrow from being placed right at the edge.

Does not apply when aligning the element to the reference elemnent's center.

#### `pointerClassNames: string[]`

_Defaults to `[]`._

If your element uses CSS-only pointers, such as using the `::before` or `::after`
pseudo selectors, you can specifiy two separate CSS class names that control the
alignment:

- `pointerClassNames[0]` is applied to the element when the pointer is displayed
   at the bottom.
- `pointerClassNames[1]` is used to align the pointer to the right side of the
  element.

#### `refDimensionsElement: Element`

_Defaults to `null`._

An alternative element that will be used to determine the position and dimensions
of the reference element. This can be useful if you reference element is contained
in a wrapper element with alternating dimensions.

#### `horizontal: string`

{% include callout.html content="This value is automatically flipped for RTL (right-to-left) languages, `left` is changed into `right` and vice versa." type="info" %}

_Defaults to `"left"`._

Sets the prefered alignment, accepts either `left` or `right`. The value `left`
instructs the module to align the element with the left boundary of the reference
element.

The `horizontal` alignment is used as the default and a flip only occurs, if there
is not enough space in the desired direction. If the element exceeds the boundaries
in both directions, the value of `horizontal` is used.

#### `vertical: string`

_Defaults to `"bottom"`._

Sets the prefered alignment, accepts either `bottom` or `top`. The value `bottom`
instructs the module to align the element below the reference element.

The `vertical` alignment is used as the default and a flip only occurs, if there
is not enough space in the desired direction. If the element exceeds the boundaries
in both directions, the value of `vertical` is used.

#### `allowFlip: string`

{% include callout.html content="The value for `horizontal` is automatically flipped for RTL (right-to-left) languages, `left` is changed into `right` and vice versa. This setting only controls the behavior when violating space constraints, therefore the aforementioned transformation is always applied." type="info" %}

_Defaults to `"both"`._

Restricts the automatic alignment flipping if the element exceeds the window
boundaries in the instructed direction.

- `both` - No restrictions.
- `horizontal` - Element can be aligned with the left _or_ the right boundary of
  the reference element, but the vertical position is fixed.
- `vertical` - Element can be aligned below _or_ above the reference element,
  but the vertical position is fixed.
- `none` - No flipping can occur, the element will be aligned regardless of
  any space constraints.

## `Ui/CloseOverlay`

Register elements that should be closed when the user clicks anywhere else, such
as drop-down menus or tooltips.

```js
require(["Ui/CloseOverlay"], function(UiCloseOverlay) {
  UiCloseOverlay.add("App/Foo", function() {
    // invoked, close something
  });
});
```

### `add(identifier: string, callback: () => void)`

Adds a callback that will be invoked when the user clicks anywhere else.

## `Ui/Confirmation`

Prompt the user to make a decision before carrying out an action, such as a safety
warning before permanently deleting content.

```js
require(["Ui/Confirmation"], function(UiConfirmation) {
  UiConfirmation.show({
    confirm: function() {
      // the user has confirmed the dialog
    },
    message: "Do you really want to continue?"
  });
});
```

### `show(options: Object)`

Displays a dialog overlay with actions buttons to confirm or reject the dialog.

#### `cancel: (parameters: Object) => void`

_Defaults to `null`._

Callback that is invoked when the dialog was rejected.

#### `confirm: (parameters: Object) => void`

_Defaults to `null`._

Callback that is invoked when the user has confirmed the dialog.

#### `message: string`

_Defaults to '""'._

Text that is displayed in the content area of the dialog, optionally this can
be HTML, but this requires `messageIsHtml` to be enabled.

#### `messageIsHtml`

_Defaults to `false`._

The `message` option is interpreted as text-only, setting this option to `true`
will cause the `message` to be evaluated as HTML.

#### `parameters: Object`

Optional list of parameter options that will be passed to the `cancel()` and
`confirm()` callbacks.

#### `template: string`

An optional HTML template that will be inserted into the dialog content area,
but after the `message` section.

## `Ui/Notification`

Displays a simple notification at the very top of the window, such as a success
message for Ajax based actions.

```js
require(["Ui/Notification"], function(UiNotification) {
  UiNotification.show(
    "Your changes have been saved.",
    function() {
      // this callback will be invoked after 2 seconds
    },
    "success"
  );
});
```

### `show(message: string, callback?: () => void, cssClassName?: string)`

Shows the notification and executes the callback after 2 seconds.

{% include links.html %}
