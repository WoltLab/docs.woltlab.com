# Migrating from WoltLab Suite 6.1 - Templates

## Image Viewer

The previous image viewer `WCF.ImageViewer` has been open by the HTML class `.jsImageViewer`.
From now on this is done via the attribute `data-fancybox`, which opens the new [Image Viewer](../../javascript/components_image_viewer.md).
Which also now supports grouping, `data-fancybox="foo"`.

#### Previous Code Example

```smarty
<a href="{$link}" class="jsImageViewer" title="{$title}">
    <img src="{$link}">
</a>
```

#### New Code Example

```smarty
<a href="{$link}" data-caption="{$title}" data-fancybox>
    <img src="{$link}">
</a>
```
