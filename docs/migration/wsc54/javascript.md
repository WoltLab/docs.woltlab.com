# Migrating from WSC 5.4 - TypeScript and JavaScript

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
