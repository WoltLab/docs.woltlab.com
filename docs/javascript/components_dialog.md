# Dialogs - JavaScript API

Modal dialogs are a powerful tool to draw the viewer‘s attention to an important message, question or form.
Dialogs naturally interrupt the workflow and prevent the navigation to other sections by making other elements on the page inert.

WoltLab Suite 6.0 ships with four different types of dialogs.

## Dialogs Without Controls

Dialogs may contain just an explanation or extra information that should be presented to the viewer without requiring any further interaction.

```ts
const dialog = dialogFactory()
  .fromHtml("<p>Hello World</p>")
  .withoutControls();
dialog.show("Greetings from my dialog");
```

### When to Use

The short answer is: Don‘t.

Dialogs without controls are an anti-pattern because they only contain content that does not require the modal appearance of a dialog.
More often than not dialogs are used for this kind of content because they are easy to use without thinking about better ways to present the content.

If possible these dialogs should be avoided and the content is presented in a more suitable way, for example, as a flyout or by showing content on an existing or new page.

## Alerts

Alerts are designed to inform the user of something important that requires no further action by the user.
Typical examples for alerts are error messages or warnings.

An alert will only provide a single button to acknowledge the dialog and must not contain interactive content.

```ts
const dialog = dialogFactory()
  .fromHtml("<p>ERROR: Something went wrong!</p>")
  .asAlert();
dialog.show("Server Error")
```

### When to Use

Alerts are a special type of dialog that use the `role="alert"` attribute to signal its importance to assistive tools.
Use alerts sparingly when there is no other way to communicate that something did not work as expected.

Alerts should not be used for cases where you expect an error to happen.
For example, a form control that expectes an input to fall within a restricted range should show an inline error message instead of raising an alert.

## Confirmation

Confirmation dialogs are supported through a separate factory function that provides a set of presets as well as a generic API. Please see the separate documentation for [confirmation dialogs](components_confirmation.md) to learn more.

## Prompts

TODO

## Interacting with dialogs

TODO
