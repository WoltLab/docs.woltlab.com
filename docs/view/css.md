# CSS

## SCSS and CSS

SCSS is a scripting language that features a syntax similar to CSS and compiles into native CSS at runtime. It provides many great additions to CSS such as declaration nesting and variables, it is recommended to read the [official guide](http://sass-lang.com/guide) to learn more.

You can create `.scss` files containing only pure CSS code and it will work just fine, you are at no point required to write actual SCSS code.

### File Location

Please place your style files in a subdirectory of the `style/` directory of the target application or the Core's style directory, for example `style/layout/pageHeader.scss`.

### Variables

You can access variables with `$myVariable`, variable interpolation (variables inside strings) is accomplished with `#{$myVariable}`.

#### Linking images

Images used within a style must be located in the style's image folder. To get the folder name within the CSS the SCSS variable `#{$style_image_path}` can be used. The value will contain a trailing slash.

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

!!! info "Some very large smartphones, for example the Apple iPhone 7 Plus, do match the media query for `Tablets (portrait)` when viewed in landscape mode."

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

## Asset Preloading

WoltLab Suite’s SCSS compiler supports adding [preloading](https://developer.mozilla.org/en-US/docs/Web/HTML/Preloading_content) metadata to the CSS.
To communicate the preloading intent to the compiler the `--woltlab-suite-preload` CSS variable is set to the result of the `preload()` function:

```scss
body {
    --woltlab-suite-preload:    #{preload(
                                    '#{$style_image_path}custom/background.png',
                                    $as: "image",
                                    $crossorigin: false,
                                    $type: "image/png"
                                )};

    background: url('#{$style_image_path}custom/background.png');
}
```

The parameters of the `preload()` function map directly to the preloading properties that are used within the `<link>` tag and the `link:` HTTP response header.

The above example will result in a `<link>` similar to the following being added to the generated HTML:

```
<link rel="preload" href="https://example.com/images/style-1/custom/background.png" as="image" type="image/png">
```

!!! info "Use preloading sparingly for the most important resources where you can be certain that the browser will need them. Unused preloaded resources will unnecessarily waste bandwidth."
