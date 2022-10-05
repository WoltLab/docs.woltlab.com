# Confirmation - JavaScript API

The purpose of confirmation dialogs is to prevent misclicks and to inform the user of potential consequences of their action.
A confirmation dialog should always ask a concise question that includes a reference to the object the action is performed upon.

You can exclude extra information or form elements in confirmation dialogs, but these should be kept as compact as possible.

## Example

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

## When to Use

Over the past few years the term “Confirmation Fatique” has emerged that describes the issue of having too many confirmation dialogs even there is no real need for them.
A confirmation dialog should only be displayed when the action requires further inputs, for example, a soft delete that requires a reason, or when the action is destructive.

## Proper Wording

The confirmation question should hint the severity of the action, in particular whether or not it is destructive.
Destructive actions are those that cannot be undone and either cause a permanent mutation or that cause data loss.
All questions should be phrased in one or two ways depending on the action.

Destructive action:

> Are you sure you want to delete “Example Object”?
> (German) Wollen Sie „Beispiel-Objekt” wirklich löschen?

All other actions:

> Do you want to move “Example Object” to the trash bin?
> (German) Möchten Sie „Beispiel-Objekt“ in den Papierkorb verschieben?

## Available Presets

WoltLab Suite 6.0 currently ships with three presets for common confirmation dialogs.
All three presets require the title of the related object as part of the question asked to the user.

### Soft Delete

Soft deleting objects with an optional input field for a reason:

```ts
const askForReason = true;
const { result, reason } = await confirmationFactory().softDelete(
  theObjectName,
  askForReason
);
if (result) {
  console.log(
    "The user has requested a soft delete, the following reason was provided:",
    reason
  );
}
```

The `reason` will always be a string, but with a length of zero if the `result` is `false` or if no reason was requested.
You can simply omit the value if you do not use the reason.

```ts
const askForReason = false;
const { result } = await confirmationFactory().softDelete(
  theObjectName,
  askForReason
);
if (result) {
  console.log("The user has requested a soft delete.");
}
```

### Restore

Restore a previously soft deleted object:

```ts
const result = await confirmationFactory().restore(theObjectName);
if (result) {
  console.log("The user has requested to restore the object.");
}
```

### Delete

Permanently delete an object, will inform the user that the action cannot be undone:

```ts
const result = await confirmationFactory().delete(theObjectName);
if (result) {
  console.log("The user has requested to delete the object.");
}
```
