---
title: JavaScript Helper Functions
sidebar: sidebar
permalink: javascript_helper-functions.html
folder: javascript
---

## Introduction

WoltLab Suite 3.0 and 3.1 ship with a set of global helper functions that are
exposed on the `window`-object and thus are available regardless of the context.
They are meant to reduce code repetition and to increase readability by moving
potentially relevant parts to the front of an instruction.

## Elements

### `elCreate(tagName: string): Element`

Creates a new element with the provided tag name.

```js
var element = elCreate("div");
// equals
var element = document.createElement("div");
```

### `elRemove(element: Element)`

Removes an element from its parent without returning it. This function will throw
an error if the `element` doesn't have a parent node.

```js
elRemove(element);
// equals
element.parentNode.removeChild(element);
```

### `elShow(element: Element)`

Attempts to show an element by removing the `display` CSS-property, usually used
in conjunction with the `elHide()` function.

```js
elShow(element);
// equals
element.style.removeProperty("display");
```

### `elHide(element: Element)`

Attempts to hide an element by setting the `display` CSS-property to `none`, this
is intended to be used with `elShow()` that relies on this behavior.

```js
elHide(element);
// equals
element.style.setProperty("display", "none", "");
```

### `elToggle(element: Element)`

Attempts to toggle the visibility of an element by examining the value of the
`display` CSS-property and calls either `elShow()` or `elHide()`.

## Attributes

### `elAttr(element: Element, attribute: string, value?: string): string`

