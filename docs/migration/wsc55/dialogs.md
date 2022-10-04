# Migrating from WoltLab Suite 5.5 - Dialogs

## The State of Dialogs in WoltLab Suite 5.5 and earlier

In the past dialogs have been used for all kinds of purposes, for example, to provide more details.
Dialogs make it incredibly easy to add extra information or forms to an existing page without giving much thought: A simple button is all that it takes to show a dialog.

This has lead to an abundance of dialogs that have been used in a lot of places where dialogs are not the right choice, something we are guilty of in a lot of cases.
A lot of research has gone into the accessibility of dialogs and the general recommendations towards their usage and the behavior.

One big issue of dialogs have been their inconsistent appearance in terms of form buttons and their (lack of) keyboard support for input fields.
WoltLab Suite 6.0 provides a completely redesigned API that strives to make the process of creating dialogs much easier and features a consistent keyboard support out of the box.

## Migrating to the Dialogs of WoltLab Suite 6.0

The old dialogs are still fully supported and have remained unchanged apart from a visual update to bring them in line with the new dialogs.
We do recommend that you use the new dialog API exclusively for new components and migrate the existing dialogs whenever you see it fit, we’ll continue to support the legacy dialog API for the entire 6.x series at minimum.

## Comparison of the APIs

The legacy API relied on implicit callbacks to initialize dialogs and to handle the entire lifecycle.
The `_dialogSetup()` method was complex, offered subpar auto completition support and generally became very bloated when utilizing the events.

### `source`

The source of a dialog is provided directly through the fluent API of `dialogFactory()` which provides methods to spawn dialogs using elements, HTML strings or completely empty.

The major change is the removal of the AJAX support as the content source, you should use [`dboAction()`](new-api_ajax.md) instead and then create the dialog.

### `options.onSetup(content: HTMLElement)`

You can now access the content element directly, because everything happens in-place.

```ts
const dialog = dialogFactory()
  .fromHtml('<p>Hello World!</p>')
  .asAlert();

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
  .fromId('myComplexDialogWithFormInputs')
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

## Migration by Example

There is no universal pattern that fits every case, because dialogs vary greatly between each other and the required functionality causes the actual implementation to be different.

As an example we have migrated the dialog to create a new box to use the new API. It uses a prompt dialog that automagically adds form controls and fires an event once the user submits the dialog. You can find the commit [3a9210f229f6a2cf5e800c2c4536c9774d02fc86](https://github.com/WoltLab/WCF/commit/3a9210f229f6a2cf5e800c2c4536c9774d02fc86?diff=split) on GitHub.
