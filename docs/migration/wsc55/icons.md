# Migrating from WoltLab Suite 5.5 - Icons

WoltLab Suite 6.0 introduces Font Awesome 6.0 which is a major upgrade over the previously used Font Awesome 4.7 icon library.
The new version features not only many hundreds of new icons but also focused a lot more on icon consistency, namely the proper alignment of icons within the grid.

The previous implementation of Font Awesome 4 included shims for Font Awesome 3 that was used before, the most notable one being the `.icon` notation instead of `.fa` as seen in Font Awesome 4 and later.
In addition, Font Awesome 5 introduced the concept of different font weights to separate icons which was further extended in Font Awesome 6.

In WoltLab Suite 6.0 we have made the decision to make a clean cut and drop support for the Font Awesome 3 shim as well as a Font Awesome 4 shim in order to dramatically reduce the CSS size and to clean up the implementation.
Brand icons had been moved to a separate font in Font Awesome 5, but since more and more fonts are being added we have stepped back from relying on that font.
We have instead made the decision to embed brand icons using inline SVGs which are much more efficient when you only need a handful of brand icons instead of loading a 100kB+ font just for a few icons.

## Using Icons in Templates

The new template function `{icon}` was added to take care of generating the HTML code for icons, including the embedded SVGs for brand icons.
Icons in HTML should not be constructed using the actual HTML element, but instead always use `{icon}`.

```smarty
<button class="button">{icon size=16 name='bell'} I‘m a button with a bell icon</button>
```

Unless specified the icon will attempt to use a non-solid variant of the icon if it is available.
You can explicitly request a solid version of the icon by specifying it with `type='solid'`.

```smarty
<button class="button">{icon size=16 name='bell' type='solid'} I‘m a button with a solid bell icon</button>
```

### Brand Icons

The syntax for brand icons is very similar, but you are required to specifiy parameter `type='brand'` to access them.

```smarty
<button class="button">{icon size=16 name='facebook' type='brand'} Share on Facebook</button>
```
