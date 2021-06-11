# Migrating from WSC 5.4 - TypeScript and JavaScript

## CodeMirror

CodeMirror, which we use for highlighting custom CSS, for example, has been updated to version 5.61.1 and we now also deliver all supported languages/modes.
To properly support all languages/modes, CodeMirror is now loaded via requireJS, which requires the original structure of the CodeMirror package, i.e. `codemirror.js` being in a `lib` folder.
To preserve backward-compatibility, we also keep copies of `codemirror.js` and `codemirror.css` in version 5.61.1 directly in `js/3rdParty/codemirror`.
These files are, however, considered deprecated and you should migrate to using requireJS (see `codemirror` ACP template).

See [WCF#4277](https://github.com/WoltLab/WCF/pull/4277) for more information.
