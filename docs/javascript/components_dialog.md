# Dialogs - JavaScript API

Modal dialogs are a powerful tool to draw the viewer’s attention to an important message, question or form.
Dialogs naturally interrupt the workflow and prevent the navigation to other sections by making other elements on the page inert.

WoltLab Suite 6.0 ships with four different types of dialogs.

## Quickstart

There are four different types of dialogs that each fulfill their own specialized role and that provide built-in features to make the development much easier.
Please see the following list to make a quick decision of what kind of dialog you need.

* Is this some kind of error message? Use an alert dialog.
* Are you asking the user to confirm an action? Use a [confirmation dialog](components_confirmation.md).
* Does the dialog contain form inputs that the user must fill in? Use a prompt dialog.
* Do you want to present information to the user without requiring any action? Use a dialog without controls.

## Dialogs Without Controls

Dialogs may contain just an explanation or extra information that should be presented to the viewer without requiring any further interaction.
The dialog can be closed via the “X” button or by clicking the modal backdrop.

```ts
const dialog = dialogFactory().fromHtml("<p>Hello World</p>").withoutControls();
dialog.show("Greetings from my dialog");
```

### When to Use

The short answer is: Don’t.

Dialogs without controls are an anti-pattern because they only contain content that does not require the modal appearance of a dialog.
More often than not dialogs are used for this kind of content because they are easy to use without thinking about better ways to present the content.

If possible these dialogs should be avoided and the content is presented in a more suitable way, for example, as a flyout or by showing content on an existing or new page.

## Alerts

Alerts are designed to inform the user of something important that requires no further action by the user.
Typical examples for alerts are error messages or warnings.

An alert will only provide a single button to acknowledge the dialog and must not contain interactive content.
The dialog itself will be limited to a width of 500px, the title can wrap into multiple lines and there will be no “X” button to close the dialog.

```ts
const dialog = dialogFactory()
  .fromHtml("<p>ERROR: Something went wrong!</p>")
  .asAlert();
dialog.show("Server Error");
```

You can customize the label of the primary button to better explain what will happen next.
This can be useful for alerts that will have a side-effect when closing the dialog, such as redirect to a different page.

```ts
const dialog = dialogFactory()
  .fromHtml("<p>Something went wrong, we cannot find your shopping cart.</p>")
  .asAlert({
    primary: "Back to the Store Page",
  });

dialog.addEventListener("primary", () => {
  window.location.href = "https://example.com/shop/";
});

dialog.show("The shopping cart is missing");
```

The `primary` event is triggered both by clicking on the primary button and by clicks on the modal backdrop.

### When to Use

Alerts are a special type of dialog that use the `role="alert"` attribute to signal its importance to assistive tools.
Use alerts sparingly when there is no other way to communicate that something did not work as expected.

Alerts should not be used for cases where you expect an error to happen.
For example, a form control that expectes an input to fall within a restricted range should show an inline error message instead of raising an alert.

## Confirmation

Confirmation dialogs are supported through a separate factory function that provides a set of presets as well as a generic API. Please see the separate documentation for [confirmation dialogs](components_confirmation.md) to learn more.

## Prompts

The most common type of dialogs are prompts that are similar to confirmation dialogs, but without the restrictions and with a regular title.
These dialogs can be used universally and provide a submit and cancel button by default.

In addition they offer an “extra” button that is placed to the left of the default buttons are can be used to offer a single additional action.
A possible use case for an “extra” button would be a dialog that includes an instance of the WYSIWYG editor, the extra button could be used to trigger a message preview.

### Code Example

```html
<button id="showMyDialog">Show the dialog</button>

<template id="myDialog">
  <dl>
    <dt>
      <label for="myInput">Title</label>
    </dt>
    <dd>
      <input type="text" name="myInput" id="myInput" value="" required />
    </dd>
  </dl>
</template>
```

```ts
document.getElementById("showMyDialog")!.addEventListener("click", () => {
  const dialog = dialogFactory().fromId("myDialog").asPrompt();

  dialog.addEventListener("primary", () => {
    const myInput = document.getElementById("myInput");

    console.log("Provided title:", myInput.value.trim());
  });
});
```

### Custom Buttons

