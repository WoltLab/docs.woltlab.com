# Migrating from WoltLab Suite 5.5 - Dialogs

## The State of Dialogs in WoltLab Suite 5.5 and earlier

In the past dialogs have been used for all kinds of purposes, for example, to provide more details.
Dialogs make it incredibly easy to add extra information or forms to an existing page without giving much thought: A simple button is all that it takes to show a dialog.

This has lead to an abundance of dialogs that have been used in a lot of places where dialogs are not the right choice, something we are guilty of in a lot of cases.
A lot of research has gone into the accessibility of dialogs and the general recommendations towards their usage and the behavior.

One big issue of dialogs have been their inconsistent appearance in terms of form buttons and their (lack of) keyboard support for input fields.
WoltLab Suite 6.0 provides a completely redesigned API that strives to make the process of creating dialogs much easier and features a consistent keyboard support out of the box.

## Conceptual Changes

Dialogs are a powerful tool, but as will all things, it is easy to go overboard and eventually one starts using dialogs out of convenience rather than necessity.
In general dialogs should only be used when you need to provide information out of flow, for example, for urgent error messages.

A common misuse that we are guilty of aswell is the use of dialogs to present content.
Dialogs completely interrupt whatever the user is doing and can sometimes even hide contextual relevant content on the page.
It is best to embed information into regular pages and either use deep links to refer to them or make use of flyovers to present the content in place.

Another important change is the handling of form inputs.
Previously it was required to manually craft the form submit buttons, handle button clicks and implement a proper validation.
The new API provides the “prompt” type which implements all this for you and exposing JavaScript events to validate, submit and cancel the dialog.

Last but not least there have been updates to the visual appearance of dialogs.
The new dialogs mimic the appearance on modern desktop operating systems as well as smartphones by aligning the buttons to the bottom right.
In addition the order of buttons has been changed to always show the primary button on the rightmost position.
These changes were made in an effort to make it easier for users to adopt an already well known control concept and to improve the overall accessibility.

## Migrating to the Dialogs of WoltLab Suite 6.0

The old dialogs are still fully supported and have remained unchanged apart from a visual update to bring them in line with the new dialogs.
We do recommend that you use the new dialog API exclusively for new components and migrate the existing dialogs whenever you see it fit, we’ll continue to support the legacy dialog API for the entire 6.x series at minimum.

## Comparison of the APIs

The legacy API relied on implicit callbacks to initialize dialogs and to handle the entire lifecycle.
The `_dialogSetup()` method was complex, offered subpar auto completition support and generally became very bloated when utilizing the events.

### `source`

The source of a dialog is provided directly through the fluent API of [`dialogFactory()`](components_dialog.md) which provides methods to spawn dialogs using elements, HTML strings or completely empty.

The major change is the removal of the AJAX support as the content source, you should use [`dboAction()`](new-api_ajax.md) instead and then create the dialog.

### `options.onSetup(content: HTMLElement)`

You can now access the content element directly, because everything happens in-place.

```ts
const dialog = dialogFactory().fromHtml("<p>Hello World!</p>").asAlert();

// Do something with `dialog.content` or bind event listeners.

dialog.show("My Title");
```

### `options.onShow(content: HTMLElement)`

There is no equivalent in the new API, because you can simply store a reference to your dialog and access the `.content` property at any time.

### `_dialogSubmit()`

This is the most awkward feature of the legacy dialog API: Poorly documented and cumbersome to use.
Implementing it required a dedicated form submit button and the keyboard interaction required the `data-dialog-submit-on-enter="true"` attribute to be set on all input elements that should submit the form through `Enter`.

The new dialog API takes advantage of the `form[method="dialog"]` functionality which behaves similar to regular forms but will signal the form submit to the surrounding dialog.
As a developer you only need to listen for the `validate` and the `primary` event to implement your logic.

```ts
const dialog = dialogFactory()
  .fromId("myComplexDialogWithFormInputs")
  .asPrompt();

dialog.addEventListener("validate", (event) => {
  // Validate the form inputs in `dialog.content`.

  if (validationHasFailed) {
    event.preventDefault();
  }
});

dialog.addEventListener("primary", () => {
  // The dialog has been successfully validated
  // and was submitted by the user.
  // You can access form inputs through `dialog.content`.
});
```

## Changes to the Template

Both the old and the new API support the use of existing elements to create dialogs.
It is recommended to use `<template>` in this case which will never be rendered and has a well-defined role.

```html
<!-- Previous -->
<div id="myDialog" style="display: none">
  <!-- … -->
</div>

<!-- Use instead -->
<template id="myDialog">
  <!-- … -->
</template>
```

Dialogs have historically been using the same HTML markup that regular pages do, including but not limited to the use of sections.
For dialogs that use only a single container it is recommend to drop the section entirely.

If your dialog contain multiple sections it is recommended to skip the title of the first section.

### `.formSubmit`

Form controls are no longer defined through the template, instead those are implicitly generated by the new dialog API.
Please see the explanation on the four different [dialog types](components_dialog.md) to learn more about form controls.

## Migration by Example

There is no universal pattern that fits every case, because dialogs vary greatly between each other and the required functionality causes the actual implementation to be different.

As an example we have migrated the dialog to create a new box to use the new API. It uses a prompt dialog that automagically adds form controls and fires an event once the user submits the dialog. You can find the commit [3a9210f229f6a2cf5e800c2c4536c9774d02fc86](https://github.com/WoltLab/WCF/commit/3a9210f229f6a2cf5e800c2c4536c9774d02fc86?diff=split) on GitHub.

The changes can be summed up as follows:

1. Use a `<template>` element for the dialog content.
2. Remove the `.formSubmit` section from the HTML.
3. Unwrap the contents by stripping the `.section`.
4. Create and store the reference to the dialog in a property for later re-use.
5. Interact with the dialog’s `.content` property and make use of an event listener to handle the user interaction.

### Creating a Dialog Using an ID

```ts
_dialogSetup() {
  return {
    // …
    id: "myDialog",
  };
}
```

New API

```ts
dialogFactory().fromId("myDialog").withoutControls();
```

### Using `source` to Provide the Dialog HTML

```ts
_dialogSetup() {
  return {
    // …
    source: "<p>Hello World</p>",
  };
}
```

New API

```ts
dialogFactory().fromHtml("<p>Hello World</p>").withoutControls();
```

### Updating the HTML When the Dialog Is Shown

```ts
_dialogSetup() {
  return {
    // …
    options: {
      // …
      onShow: (content) => {
        content.querySelector("p")!.textContent = "Hello World";
      },
    },
  };
}
```

New API

```ts
const dialog = dialogFactory().fromHtml("<p></p>").withoutControls();

// Somewhere later in the code

dialog.content.querySelector("p")!.textContent = "Hello World";

dialog.show("Some Title");
```

### Specifying the Title of a Dialog

The title was previously fixed in the `_dialogSetup()` method and could only be changed on runtime using the `setTitle()` method.

```ts
_dialogSetup() {
  return {
    // …
    options: {
      // …
      title: "Some Title",
    },
  };
}
```

The title is now provided whenever the dialog should be opened, permitting changes in place.

```ts
const dialog = dialogFactory().fromHtml("<p></p>").withoutControls();

// Somewhere later in the code

dialog.show("Some Title");
```
