---
title: Code Snippets - JavaScript API
sidebar: sidebar
permalink: javascript_code-snippets.html
folder: javascript
---

This is a list of code snippets that do not fit into any of the other articles
and merely describe how to achieve something very specific, rather than explaining
the inner workings of a function.

## ImageViewer

The ImageViewer is available on all frontend pages by default, you can easily
add images to the viewer by wrapping the thumbnails with a link with the CSS
class `jsImageViewer` that points to the full version.

```html
<a href="http://example.com/full.jpg" class="jsImageViewer">
  <img src="http://example.com/thumbnail.jpg">
</a>
```

{% include links.html %}
