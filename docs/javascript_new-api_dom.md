---
title: Working with the DOM - JavaScript API
sidebar: sidebar
permalink: javascript_new-api_dom.html
folder: javascript
---

## Helper Functions

There is large set of [helper functions][javascript_helper-functions] that assist
you when working with the DOM tree and its elements. These functions are globally
available and do not require explicit module imports.

## `Dom/Util`

### `createFragmentFromHtml(html: string): DocumentFragment`

Parses a HTML string and creates a `DocumentFragment` object that holds the
resulting nodes.

### `identify(element: Element): string`

Retrieves the unique identifier (`id`) of an element. If it does not currently
have an id assigned, a generic identifier is used instead.

### `outerHeight(element: Element, styles?: CSSStyleDeclaration): number`

Computes the outer height of an element using the element's `offsetHeight` and
the sum of the rounded down values for `margin-top` and `margin-bottom`.

### `outerWidth(element: Element, styles?: CSSStyleDeclaration): number`

Computes the outer width of an element using the element's `offsetWidth` and
the sum of the rounded down values for `margin-left` and `margin-right`.

### `outerDimensions(element: Element): { height: number, width: number }`

Computes the outer dimensions of an element including its margins.

### `offset(element: Element): { top: number, left: number }`

Computes the element's offset relative to the top left corner of the document.

### `setInnerHtml(element: Element, innerHtml: string)`

Sets the inner HTML of an element via `element.innerHTML = innerHtml`. Browsers
do not evaluate any embedded `<script>` tags, therefore this method extracts each
of them, creates new `<script>` tags and inserts them in their original order of
appearance.

### `contains(element: Element, child: Element): boolean`

Evaluates if `element` is a direct or indirect parent element of `child`.

### `unwrapChildNodes(element: Element)`

Moves all child nodes out of `element` while maintaining their order, then removes
`element` from the document.

## `Dom/ChangeListener`

This class is used to observe specific changes to the DOM, for example after an
Ajax request has completed. For performance reasons this is a manually-invoked
listener that does not rely on a `MutationObserver`.

```js
require(["Dom/ChangeListener"], function(DomChangeListener) {
  DomChangeListener.add("App/Foo", function() {
    // the DOM may have been altered significantly
  });

  // propagate changes to the DOM
  DomChangeListener.trigger();
});
```

{% include links.html %}
