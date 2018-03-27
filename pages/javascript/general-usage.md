---
title: General JavaScript Usage
sidebar: sidebar
permalink: javascript_general-usage.html
folder: javascript
---

## The History of the Legacy API

The WoltLab Suite 3.0 [introduced a new API][javascript_new-api_writing-a-module] based on AMD-Modules
with ES5-JavaScript that was designed with high performance and visible dependencies
in mind. This was a fundamental change in comparison to [the legacy API][javascript_legacy-api]
that was build many years before while jQuery was still a thing and we had to deal
with ancient browsers such as Internet Explorer 9 that felt short in both CSS and
JavaScript capabilities.

Fast forward a few years, the old API is still around and most important, it is
actively being used by some components that have not been rewritten yet. This
has been done to preserve the backwards-compatibility and to avoid the
significant amount of work that it requires to rewrite a component. The components
invoked on page initialization have all been rewritten to use the modern API, but
some deferred objects that are invoked later during the page runtime may still
use the old API.

However, the legacy API is deprecated and you should not rely on it for new
components at all. It slowly but steadily gets replaced up until a point where its
last bits are finally removed from the code base.

## Embedding JavaScript inside Templates

The `<script>`-tags are extracted and moved during template processing, eventually
placing them at the very end of the body element while preserving their order of
appearance.

This behavior is controlled through the `data-relocate="true"` attribute on the `<script>`
which is mandatory for almost all scripts, mostly because their dependencies (such
as jQuery) are moved to the bottom anyway.

```html
<script data-relocate="true">
  $(function() {
    // Code that uses jQuery (Legacy API)
  });
</script>

<!-- or -->

<script data-relocate="true">
  require(["Some", "Dependencies"], function(Some, Dependencies) {
    // Modern API
  });
</script>
```

## Including External JavaScript Files

The AMD-Modules used in the new API are automatically recognized and lazy-loaded
on demand, so unless you have a rather large and pre-compiled code-base, there
is nothing else to worry about.

### Debug-Variants and Cache-Buster

Your JavaScript files may change over time and you would want the users' browsers
to always load and use the latest version of your files. This can be achieved by
appending the special `LAST_UPDATE_TIME` constant to your file path. It contains
the unix timestamp of the last time any package was installed, updated or removed
and thus avoid outdated caches by relying on a unique value, without invalidating
the cache more often that it needs to be.

```html
<script data-relocate="true" src="{@$__wcf->getPath('app')}js/App.js?t={@LAST_UPDATE_TIME}"></script>
```

For small scripts you can simply serve the full, non-minified version to the user
at all times, the differences in size and execution speed are insignificant and
are very unlikely to offer any benefits. They might even yield a worse performance,
because you'll have to include them statically in the template, even if the code
is never called.

However, if you're including a minified build in your app or plugin, you should
include a switch to load the uncompressed version in the debug mode, while serving
the minified and optimized file to the average visitor. You should use the
`ENABLE_DEBUG_MODE` constant to decide which version should be loaded.

```html
<script data-relocate="true" src="{@$__wcf->getPath('app')}js/App{if !ENABLE_DEBUG_MODE}.min{/if}.js?t={@LAST_UPDATE_TIME}"></script>
```

### The Accelerated Guest View ("Tiny Builds")

{% include callout.html content="You can learn more on the [Accelerated Guest View][migration_wsc-30_javascript] in the migration docs." type="info" %}

The "Accelerated Guest View" was introduced in WoltLab Suite 3.1 and aims to
decrease page size and to improve responsiveness by enabling a read-only mode
for visitors. If you are providing a separate compiled build for this mode, you'll
need to include yet another switch to serve the right version to the visitor.

```html
<script data-relocate="true" src="{@$__wcf->getPath('app')}js/App{if !ENABLE_DEBUG_MODE}{if VISITOR_USE_TINY_BUILD}.tiny{/if}.min{/if}.js?t={@LAST_UPDATE_TIME}"></script>
```

### The `{js}` Template Plugin

The `{js}` template plugin exists solely to provide a much easier and less error-prone
method to include external JavaScript files.

```html
{js application='app' file='App' hasTiny=true}
```

The `hasTiny` attribute is optional, you can set it to `false` or just omit it
entirely if you do not provide a tiny build for your file.

{% include links.html %}
