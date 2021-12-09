# Migrating from WSC 5.4 - TypeScript and JavaScript

## `Ajax.dboAction()`

We have introduced a new `Promise` based API for the interaction with `wcf\data\DatabaseObjectAction`.
It provides full IDE autocompletion support and transparent error handling, but is designed to be used with `DatabaseObjectAction` only.

See [the documentation for the new API](../../javascript/new-api_ajax.md) and [WoltLab/WCF#4585](https://github.com/WoltLab/WCF/pull/4585) for details.

## `WCF.ColorPicker`

We have replaced the old jQuery-based color picker `WCF.ColorPicker` with a more lightweight replacement `WoltLabSuite/Core/Ui/Color/Picker`, which uses the build-in `input[type=color]` field.
To support transparency, which `input[type=color]` does not, we also added a slider to set the alpha value.
`WCF.ColorPicker` has been adjusted to internally use `WoltLabSuite/Core/Ui/Color/Picker` and it has been deprecated.

Be aware that the new color picker requires the following new phrases to be available in the TypeScript/JavaScript code:

- `wcf.style.colorPicker.alpha`,
- `wcf.style.colorPicker.color`,
- `wcf.style.colorPicker.error.invalidColor`,
- `wcf.style.colorPicker.hexAlpha`,
- `wcf.style.colorPicker.new`.

See [WoltLab/WCF#4353](https://github.com/WoltLab/WCF/pull/4353) for details.

## CodeMirror

The bundled version of CodeMirror was updated and should be loaded using the AMD loader going forward.

See the [third party libraries migration guide](libraries.md#codemirror) for details.

## New User Menu

The legacy implementation `WCF.User.Panel.Abstract` was based on jQuery and has now been retired in favor of a new lightweight implementation that provides a clean interface and improved accessibility.
You are strongly encouraged to migrate your existing implementation to integrate with existing menus.

Please use `WoltLabSuite/Core/Ui/User/Menu/Data/ModerationQueue.ts` as a template for your own implementation, it contains only strictly the code you will need. It makes use of the new `Ajax.dboAction()` (see above) for improved readability and flexibility.

You must update your trigger button to include the `role`, `tabindex` and ARIA attributes! Please take a look at the links in `pageHeaderUser.tpl` to see these four attributes in action.

See [WoltLab/WCF#4603](https://github.com/WoltLab/WCF/pull/4603) for details.