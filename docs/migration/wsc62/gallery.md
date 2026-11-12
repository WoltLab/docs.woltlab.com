# Migrating from WoltLab Suite 6.2 - Gallery

The pages that upload and edit images, videos and video links have been rebuilt in WoltLab Suite Gallery 6.3.
Their fields are [form builder](../../php/api/form_builder/overview.md) fields now, which are saved through `gallery\data\image\ImageBuilder` and its commands, see [Database Object Builder](../../php/database-object-builder.md).
`gallery\page\ImageEditPage` has been removed and `gallery\page\ImageAddPage` has been rewritten, together with their templates and JavaScript.

## Upload and Edit Pages

| Page | Controller | Request |
|---|---|---|
| Uploading images, videos and video links | `gallery\page\ImageAddPage` | `image-add/`, optionally with `categoryIDs[]` and `albumID` |
| Editing a single item | `gallery\form\ImageEditForm` | `image-edit/{id}/` |
| Editing several items | `gallery\page\ImageBulkEditPage` | `image-bulk-edit/?imageIDs[]=…` |

`gallery\form\ImageEditForm` replaces `gallery\page\ImageEditPage`, which extended `ImageAddPage` and edited one or several items with the template of the upload page.
Editing several items moved to the new `gallery\page\ImageBulkEditPage`.

The upload page and the bulk edit page show a list of the items and the form of the open item beside it.
Each item has a form of its own, which `gallery\action\ImageItemFormAction` provides and saves.
Saving an uploaded item completes the upload through `gallery\command\image\CompleteImageUpload`, saving an existing item runs `gallery\command\image\UpdateImage`.

### Saving No Longer Runs `ImageAction`

`gallery\data\image\ImageAction::saveImageData()` has been removed, and these pages no longer save through `ImageAction::update()`.
Event listeners on these actions are therefore not called when items are uploaded or edited on these pages.
Listen to the following events instead, which both provide the image and the `ImageBuilder` that saved it:

- `gallery\event\image\ImageUploadCompleted` is fired when an upload has been saved, before the image is published or waits for the approval of a moderator.
- `gallery\event\image\ImageUpdated` is fired when an existing image has been saved.

The actions of `ImageAction` that the upload page still uses for uploading and processing files are unchanged.

## Adding Fields to the Image Form

All fields of an item are created by the form container `gallery\system\form\builder\container\ImageFormContainer`, which the single edit form, the forms of the upload and bulk edit page and the bulk edit dialogs share.
After creating its fields, the container fires the event `populate`, where plugins add their own fields.

Most fields of an item are shown as collapsible rows, which are the children of the container with the id `rows`.
A row is a `gallery\system\form\builder\container\CollapsibleFormContainer` that shows a summary of its values while it is collapsed.
Required fields are not marked with an asterisk: a row that contains a required field shows “(Required)” while it is empty.

The fields write their values into the `ImageBuilder`.
A plugin that stores a value in a column of its own, which it added to the `gallery1_image` table, uses `setCustomProperty()`:

```php
<?php

namespace example\system\event\listener;

use gallery\data\image\Image;
use gallery\data\image\ImageBuilder;
use gallery\system\form\builder\container\CollapsibleFormContainer;
use gallery\system\form\builder\container\ImageFormContainer;
use wcf\system\event\listener\IParameterizedEventListener;
use wcf\system\form\builder\container\IFormContainer;
use wcf\system\form\builder\field\TextFormField;

final class ImageFormContainerLicenseListener implements IParameterizedEventListener
{
    /**
     * @param ImageFormContainer $eventObj
     */
    #[\Override]
    public function execute($eventObj, $className, $eventName, array &$parameters)
    {
        $rows = $eventObj->getNodeById('rows');
        \assert($rows instanceof IFormContainer);

        $rows->appendChild(
            CollapsibleFormContainer::create('licenseRow')
                ->label('example.image.license')
                ->appendChild(
                    TextFormField::create('license')
                        ->label('example.image.license')
                        ->maximumLength(255)
                        ->loadValueCallback(static function (Image $image, TextFormField $field) {
                            $field->value($image->license ?? '');
                        })
                        ->saveValueCallback(static function (ImageBuilder $builder, TextFormField $field) {
                            $builder->setCustomProperty('license', $field->getValue());
                        })
                )
        );

        // Offers the row in the "Edit" menu for selected items, see below.
        $eventObj->bulkEditable('licenseRow', 'example.image.license');
    }
}
```

```xml
<eventlistener name="imageFormContainerLicense">
	<eventclassname>gallery\system\form\builder\container\ImageFormContainer</eventclassname>
	<eventname>populate</eventname>
	<listenerclassname>example\system\event\listener\ImageFormContainerLicenseListener</listenerclassname>
</eventlistener>
```

`ImageFormContainer::getImage()` returns the edited image, for example to add a field to images only (`Image::isImage()`).
Data that is not a column of the image, such as rows in a table of the plugin, is saved in a listener of `ImageUploadCompleted` and `ImageUpdated`.

### Editing a Row for Several Items

On the upload page and the bulk edit page, the “Edit” menu edits a row for all selected items at once in a dialog.
The Gallery offers its own rows there, plugins opt in with `ImageFormContainer::bulkEditable()`, which takes the id of the row and the language item of its name in the menu.
The dialog shows only that row and applies its values to the forms of the selected items, which are saved later with the rest of the form.

## Removals

The removed classes, methods, properties, JavaScript and template events are listed in [Deprecations and Removals](deprecations_removals.md#removals).

### PHP

`gallery\page\ImageAddPage::$pendingImageList` is typed as `gallery\data\image\ImageList` now and always holds the uploads that were not saved in an earlier visit.

### JavaScript

`js/Gallery.js` has been removed together with the `Gallery` namespace and all of its classes, which only served the previous upload and edit page.

### Templates

The template `imageAdd` has been rewritten, and the template events of the previous page have been removed.
Use the `populate` event of `ImageFormContainer` to add fields instead, see [Adding Fields to the Image Form](#adding-fields-to-the-image-form).
The markup of the previous page has been removed with it, including `#thumbnailList`, `#imageContainer`, `#currentImageContainer`, `#pendingImageContainer` and `.jsOverwriteDataButton`, as well as their styles.

The template `imageAddRestrictions` is shown in a dialog that the upload page loads on demand.
Its root element is `div.galleryUploadRestrictions` instead of `#galleryUploadRestrictions.jsStaticDialogContent`, its template events `imageUploadRestrictions`, `videoUploadRestrictions` and `videoLinkRestrictions` are unchanged.