Sets or reads an attribute value, value are implicitly casted into strings and
reading non-existing attributes will always yield an empty string. If you want
to test for attribute existence, you'll have to fall-back to the native
[`Element.hasAttribute()`](https://developer.mozilla.org/en-US/docs/Web/API/Element/hasAttribute)
method.

You should read and set native attributes directly, such as `img.src` rather
than `img.getAttribute("src");`.

```js
var value = elAttr(element, "some-attribute");
// equals
var value = element.getAttribute("some-attribute");

elAttr(element, "some-attribute", "some value");
// equals
element.setAttribute("some-attribute", "some value");
```

### `elAttrBool(element: Element, attribute: string): boolean`

Reads an attribute and converts it value into a boolean value, the strings `"1"`
and `"true"` will evaluate to `true`. All other values, including a missing attribute,
will return `false`.

```js
if (elAttrBool(element, "some-attribute")) {
  // attribute is true-ish
}
```

### `elData(element: Element, attribute: string, value?: string): string`

Short-hand function to read or set HTML5 `data-*`-attributes, it essentially
prepends the `data-` prefix before forwarding the call to `elAttr()`.

```js
var value = elData(element, "some-attribute");
// equals
var value = elAttr(element, "data-some-attribute");

elData(element, "some-attribute", "some value");
// equals
elAttr(element, "data-some-attribute", "some value");
```

### `elDataBool(element: Element, attribute: string): boolean`

Short-hand function to convert a HTML5 `data-*`-attribute into a boolean value. It
prepends the `data-` prefix before forwarding the call to `elAttrBool()`.

```js
if (elDataBool(element, "some-attribute")) {
  // attribute is true-ish
}
// equals
if (elAttrBool(element, "data-some-attribute")) {
  // attribute is true-ish
}
```

## Selecting Elements

{% include callout.html content="Unlike libraries like jQuery, these functions will return `null` if an element is not found. You are responsible to validate if the element exist and to branch accordingly, invoking methods on the return value without checking for `null` will yield an error." type="warning" %}

### `elById(id: string): Element | null`

Selects an element by its `id`-attribute value.

```js
var element = elById("my-awesome-element");
// equals
var element = document.getElementById("my-awesome-element");
```

### `elBySel(selector: string, context?: Element): Element | null`

{% include callout.html content="The underlying `querySelector()`-method works on the entire DOM hierarchy and can yield results outside of your context element! Please read and understand the MDN article on [`Element.querySelector()`](https://developer.mozilla.org/en-US/docs/Web/API/Element/querySelector#The_entire_hierarchy_counts) to learn more about this." type="danger" %}

Select a single element based on a CSS selector, optionally limiting the results
to be a direct or indirect children of the context element.

```js
var element = elBySel(".some-element");
// equals
var element = document.querySelector(".some-element");

// limiting the scope to a context element:
var element = elBySel(".some-element", context);
// equals
var element = context.querySelector(".some-element");
```

### `elBySelAll(selector: string, context?: Element, callback: (element: Element) => void): NodeList`

{% include callout.html content="The underlying `querySelector()`-method works on the entire DOM hierarchy and can yield results outside of your context element! Please read and understand the MDN article on [`Element.querySelector()`](https://developer.mozilla.org/en-US/docs/Web/API/Element/querySelector#The_entire_hierarchy_counts) to learn more about this." type="danger" %}

Finds and returns a `NodeList` containing all elements that match the provided
CSS selector. Although `NodeList` is an array-like structure, it is not possible
to iterate over it using array functions, including `.forEach()` which is not
available in Internet Explorer 11.

```js
var elements = elBySelAll(".some-element");
// equals
var elements = document.querySelectorAll(".some-element");

// limiting the scope to a context element:
var elements = elBySelAll(".some-element", context);
// equals
var elements = context.querySelectorAll(".some-element");
```

#### Callback to Iterate Over Elements

`elBySelAll()` supports an optional third parameter that expects a callback function
that is invoked for every element in the list.

```js
// set the 2nd parameter to `undefined` or `null` to query the whole document
elBySelAll(".some-element", undefined, function(element) {
  // is called for each element
});

// limiting the scope to a context element:
elBySelAll(".some-element", context, function(element) {
  // is called for each element
});
```

### `elClosest(element: Element, selector: string): Element | null`

Returns the first `Element` that matches the provided CSS selector, this will
return the provided element itself if it matches the selector.

```js
var element = elClosest(context, ".some-element");
// equals
var element = context.closest(".some-element");
```

#### Text Nodes

If the provided context is a `Text`-node, the function will move the context to
the parent element before applying the CSS selector. If the `Text` has no parent,
`null` is returned without evaluating the selector.

### `elByClass(className: string, context?: Element): NodeList`

Returns a live `NodeList` containing all elements that match the provided CSS
class now _and_ in the future! The collection is automatically updated whenever
an element with that class is added or removed from the DOM, it will also include
elements that get dynamically assigned or removed this CSS class.

You absolutely need to understand that this collection is dynamic, that means that
elements can and will be added and removed from the collection _even while_ you
iterate over it. There are only very few cases where you would need such a collection,
almost always `elBySelAll()` is what you're looking for.

```js
// no leading dot!
var elements = elByClass("some-element");
// equals
var elements = document.getElementsByClassName("some-element");

// limiting the scope to a context element:
var elements = elByClass("some-element", context);
// equals
var elements = context.getElementsByClassName(".some-element");
```

### `elByTag(tagName: string, context?: Element): NodeList`

Returns a live `NodeList` containing all elements with the provided tag name now
_and_ in the future! Please read the remarks on `elByClass()` above to understand
the implications of this.

```js
var elements = elByTag("div");
// equals
var elements = document.getElementsByTagName("div");

// limiting the scope to a context element:
var elements = elByTag("div", context);
// equals
var elements = context.getElementsByTagName("div");
```

## Utility Functions

### `elInnerError(element: Element, errorMessage?: string, isHtml?: boolean): Element | null``

Unified function to display and remove inline error messages for input elements,
please read the [section in the migration docs](migration_wsc-30_javascript.html#helper-function-for-inline-error-messages)
to learn more about this function.

## String Extensions

### `hashCode(): string`

Computes a numeric hash value of a string similar to Java's `String.hashCode()` method.

```js
console.log("Hello World".hashCode());
// outputs: -862545276
```

{% include links.html %}
