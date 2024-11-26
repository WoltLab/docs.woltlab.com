# Migrating from WoltLab Suite 6.1 - Templates

## Image Viewer

The previous image viewer `WCF.ImageViewer` used the CSS class `.jsImageViewer` to open the image viewer.
From now on this is done via the attribute `data-fancybox`, which opens the new [Image Viewer](../../javascript/components_image_viewer.md).
Grouping is supported through the attribute `data-fancybox="foo"`.

#### Previous Code Example

```smarty
<a href="{$link}" class="jsImageViewer" title="{$title}">
    <img src="{$thumbnailUrl}" width="…" height="…" alt="">
</a>
```

#### New Code Example

```smarty
<a href="{$link}" data-caption="{$title}" data-fancybox>
    <img src="{$thumbnailUrl}" width="…" height="…" alt="">
</a>
```
