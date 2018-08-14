---
title: CSS
sidebar: sidebar
permalink: view_css.html
folder: view
---

## SCSS and CSS

SCSS is a scripting language that features a syntax similar to CSS and compiles into native CSS at runtime. It provides many great additions to CSS such as declaration nesting and variables, it is recommended to read the [official guide](http://sass-lang.com/guide) to learn more.

You can create `.scss` files containing only pure CSS code and it will work just fine, you are at no point required to write actual SCSS code.

### File Location

Please place your style files in a subdirectory of the `style/` directory of the target application or the Core's style directory, for example `style/layout/pageHeader.scss`.

### Variables

You can access variables with `$myVariable`, variable interpolation (variables inside strings) is accomplished with `#{$myVariable}`.

## Media Breakpoints

Media breakpoints instruct the browser to apply different CSS depending on the viewport dimensions, e.g. serving a desktop PC a different view than when viewed on a smartphone.

```scss
/* red background color for desktop pc */
@include screen-lg {
    body {
        background-color: red;
    }
}

/* green background color on smartphones and tablets */
@include screen-md-down {
    body {
        background-color: green;
    }
}
```

### Available Breakpoints

{% include callout.html content="Some very large smartphones, for example the Apple iPhone 7 Plus, do match the media query for `Tablets (portrait)` when viewed in landscape mode." type="info" %}

| Name | Devices | `@media` equivalent |
|-------|-------|-------|
| `screen-xs` | Smartphones only | `(max-width: 544px)` |
| `screen-sm` | Tablets (portrait) | `(min-width: 545px) and (max-width: 768px)` |
| `screen-sm-down` | Tablets (portrait) and smartphones | `(max-width: 768px)` |
| `screen-sm-up` | Tablets and desktop PC | `(min-width: 545px)` |
| `screen-sm-md` | Tablets only | `(min-width: 545px) and (max-width: 1024px)` |
| `screen-md` | Tablets (landscape) | `(min-width: 769px) and (max-width: 1024px)` |
| `screen-md-down` | Smartphones and Tablets | `(max-width: 1024px)` |
| `screen-md-up` | Tablets (landscape) and desktop PC | `(min-width: 769px)` |
| `screen-lg` | Desktop PC | `(min-width: 1025px)` |
