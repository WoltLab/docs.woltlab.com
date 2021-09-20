# Migrating from WSC 5.4 - Third Party Libraries

## scssphp

scssphp was updated from version 1.4 to 1.8.

If you interact with scssphp only by deploying `.scss` files, then you should not experience any breaking changes, except when the improved SCSS compatibility interprets your SCSS code differently.

If you happen to directly use scssphp in your PHP code, you should be aware that scssphp deprecated the use of the `compile()` method, non-UTF-8 processing and also adjusted the handling of pure PHP values for variable handling.

Refer to [WoltLab/WCF#4345](https://github.com/WoltLab/WCF/pull/4345) and the [scssphp releases](https://github.com/scssphp/scssphp/releases) for details.

## CodeMirror

CodeMirror, the code editor we use for editing templates and SCSS, for example, has been updated to version 5.61.1 and we now also deliver all supported languages/modes.
To properly support all languages/modes, CodeMirror is now loaded via the AMD module loader, which requires the original structure of the CodeMirror package, i.e. `codemirror.js` being in a `lib` folder.
To preserve backward-compatibility, we also keep copies of `codemirror.js` and `codemirror.css` in version 5.61.1 directly in `js/3rdParty/codemirror`.
These files are, however, considered deprecated and you should migrate to using `require()` (see `codemirror` ACP template).

See [WoltLab/WCF#4277](https://github.com/WoltLab/WCF/pull/4277) for details.

## Zend/ProgressBar

The old bundled version of Zend/ProgressBar was replaced by a current version of laminas-progressbar.

Due to laminas-zendframework-bridge this update is a drop-in replacement.
Existing code should continue to work as-is.

It is recommended to cleanly migrate to laminas-progressbar to allow for a future removal of the bridge.
Updating the `use` imports should be sufficient to switch to the laminas-progressbar.

See [WoltLab/WCF#4460](https://github.com/WoltLab/WCF/pull/4460) for details.