The `asPrompt()` call permits some level of customization of the form control buttons.

#### Customizing the Primary Button

The `primary` option is used to change the default label of the primary button.

```ts
dialogFactory()
  .fromId("myDialog")
  .asPrompt({
    primary: Language.get("wcf.dialog.button.primary"),
  });
```

### Adding an Extra Button

The extra button has no default label, enabling it requires you to provide a readable name.

```ts
const dialog = dialogFactory()
  .fromId("myDialog")
  .asPrompt({
    extra: Language.get("my.extra.button.name"),
  });

dialog.addEventListener("extra", () => {
  // The extra button does nothing on its own. If you want
  // to close the button after performing an action you’ll
  // need to call `dialog.close()` yourself.
});
```

## Interacting with dialogs

Dialogs are represented by the `<woltlab-core-dialog>` element that exposes a set of properties and methods to interact with it.

### Opening and Closing Dialogs

You can open a dialog through the `.show()` method that expects the title of the dialog as the only argument.
Check the `.open` property to determine if the dialog is currently open.

Programmatically closing a dialog is possibly through `.close()`.

### Accessing the Content

All contents of a dialog exists within a child element that can be accessed through the `content` property.

```ts
// Add some text to the dialog.
const p = document.createElement("p");
p.textContent = "Hello World";
dialog.content.append(p);

// Find a text input inside the dialog.
const input = dialog.content.querySelector('input[type="text"]');
```

### Managing an Instance of a Dialog

The old API for dialogs implicitly kept track of the instance by binding it to the `this` parameter as seen in calls like `UiDialog.open(this);`.
The new implementation requires to you to keep track of the dialog on your own.

```ts
class MyComponent {
  #dialog?: WoltlabCoreDialogElement;

  constructor() {
    const button = document.querySelector(".myButton") as HTMLButtonElement;
    button.addEventListener("click", () => {
      this.#showGreeting(button.dataset.name);
    });
  }

  #showGreeting(name: string | undefined): void {
    const dialog = this.#getDialog();

    const p = dialog.content.querySelector("p")!;
    if (name === undefined) {
      p.textContent = "Hello World";
    } else {
      p.textContent = `Hello ${name}`;
    }

    dialog.show("Greetings!");
  }

  #getDialog(): WoltlabCoreDialogElement {
    if (this.#dialog === undefined) {
      this.#dialog = dialogFactory()
        .fromHtml("<p>Hello from MyComponent</p>")
        .withoutControls();
    }

    return this.#dialog;
  }
}
```

### Event Access

You can bind event listeners to specialized events to get notified of events and to modify its behavior.

#### `afterClose`

_This event cannot be canceled._

Fires when the dialog has closed.

```ts
dialog.addEventListener("afterClose", () => {
  // Dialog was closed.
});
```

#### `close`

Fires when the dialog is about to close.

```ts
dialog.addEventListener("close", (event) => {
  if (someCondition) {
    event.preventDefault();
  }
});
```

#### `cancel`

Fires only when there is a “Cancel” button and the user has either pressed that button or clicked on the modal backdrop.
The dialog will close if the event is not canceled.

```ts
dialog.addEventListener("cancel", (event) => {
  if (someCondition) {
    event.preventDefault();
  }
});
```

#### `extra`

_This event cannot be canceled._

Fires when an extra button is present and the button was clicked by the user.
This event does nothing on its own and is supported for dialogs of type “Prompt” only.

```ts
dialog.addEventListener("extra", () => {
  // The extra button was clicked.
});
```

#### `primary`

_This event cannot be canceled._

Fires only when there is a primary action button and the user has either pressed that button or submitted the form through keyboard controls.

```ts
dialog.addEventListener("primary", () => {
  // The primary action button was clicked or the
  // form was submitted through keyboard controls.
  //
  // The `validate` event has completed successfully.
});
```

#### `validate`

Fires only when there is a form and the user has pressed the primary action button or submitted the form through keyboard controls.
Canceling this event is interpreted as a form validation failure.

```ts
const input = document.createElement("input");
dialog.content.append(input);

dialog.addEventListener("validate", (event) => {
  if (input.value.trim() === "") {
    event.preventDefault();

    // Display an inline error message.
  }
});
```
