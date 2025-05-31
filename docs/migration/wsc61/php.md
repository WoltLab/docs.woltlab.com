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

## Forms

The following forms have been migrated to FormBuilder forms.
Plugins that have been hooked into these forms via event listeners must be adapted accordingly.

- `wcf\acp\form\BBCodeMediaProviderAddForm`
- `wcf\acp\form\BBCodeMediaProviderEditForm`
- `wcf\acp\form\CaptchaQuestionAddForm`
- `wcf\acp\form\CaptchaQuestionEditForm`
- `wcf\acp\form\ContactOptionAddForm`
- `wcf\acp\form\ContactOptionEditForm`
- `wcf\acp\form\ContactRecipientAddForm`
- `wcf\acp\form\ContactRecipientEditForm`
- `wcf\acp\form\CronjobAddForm`
- `wcf\acp\form\CronjobEditForm`
- `wcf\acp\form\LabelAddForm`
- `wcf\acp\form\LabelEditForm`
- `wcf\acp\form\MenuAddForm`
- `wcf\acp\form\MenuEditForm`
- `wcf\acp\form\MenuItemAddForm`
- `wcf\acp\form\MenuItemEditForm`
- `wcf\acp\form\SitemapEditForm`
- `wcf\acp\form\TagAddForm`
- `wcf\acp\form\TagEditForm`
- `wcf\acp\form\TemplateGroupAddForm`
- `wcf\acp\form\TemplateGroupEditForm`
- `wcf\acp\form\UserOptionAddForm`
- `wcf\acp\form\UserOptionEditForm`
- `wcf\acp\form\UserRankAddForm`
- `wcf\acp\form\UserRankEditForm`
- `wcf\form\ContactForm`
