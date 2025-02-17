# Migrating from WoltLab Suite 6.1 - PHP

## User Activity Events

User activity events can optionally be provided with an image.
The `setImage()` method expects an object of type `wcf\system\file\processor\ImageData` as a parameter.

Classes that implement the interface `wcf\system\file\processor\IImageDataProvider` provide the `getImageData()` method,
that returns a suitable `ImageData` object.

#### Example

```php
$object = new FooBarObject(1);
$event->setTitle(WCF::getLanguage()->getDynamicVariable('com.foo.bar', [
    // variables
]));
$event->setDescription(
    StringUtil::encodeHTML(
        StringUtil::truncate($object->getPlainTextMessage(), 500)
    ),
    true
);
$event->setLink($object->getLink());
$event->setImage(new ImageData('image_src', 800, 600));
```
