# Migrating from WoltLab Suite 5.5 - Dialogs

## Purpose and Past Usage

Modal dialogs are a powerful tool to draw the viewer‘s attention to an important message, question or form.
Dialogs naturally interrupt the workflow and prevent the navigation to other sections by making other elements on the page inert.

In the past dialogs have been used for all kinds of purposes, for example, to provide more details.
Dialogs make it incredibly easy to add extra information or forms to an existing page without giving much thought: A simple button is all that it takes to show a dialog.

## Types of Dialogs

### Dialogs Without Controls

Dialogs may contain just an explanation or extra information that should be presented to the viewer without requiring any further interaction.

```ts
const dialog = dialogFactory()
  .fromHtml("<p>Hello World</p>")
  .withoutControls();
dialog.show("Greetings from my dialog");
```

#### When to Use

The short answer is: Don‘t.

Dialogs without controls are an anti-pattern because they only contain content that does not require the modal appearance of a dialog.
More often than not dialogs are used for this kind of content because they are easy to use without thinking about better ways to present the content.

If possible these dialogs should be avoided and the content is presented in a more suitable way, for example, as a flyout or by showing content on an existing or new page.

### Alerts

Alerts are designed to inform the user of something important that requires no further action by the user.
Typical examples for alerts are error messages or warnings.

An alert will only provide a single button to acknowledge the dialog and must not contain interactive content.

```ts
const dialog = dialogFactory()
  .fromHtml("<p>ERROR: Something went wrong!</p>")
  .asAlert();
dialog.show("Server Error")
```

#### When to Use

Alerts are a special type of dialog that use the `role="alert"` attribute to signal its importance to assistive tools.
Use alerts sparingly when there is no other way to communicate that something did not work as expected.

Alerts should not be used for cases where you expect an error to happen.
For example, a form control that expectes an input to fall within a restricted range should show an inline error message instead of raising an alert.

### Confirmation

The purpose of confirmation dialogs is to prevent misclicks and to inform the user of potential consequences of their action.
A confirmation dialog should always ask a concise question that includes a reference to the object the action is performed upon.

You can exclude extra information or form elements in confirmation dialogs, but these should be kept as compact as possible.

```ts
const result = await confirmationFactory()
  .custom("Do you want a cookie?")
  .withoutMessage();
if (result) {
    // User has confirmed the dialog.
}
```

Confirmation dialogs are a special type that use the `role="alertdialog"` attribute and will always include a cancel button.
The dialog itself will be limited to a width of 500px, the title can wrap into multiple lines and there will be no „X“ button to close the dialog.

#### When to Use

Over the past few years the term “Confirmation Fatique” has emerged that describes the issue of having too many confirmation dialogs even there is no real need for them.
A confirmation dialog should only be displayed when the action requires further inputs, for example, a soft delete that requires a reason, or when the action is destructive.

#### Proper Wording

The confirmation question should hint the severity of the action, in particular whether or not it is destructive.
Destructive actions are those that cannot be undone and either cause a permanent mutation or that cause data loss.
All questions should be phrased in one or two ways depending on the action.

Destructive action:
> Are you sure you want to […]?

All other actions:
> Do you want to […]

### Available Presets

WoltLab Suite 6.0 currently ships with three presets for common actions.
All three presets require the title of the related object as part of the question asked to the user.

Soft deleting objects with an optional input field for a reason:

```ts
const askForReason = true;
const { result, reason } = await confirmationFactory()
  .softDelete(theObjectName, askForReason);
if (result) {
    // …
}
```

Restore a previously soft deleted object:

```ts
const result = await confirmationFactory()
  .restore(theObjectName);
if (result) {
    // …
}
```

Permanently delete an object, will inform the user that the action cannot be undone:

```ts
const result = await confirmationFactory()
  .delete(theObjectName);
if (result) {
    // …
}
```

## Prompts

TODO
