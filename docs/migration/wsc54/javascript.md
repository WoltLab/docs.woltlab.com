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

See [WoltLab/WCF#4353](https://github.com/WoltLab/WCF/pull/4353) for more information.


## CodeMirror

CodeMirror, the code editor we use for editing templates and SCSS, for example, has been updated to version 5.61.1 and we now also deliver all supported languages/modes.
To properly support all languages/modes, CodeMirror is now loaded via the AMD module loader, which requires the original structure of the CodeMirror package, i.e. `codemirror.js` being in a `lib` folder.
To preserve backward-compatibility, we also keep copies of `codemirror.js` and `codemirror.css` in version 5.61.1 directly in `js/3rdParty/codemirror`.
These files are, however, considered deprecated and you should migrate to using `require()` (see `codemirror` ACP template).

See [WoltLab/WCF#4277](https://github.com/WoltLab/WCF/pull/4277) for more information.
