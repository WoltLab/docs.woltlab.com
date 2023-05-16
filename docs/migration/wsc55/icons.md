# Migrating from WoltLab Suite 5.5 - Icons

WoltLab Suite 6.0 introduces Font Awesome 6.0 which is a major upgrade over the previously used Font Awesome 4.7 icon library.
The new version features not only many hundreds of new icons but also focused a lot more on icon consistency, namely the proper alignment of icons within the grid.

The previous implementation of Font Awesome 4 included shims for Font Awesome 3 that was used before, the most notable one being the `.icon` notation instead of `.fa` as seen in Font Awesome 4 and later.
In addition, Font Awesome 5 introduced the concept of different font weights to separate icons which was further extended in Font Awesome 6.

In WoltLab Suite 6.0 we have made the decision to make a clean cut and drop support for the Font Awesome 3 shim as well as a Font Awesome 4 shim in order to dramatically reduce the CSS size and to clean up the implementation.
Brand icons had been moved to a separate font in Font Awesome 5, but since more and more fonts are being added we have stepped back from relying on that font.
We have instead made the decision to embed brand icons using inline SVGs which are much more efficient when you only need a handful of brand icons instead of loading a 100kB+ font just for a few icons.

## Misuse of Icons as Buttons

One pattern that could be found every here and then was the use of icons as buttons.
Using icons in buttons is fine, as long as there is a readable title and that they are properly marked as buttons.

A common misuse looks like this:

```smarty
<span class="icon icon16 fa-times pointer jsMyDeleteButton" data-some-object-id="123"></span>
```

This example has a few problems, for starters it is not marked as a button which would require both `role="button"` and `tabindex="0"` to be recognized as such.
Additionally there is no title which leaves users clueless about what the option does, especially visually impaired users are possibly unable to identify the icon.

WoltLab Suite 6.0 addresses this issue by removing all default styling from `<button>` elements, making them the ideal choice for button type elements.

```smarty
<button class="jsMyDeleteButton" data-some-object-id="123" title="descriptive title here">{icon name='xmark'}</button>
```

The icon will appear just as before, but the button is now properly accessible.

## Using CSS Classes With Icons

It is strongly discouraged to apply CSS classes to icons themselves.
Icons inherit the text color from the surrounding context which removes the need to manually apply the color.

If you ever need to alter the icons, such as applying a special color or transformation, you should wrap the icon in an element like `<span>` and apply the changes to that element instead.

## Using Icons in Templates

The new template function `{icon}` was added to take care of generating the HTML code for icons, including the embedded SVGs for brand icons.
Icons in HTML should not be constructed using the actual HTML element, but instead always use `{icon}`.

```smarty
<button class="button">{icon name='bell'} I’m a button with a bell icon</button>
```

Unless specified the icon will attempt to use a non-solid variant of the icon if it is available.
You can explicitly request a solid version of the icon by specifying it with `type='solid'`.

```smarty
<button class="button">{icon name='bell' type='solid'} I’m a button with a solid bell icon</button>
```

Icons will implicitly assume the size `16`, but you can explicitly request a different icon size using the `size` attribute:

```smarty
{icon size=24 name='bell' type='solid'}
```

### Brand Icons

The syntax for brand icons is very similar, but you are required to specifiy parameter `type='brand'` to access them.

```smarty
<button class="button">{icon size=16 name='facebook' type='brand'} Share on Facebook</button>
```

## Using Icons in TypeScript/JavaScript

Buttons can be dynamically created using the native `document.createElement()` using the new `fa-icon` element.

```ts
const icon = document.createElement("fa-icon");
icon.setIcon("bell", true);

// This is the same as the following call in templates:
// {icon name='bell' type='solid'}
```

You can request a size other than the default value of `16` through the `size` property:

```ts
const icon = document.createElement("fa-icon");
icon.size = 24;
icon.setIcon("bell", true);
```

### Creating Icons in HTML Strings

You can embed icons in HTML strings by constructing the `fa-icon` element yourself.

```ts
element.innerHTML = '<fa-icon name="bell" solid></fa-icon>';
```

### Changing an Icon on Runtime

You can alter the size by changing the `size` property which accepts the numbers `16`, `24`, `32`, `48`, `64`, `96`, `128` and `144`.
The icon itself should be always set through the `setIcon(name: string, isSolid: boolean)` function which validates the values and rejects unknown icons.

```ts
const div = document.createElement("div");
div.innerHTML = '<fa-icon name="user"></fa-icon>';

const icon = div.querySelector("fa-icon");
icon.size = 24;
icon.setIcon("bell", true);
```

## Migrating Icons

We provide a helper script that eases the transition by replacing icons in templates, JavaScript and TypeScript files.
The script itself is very defensive and only replaces obvious matches, it will leave icons with additional CSS classes or attributes as-is and will need to be manually adjusted.

### Replacing Icons With the Helper Script

The helper script is part of WoltLab Suite Core and can be found in the repository at `extra/migrate-fa-v4.php`.
The script must be executed from CLI and requires PHP 8.1.

```shell
$> php extra/migrate-fa-v4.php /path/to/the/target/directory/
```

The target directory will be searched recursively for files with the extension `tpl`, `js` and `ts`.

### Replacing Icons Manually by Example

The helper script above is limited to only perform replacements for occurrences that it can identify without doubt.
It will not replace occurrences that are formatted differently and/or make use of additional attributes, including the icon misuse as clickable elements.

```smarty
<li>
    <span class="icon icon16 fa-times pointer jsButtonFoo jsTooltip" title="{lang}foo.bar.baz{/lang}">
</li>
```

This can be replaced using a proper button element which also provides proper accessibility for free.

```smarty
<li>
    <button class="jsButtonFoo jsTooltip" title="{lang}foo.bar.baz{/lang}">
        {icon name='xmark'}
    </button>
</li>
```

### Replacing Icons in CSS

Icons created through CSS properties are generally not supported.
It was often used as a convenient way to introduce a new icon with little hassle, but has way too many downsides, including but not limited to accessibility.

Existing icons injected through CSS properties are dysfunctional and must be converted into regular icons that are injected into the DOM.
If you cannot embed them directly in the template, you should inject those using JavaScript on runtime.
It is possible to inject icons using JavaScript on page load by relying entierly on native JavaScript, because the icon component is eagerly loaded ahead of time, preventing any flicker or layout shifts.

### Migrating Admin-Configurable Icons

If admin-configurable icon names (e.g. created by [`IconFormField`](../../php/api/form_builder/form_fields.md#iconformfield)) are stored within the database, these need to be migrated with an [upgrade script](../../package/pip/script.md).

The `FontAwesomeIcon::mapVersion4()` maps a Font Awesome 4 icon name to a string that may be passed to `FontAwesomeIcon::fromString()`.
It will throw an `UnknownIcon` exception if the icon cannot be mapped.
It is important to catch and handle this exception to ensure a reliable upgrade even when facing malformed data.

See [WoltLab/WCF#5288](https://github.com/WoltLab/WCF/pull/5288) for an example script.
