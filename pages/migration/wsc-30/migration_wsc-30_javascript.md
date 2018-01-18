---
title: Migrating from WSC 3.0 - JavaScript
sidebar: sidebar
permalink: migration_wsc-30_javascript.html
folder: migration/wsc-30
---

## Accelerated Guest View / Tiny Builds

The new tiny builds are highly optimized variants of existing JavaScript files and modules, aiming for significant performance improvements for guests and search engines alike. This is accomplished by heavily restricting page interaction to read-only actions whenever possible, which in return removes the need to provide certain JavaScript modules in general.

For example, disallowing guests to write any formatted messages will in return remove the need to provide the WYSIWYG editor at all. But it doesn't stop there, there are a lot of other modules that provide additional features for the editor, and by excluding the editor, we can also exclude these modules too.

Long story short, the tiny mode guarantees that certain actions will never be carried out by guests or search engines, therefore some modules are not going to be needed by them ever.

### Code Templates for Tiny Builds

The following examples assume that you use the virtual constant `COMPILER_TARGET_DEFAULT` as a switch for the optimized code path. This is also the constant used by the official [build scripts for JavaScript files](https://github.com/WoltLab/WCF/tree/master/extra).

We recommend that you provide a mock implementation for existing code to ensure 3rd party compatibility. It is enough to provide a bare object or class that exposes the original properties using the same primitive data types. This is intended to provide a soft-fail for implementations that are not aware of the tiny mode yet, but is not required for classes that did not exist until now.

#### Legacy JavaScript

```js
if (COMPILER_TARGET_DEFAULT) {
  WCF.Example.Foo = {
    makeSnafucated: function() {
      return "Hello World";
    }
  };

  WCF.Example.Bar = Class.extend({
    foobar: "baz",

    foo: function($bar) {
      return $bar + this.foobar;
    }
  });
}
else {
  WCF.Example.Foo = {
    makeSnafucated: function() {}
  };

  WCF.Example.Bar = Class.extend({
    foobar: "",
    foo: function() {}
  });
}
```

#### require.js Modules

```js
define(["some", "fancy", "dependencies"], function(Some, Fancy, Dependencies) {
  "use strict";

  if (!COMPILER_TARGET_DEFAULT) {
    var Fake = function() {};
    Fake.prototype = {
      init: function() {},
      makeSnafucated: function() {}
    };
    return Fake;
  }

  function MyAwesomeClass(niceArgument) { this.init(niceArgument); }
  MyAwesomeClass.prototype = {
    init: function(niceArgument) {
      if (niceArgument) {
        this.makeSnafucated();
      }
    },

    makeSnafucated: function() {
      console.log("Hello World");
    }
  }

  return MyAwesomeClass;
});
```

### Including tinified builds through `{js}`

The `{js}` template-plugin has been updated to include support for tiny builds controlled through the optional flag `hasTiny=true`:

```
{js application='wcf' file='WCF.Example' hasTiny=true}
```

This line generates a different output depending on the debug mode and the user login-state.

## Real Error Messages for AJAX Responses

The `errorMessage` property in the returned response object for failed AJAX requests contained an exception-specific but still highly generic error message. This issue has been around for quite a long time and countless of implementations are relying on this false behavior, eventually forcing us to leave the value unchanged.

This problem is solved by adding the new property `realErrorMessage` that exposes the message exactly as it was provided and now matches the value that would be displayed to users in traditional forms.

### Example Code

```js
define(['Ajax'], function(Ajax) {
  return {
    // ...
    _ajaxFailure: function(responseData, responseText, xhr, requestData) {
      console.log(responseData.realErrorMessage);
    }
    // ...
  };
});
```

## Simplified Form Submit in Dialogs

Forms embedded in dialogs often do not contain the HTML `<form>`-element and instead rely on JavaScript click- and key-handlers to emulate a `<form>`-like submit behavior. This has spawned a great amount of nearly identical implementations that all aim to handle the form submit through the `Enter`-key, still leaving some dialogs behind.

WoltLab Suite 3.1 offers automatic form submit that is enabled through a set of specific conditions and data attributes:

 1. There must be a submit button that matches the selector `.formSubmit > input[type="submit"], .formSubmit > button[data-type="submit"]`.
 2. The dialog object provided to `UiDialog.open()` implements the method `_dialogSubmit()`.
 3. Input fields require the attribute `data-dialog-submit-on-enter="true"` to be set, the `type` must be one of `number`, `password`, `search`, `tel`, `text` or `url`.

Clicking on the submit button or pressing the `Enter`-key in any watched input field will start the submit process. This is done automatically and does not require a manual interaction in your code, therefore you should not bind any click listeners on the submit button yourself.

Any input field with the `required` attribute set will be validated to contain a non-empty string after processing the value with `String.prototype.trim()`. An empty field will abort the submit process and display a visible error message next to the offending field.

## Helper Function for Inline Error Messages

Displaying inline error messages on-the-fly required quite a few DOM operations that were quite simple but also super repetitive and thus error-prone when incorrectly copied over. The global helper function `elInnerError()` was added to provide a simple and consistent behavior of inline error messages.

You can display an error message by invoking `elInnerError(elementRef, "Your Error Message")`, it will insert a new `<small class="innerError">` and sets the given message. If there is already an inner error present, then the message will be replaced instead.

Hiding messages is done by setting the 2nd parameter to `false` or an empty string:

 * `elInnerError(elementRef, false)`
 * `elInnerError(elementRef, '')`

The special values `null` and `undefined` are supported too, but their usage is discouraged, because they make it harder to understand the intention by reading the code:

 * `elInnerError(elementRef, null)`
 * `elInnerError(elementRef)`

### Example Code

```js
require(['Language'], function(Language)) {
  var input = elBySel('input[type="text"]');
  if (input.value.trim() === '') {
    // displays a new inline error or replaces the message if there is one already
    elInnerError(input, Language.get('wcf.global.form.error.empty'));
  }
  else {
    // removes the inline error if it exists
    elInnerError(input, false);
  }

  // the above condition is equivalent to this:
  elInnerError(input, (input.value.trim() === '' ? Language.get('wcf.global.form.error.empty') : false));
}
```

{% include links.html %}
