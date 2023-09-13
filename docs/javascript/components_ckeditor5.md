# CKEditor 5 - JavaScript API

WoltLab Suite 6.0 ships with a customized version of CKEditor 5 that is enriched by multiple plugins to integrate into the framework.
The editor can be accessed through an abstraction layer and event system to interact with basic functionality and to alter its initialization process.

## The `Ckeditor` API

The `WoltLabSuite/Core/Component/Ckeditor` API offers multiple helper methods to interface with CKEditor and to insert and extract content.
You can get access to the instance using the `getCkeditor()` and `getCkeditorById()` methods once the editor has been initialized.

Please refer to the typings of this component to learn about its API methods.

## The Event System for CKEditor 5

The event system of CKEditor 5 was designed for actions from within the editor but not to interface with the “outside world”.
In order to provide a deeper integration there is `WoltLabSuite/Core/Component/Ckeditor/Event` and its exported function `listenToCkeditor()`.

`listenToCkeditor()` expects the source element of the editor which usually is the `<textarea>` for the content.
There is also `dispatchToCkeditor()` which is considered to be an internal API used from within the editor.

### Lifecycle of CKEditor 5

The initialization of any CKEditor 5 instance is asynchronous and goes through multiple steps to collect the data needed to initialize the editor.

1. `setupFeatures()` is an override to disable the available features of this instance. The list of features becomes immutable after this event.
2. `setupConfiguration()` is called at the end of the configuration phase and allows changes to the `EditorConfig` before it is passed to CKEditor.
3. The `ready()` is fired as soon as the editor has been fully initialized and provides the `Ckeditor` object to access the editor.
4. `collectMetaData()` is used in inline editors to request the injection of additional payloads that should be included in the server request.
5. `reset()` notifies that the editor is being fully reset and reliant components should perform a reset themselves.
6. `destroy()` signals that the editor has been destroyed and is no longer available.

### Custom BBCode Buttons in the Editor Toolbar

Custom buttons in the editor can be injected by pushing them to the toolbar in `setupConfiguration()`.
This is implicitly takes place for buttons that are registered through the BBCode system and are set to appear in the editor.

```ts
listenToCkeditor(element).setupConfiguration(({ configuration }) => {
  configuration.woltlabBbcode.push({
    icon: "fire;false",
    name: "foo",
    label: "Button label for foo",
  });
});
```

Clicks on the buttons are forwarded through the `bbcode()` event.
The only data passed to the callback is the name of the BBCode that was invoked.

The default action of the custom buttons is to insert the BBCode into the editor, including the ability to wrap the selected text in the BBCode tags.
Every event listener registered to this event MUST return a boolean to indicate if it wants to suppress the insertion of the text BBCode, for example to open a dialog or trigger another action.

```ts
listenToCkeditor(element).ready(({ ckeditor }) => {
  listenToCkeditor(element).bbcode(({ bbcode }) => {
    if (bbcode !== "foo") {
      return false;
    }

    // Do something when the button for `foo` was clicked.

    return true;
  });
});
```

### Submit the Editor on Enter

The editor supports the special feature flag `submitOnEnter` that can be enabled through `setupFeatures()`.
Once enabled it changes the behavior of the Enter key to signal that the contents should be submitted to the server.

```ts
listenToCkeditor(element).setupFeatures(({ features }) => {
  features.submitOnEnter = true;
});
```

You can subscribe to the `submitOnEnter()` to be notified when the Enter key was pressed and the editor is not empty.
The contents of the editor is provided through the `html` key.

This mode does not suppress the insertion of hard breaks through Shift + Enter.

```ts
listenToCkeditor(element).submitOnEnter(({ ckeditor, html }) => {
  // Do something with the resulting `html`.
});
```
