# Writing a Module - JavaScript API

## Introduction

The new JavaScript-API was introduced with WoltLab Suite 3.0 and was a major
change in all regards. The previously used API heavily relied on larger JavaScript
files that contained a lot of different components with hidden dependencies
and suffered from extensive jQuery usage for historic reasons.

Eventually a new API was designed that solves the issues with the legacy API
by following a few basic principles:
 1. Vanilla ES5-JavaScript.  
    It allows us to achieve the best performance across all platforms, there is
    simply no reason to use jQuery today and the performance penalty on mobile
    devices is a real issue.
 2. Strict usage of modules.  
    Each component is placed in an own file and all dependencies are explicitly
    declared and injected at the top.Eventually we settled with AMD-style modules
    using require.js which offers both lazy loading and "ahead of time"-compilatio
    with `r.js`.
 3. No jQuery-based components on page init.  
    Nothing is more annoying than loading a page and then wait for JavaScript to
    modify the page before it becomes usable, forcing the user to sit and wait.
    Heavily optimized vanilla JavaScript components offered the speed we wanted.
 4. Limited backwards-compatibility.  
    The new API should make it easy to update existing components by providing
    similar interfaces, while still allowing legacy code to run side-by-side for
    best compatibility and to avoid rewritting everything from the start.

## Defining a Module

The default location for modules is `js/` in the Core's app dir,
but every app and plugin can register their own lookup path by providing the path
using a [template-listener](../package/pip/template-listener.md) on `requirePaths@headIncludeJavaScript`.

For this example we'll assume the file is placed at `js/WoltLabSuite/Core/Ui/Foo.js`,
the module name is therefore `WoltLabSuite/Core/Ui/Foo`, it is automatically
derived from the file path and name.

For further instructions on how to define and require modules head over to the [RequireJS API](http://requirejs.org/docs/api.html).

```js
define(["Ajax", "WoltLabSuite/Core/Ui/Bar"], function(Ajax, UiBar) {
  "use strict";

  function Foo() { this.init(); }
  Foo.prototype = {
    init: function() {
      elBySel(".myButton").addEventListener(WCF_CLICK_EVENT, this._click.bind(this));
    },

    _click: function(event) {
      event.preventDefault();

      if (UiBar.isSnafucated()) {
        Ajax.api(this);
      }
    },

    _ajaxSuccess: function(data) {
      console.log("Received response", data);
    },

    _ajaxSetup: function() {
      return {
        data: {
          actionName: "makeSnafucated",
          className: "wcf\\data\\foo\\FooAction"
        }
      };
    }
  }
  
  return Foo;
});
```

## Loading a Module

Modules can then be loaded through their derived name:

```html
<script data-relocate="true">
  require(["WoltLabSuite/Core/Ui/Foo"], function(UiFoo) {
    new UiFoo();
  });
</script>
```

### Module Aliases

Some common modules have short-hand aliases that can be used to include them
without writing out their full name. You can still use their original path, but
it is strongly recommended to use the aliases for consistency.

| Alias | Full Path |
|---|---|
| [Ajax](new-api_ajax.md) | WoltLabSuite/Core/Ajax |
| AjaxJsonp | WoltLabSuite/Core/Ajax/Jsonp |
| AjaxRequest | WoltLabSuite/Core/Ajax/Request |
| CallbackList | WoltLabSuite/Core/CallbackList |
| ColorUtil | WoltLabSuite/Core/ColorUtil |
| [Core](new-api_core.md) | WoltLabSuite/Core/Core |
| DateUtil | WoltLabSuite/Core/Date/Util |
| Devtools | WoltLabSuite/Core/Devtools |
| [Dictionary](new-api_data-structures.md) | WoltLabSuite/Core/Dictionary |
| [Dom/ChangeListener](new-api_dom.md) | WoltLabSuite/Core/Dom/Change/Listener |
| Dom/Traverse | WoltLabSuite/Core/Dom/Traverse |
| [Dom/Util](new-api_dom.md) | WoltLabSuite/Core/Dom/Util |
| [Environment](new-api_browser.md) | WoltLabSuite/Core/Environment |
| [EventHandler](new-api_events.md) | WoltLabSuite/Core/Event/Handler |
| [EventKey](new-api_events.md) | WoltLabSuite/Core/Event/Key |
| [Language](new-api_core.md) | WoltLabSuite/Core/Language |
| [List](new-api_data-structures.md) | WoltLabSuite/Core/List |
| [ObjectMap](new-api_data-structures.md) | WoltLabSuite/Core/ObjectMap |
| Permission | WoltLabSuite/Core/Permission |
| [StringUtil](new-api_core.md) | WoltLabSuite/Core/StringUtil |
| [Ui/Alignment](new-api_ui.md) | WoltLabSuite/Core/Ui/Alignment |
| [Ui/CloseOverlay](new-api_ui.md) | WoltLabSuite/Core/Ui/CloseOverlay |
| [Ui/Confirmation](new-api_ui.md) | WoltLabSuite/Core/Ui/Confirmation |
| [Ui/Dialog](new-api_dialogs.md) | WoltLabSuite/Core/Ui/Dialog |
| [Ui/Notification](new-api_ui.md) | WoltLabSuite/Core/Ui/Notification |
| Ui/ReusableDropdown | WoltLabSuite/Core/Ui/Dropdown/Reusable |
| [Ui/Screen](new-api_browser.md) | WoltLabSuite/Core/Ui/Screen |
| Ui/Scroll | WoltLabSuite/Core/Ui/Scroll |
| Ui/SimpleDropdown | WoltLabSuite/Core/Ui/Dropdown/Simple |
| Ui/TabMenu | WoltLabSuite/Core/Ui/TabMenu |
| Upload | WoltLabSuite/Core/Upload |
| User | WoltLabSuite/Core/User |
