---
title: Legacy JavaScript API
sidebar: sidebar
permalink: javascript_legacy-api.html
folder: javascript
---

## Introduction

The legacy JavaScript API is the original code that was part of the 2.x series
of WoltLab Suite, formerly known as WoltLab Community Framework. It has been
superseded for the most part by the [ES5/AMD-modules API][javascript_new-api_writing-a-module]
introduced with WoltLab Suite 3.0.

Some parts still exist to this day for backwards-compatibility and because some
less important components have not been rewritten yet. The old API is still
supported, but marked as deprecated and will continue to be replaced parts by
part in future releases, up until their entire removal, including jQuery support.

This guide does not provide any explanation on the usage of those legacy components,
but instead serves as a cheat sheet to convert code to use the new API.

## Classes

### Singletons

Singleton instances are designed to provide a unique "instance" of an object
regardless of when its first instance was created. Due to the lack of a `class`
construct in ES5, they are represented by mere objects that act as an instance.

```js
// App.js
window.App = {};
App.Foo = {
  bar: function() {}
};

// --- NEW API ---

// App/Foo.js
define([], function() {
  "use strict";

  return {
    bar: function() {}
  };
});
```

### Regular Classes

```js
// App.js
window.App = {};
App.Foo = Class.extend({
  bar: function() {}
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
```

#### Inheritance

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

## Ajax Requests

```js
// App.js
App.Foo = Class.extend({
  _proxy: null,

  init: function() {
    this._proxy = new WCF.Action.Proxy({
      success: $.proxy(this._success, this)
    });
  },

  bar: function() {
    this._proxy.setOption("data", {
      actionName: "baz",
      className: "app\\foo\\FooAction",
      objectIDs: [1, 2, 3],
      parameters: {
        foo: "bar",
        baz: true
      }
    });
    this._proxy.sendRequest();
  },

  _success: function(data) {
    // ajax request result
  }
});

// --- NEW API ---

// App/Foo.js
define(["Ajax"], function(Ajax) {
  "use strict";

  function Foo() {}
  Foo.prototype = {
    bar: function() {
      Ajax.api(this, {
        objectIDs: [1, 2, 3],
        parameters: {
          foo: "bar",
          baz: true
        }
      });
    },

    // magic method!
    _ajaxSuccess: function(data) {
      // ajax request result
    },

    // magic method!
    _ajaxSetup: function() {
      return {
        actionName: "baz",
        className: "app\\foo\\FooAction"
      }
    }
  }

  return Foo;
});
```

## Phrases

```html
<script data-relocate="true">
$(function() {
  WCF.Language.addObject({
    "app.foo.bar": "{lang}app.foo.bar{/lang}"
  });

  console.log(WCF.Language.get("app.foo.bar"));
});
</script>

<!-- NEW API -->

<script data-relocate="true">
require(["Language"], function(Language) {
  Language.addObject({
    "app.foo.bar": "{lang}app.foo.bar{/lang}"
  });

  console.log(Language.get("app.foo.bar"));
});
</script>
```

## Event-Listener

```html
<script data-relocate="true">
$(function() {
  WCF.System.Event.addListener("app.foo.bar", "makeSnafucated", function(data) {
    console.log("Event was invoked.");
  });

  WCF.System.Event.fireEvent("app.foo.bar", "makeSnafucated", { some: "data" });
});
</script>

<!-- NEW API -->

<script data-relocate="true">
require(["EventHandler"], function(EventHandler) {
  EventHandler.add("app.foo.bar", "makeSnafucated", function(data) {
    console.log("Event was invoked");
  });

  EventHandler.fire("app.foo.bar", "makeSnafucated", { some: "data" });
});
</script>
```

{% include links.html %}
