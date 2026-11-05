# Migrating from WoltLab Suite 6.2 - Third Party Libraries

## CodeMirror

CodeMirror, the code editor used for templates, SCSS and the `SourceCodeFormField`, has been updated from version 5.61.1 to version 6.
CodeMirror 6 is a complete rewrite that, among other things, properly supports editing on mobile devices.

The AMD package `codemirror` in `js/3rdParty/codemirror/` was removed, including its modes, addons and the copies of `codemirror.js` and `codemirror.css` that were deprecated since WoltLab Suite 5.4.
CodeMirror 6 is shipped as the AMD module `@woltlab/codemirror` instead, built from [WoltLab/codemirror](https://github.com/WoltLab/codemirror).
It re-exports `@codemirror/state`, `@codemirror/view`, `@codemirror/language`, `@codemirror/commands`, `@codemirror/search`, `@codemirror/autocomplete`, `@codemirror/lint` and `@lezer/highlight`, along with `basicSetup`, `minimalSetup` and `loadLanguage()`.
`loadLanguage()` lazily loads the language support for a mode name of `SourceCodeFormField::LANGUAGES`.

See [WoltLab/WCF#6686](https://github.com/WoltLab/WCF/issues/6686) for details.

### Creating an Editor

The `shared_codemirror` template still accepts the parameters `codemirrorSelector`, `codemirrorMode` and `editable`.
From TypeScript, use `setupCodeMirror()` of `WoltLabSuite/Core/Component/CodeMirror`:

```ts
import { setupCodeMirror } from "WoltLabSuite/Core/Component/CodeMirror";

const view = setupCodeMirror(textarea, { language: "smartymixed" });
```

The textarea is hidden but stays in the form, every change in the editor is written back to it.
The editor follows the dimensions and visibility of its container on its own, calls to `refresh()` after switching tabs are no longer necessary.

### Accessing an Existing Editor

The CodeMirror 5 instance stored in the `codemirror` property of the textarea was removed.
`getCodeMirror()` returns the `EditorView` of a textarea, `insertText()` replaces the selection or inserts at the cursor position:

```ts
// Before
textarea.codemirror.replaceSelection(text);

// After
import { insertText } from "WoltLabSuite/Core/Component/CodeMirror";

insertText(textarea, text);
```

### Template Events

The template events `javascriptIncludes` and `javascriptInit` of `shared_codemirror` were removed.
Extensions can be added to an existing editor instead:

```ts
import { StateEffect } from "@woltlab/codemirror";
import { getCodeMirror } from "WoltLabSuite/Core/Component/CodeMirror";

getCodeMirror(textarea)?.dispatch({
  effects: StateEffect.appendConfig.of(myExtension),
});
```

### Languages

All values of `SourceCodeFormField::LANGUAGES` remain valid.
Some have no exact counterpart in CodeMirror 6:

| Language | Highlighted As |
|---|---|
| `haml`, `haskell-literate`, `rst`, `slim` | Plain text |
| `handlebars`, `htmlembedded`, `soy` | HTML |
| `django`, `tornado`, `twig` | Jinja |
| `smarty` | Same as `smartymixed`, Smarty on top of HTML |
