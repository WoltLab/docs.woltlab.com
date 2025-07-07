# Image Viewer

The Image Viewer component enables interactive image viewing in a modal, using the [Fancybox](https://fancyapps.com/fancybox/) library.
It supports automatic or manual opening and grouping of images.

Not only images, but also Videos, YouTube Videos, PDFs, HTML, [...](https://fancyapps.com/fancybox/) are supported and can be displayed in the modal.
The appropriate `data-type` can be set, the system tries to determine this if it has not been set.
You must include the `data-type` property when showing images that are generated through `data-src` URLS that end in `.php` or `.html`.
For example, the following can be used:

- `image` - Image
- `pdf` - PDF file
- `html5video` - HTML5 video
- `youtube` - YouTube video
- `vimeo` - Vimeo video

## Open Image Viewer Automatically

The following code example adds an image to the global modal and groups it with all other images that are not explicitly grouped:

```smarty
<a href="{$imageLink}" data-caption="{$caption}" data-type="image" data-fancybox>
	<img src="{$thumbnailUrl}" width="…" height="…" alt="">
</a>
```

If you want to display several images in a group, you can group them using the `data-fancybox` attribute. Images with the same group name are then displayed together:

```smarty
<a href="{$imageLink}" data-caption="{$caption}" data-type="image" data-fancybox="fooBar">
	<img src="{$thumbnailUrl}" width="…" height="…" alt="">
</a>
```

## Grouping content from the same message

To ensure that images are only grouped from the same message in the same modal, `data-fancybox="message-{$messageObjectType}-{$messageObjectID}"` is used in the BBCode.

```php
final class FooBBCode extends AbstractBBCode
{
    #[\Override]
    public function getParsedTag(array $openingTag, $content, array $closingTag, BBCodeParser $parser): string
    {
        $objectID = (!empty($openingTag['attributes'][0])) ? \intval($openingTag['attributes'][0]) : 0;
        if (!$objectID) {
            return '';
        }

        $foo = MessageEmbeddedObjectManager::getInstance()->getObject('com.woltlab.wcf.foo', $objectID);
        if ($foo === null) {
            return ContentNotVisibleView::forNotAvailable();
        }

        if (!$foo->isAccessible()) {
            return ContentNotVisibleView::forNoPermission();
        }

        if ($parser->getOutputType() == 'text/html') {
            return WCF::getTPL()->fetch('shared_bbcode_foo', 'wcf', [
                'imageLink' => $foo->getLink(),
                'caption' => $foo->getTitle(),
                'activeMessageID' => MessageEmbeddedObjectManager::getInstance()->getActiveMessageID(),
                'activeMessageObjectType' => MessageEmbeddedObjectManager::getInstance()->getActiveMessageObjectType(),
            ], true);
        } elseif ($parser->getOutputType() == 'text/simplified-html') {
            return StringUtil::getAnchorTag($foo->getLink(), $foo->getTitle());
        } else {
            return StringUtil::encodeHTML($foo->getLink());
        }
    }
}
```

```smarty title="shared_bbcode_foo.tpl"
<a href="{$imageLink}" data-caption="{$caption}" data-type="image" data-fancybox="message-{$activeMessageObjectType}-{$activeMessageObjectID}">
	<img src="{$thumbnailUrl}" width="…" height="…" alt="">
</a>
```

## Open Image Viewer Manually

The Image Viewer can also be created dynamically using the function `WoltLabSuite/Core/Component/Image/Viewer::showFancybox()`.
The contents of the modal are passed directly to the function.

### Example

The following code example creates a button that opens the Image Viewer with two images:

```html

<button type="button" id="openImageViewer">Open Image Viewer</button>

<script data-relocate="true">
  require(['WoltLabSuite/Core/Component/Image/Viewer'], ({ showFancybox }) => {
    document.getElementById("openImageViewer").addEventListener("click", () => {
      showFancybox([
        {
          src: "https://example.com/image1.jpg",
          caption: "Image 1",
          type: "image",
        },
        {
          src: "https://example.com/image2.jpg",
          caption: "Image 2",
          type: "image",
        },
      ]);
    });
  });
</script>
```
