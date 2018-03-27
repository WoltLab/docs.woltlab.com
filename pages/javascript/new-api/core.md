---
title: Core Modules and Functions - JavaScript API
sidebar: sidebar
permalink: javascript_new-api_core.html
folder: javascript
---

A brief overview of common methods that may be useful when writing any module.

## `Core`

### `clone(object: Object): Object`

Creates a deep-clone of the provided object by value, removing any references on
the original element, including arrays. However, this does not clone references
to non-plain objects, these instances will be copied by reference.

```js
require(["Core"], function(Core) {
  var obj1 = { a: 1 };
  var obj2 = Core.clone(obj1);

  console.log(obj1 === obj2); // output: false
  console.log(obj2.hasOwnProperty("a") && obj2.a === 1); // output: true
});
```

### `extend(base: Object, ...merge: Object[]): Object`

Accepts an infinite amount of plain objects as parameters, values will be copied
from the 2nd...nth object into the first object. The first parameter will be
cloned and the resulting object is returned.

```js
require(["Core"], function(Core) {
  var obj1 = { a: 2 };
  var obj2 = { a: 1, b: 2 };
  var obj = Core.extend({
    b: 1
  }, obj1, obj2);

  console.log(obj.b === 2); // output: true
  console.log(obj.hasOwnProperty("a") && obj.a === 2); // output: false
});
```

### `inherit(base: Object, target: Object, merge?: Object)`

Derives the second object's prototype from the first object, afterwards the
derived class will pass the `instanceof` check against the original class.

```js
// App.js
window.App = {};
App.Foo = Class.extend({
  bar: function() {}
});
App.Baz = App.Foo.extend({
  makeSnafucated: function() {}
});

// --- NEW API ---

// App/Foo.js
define([], function() {
  "use strict";

  function Foo() {};
  Foo.prototype = {
    bar: function() {}
  };

  return Foo;
});

// App/Baz.js
define(["Core", "./Foo"], function(Core, Foo) {
  "use strict";

  function Baz() {};
  Core.inherit(Baz, Foo, {
    makeSnafucated: function() {}
  });

  return Baz;
});
```

### `isPlainObject(object: Object): boolean`

Verifies if an object is a plain JavaScript object and not an object instance.

```js
require(["Core"], function(Core) {
  function Foo() {}
  Foo.prototype = {
    hello: "world";
  };

  var obj1 = { hello: "world" };
  var obj2 = new Foo();

  console.log(Core.isPlainObject(obj1)); // output: true
  console.log(obj1.hello === obj2.hello); // output: true
  console.log(Core.isPlainObject(obj2)); // output: false
});
```

### `triggerEvent(element: Element, eventName: string)`

Creates and dispatches a synthetic JavaScript event on an element.

```js
require(["Core"], function(Core) {
  var element = elBySel(".some-element");
  Core.triggerEvent(element, "click");
});
```

## `Language`

### `add(key: string, value: string)`

Registers a new phrase.

```html
<script data-relocate="true">
  require(["Language"], function(Language) {
    Language.add("app.foo.bar", "{lang}app.foo.bar{/lang}");
  });
</script>
```

### `addObject(object: Object)`

Registers a list of phrases using a plain object.

```html
<script data-relocate="true">
  require(["Language"], function(Language) {
    Language.addObject({
      "app.foo.bar": "{lang}app.foo.bar{/lang}"
    });
  });
</script>
```

### `get(key: string, parameters?: Object): string`

Retrieves a phrase by its key, optionally supporting basic template scripting
with dynamic variables passed using the `parameters` object.

```js
require(["Language"], function(Language) {
  var title = Language.get("app.foo.title");
  var content = Language.get("app.foo.content", {
    some: "value"
  });
});
```

## `StringUtil`

### `escapeHTML(str: string): string`

Escapes special HTML characters by converting them into an HTML entity.

| Character | Replacement |
|---|---|
| `&` | `&amp;` |
| `"` | `&quot;` |
| `<` | `&lt;` |
| `>` | `&gt;` |

### `escapeRegExp(str: string): string`

Escapes a list of characters that have a special meaning in regular expressions
and could alter the behavior when embedded into regular expressions.

### `lcfirst(str: string): string`

Makes a string's first character lowercase.

### `ucfirst(str: string): string`

Makes a string's first character uppercase.

### `unescapeHTML(str: string): string`

Converts some HTML entities into their original character. This is the reverse
function of `escapeHTML()`.

{% include links.html %}
