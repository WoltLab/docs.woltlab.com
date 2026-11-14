# Migrating from WoltLab Suite 6.2 - Gallery

WoltLab Suite Gallery 6.3 rebuilds the pages that upload and edit images, videos and video links, and it stores every file of an item as a file of Core.

The fields of an item are [form builder](../../php/api/form_builder/overview.md) fields now, which are saved through `gallery\data\image\ImageBuilder` and its commands, see [Database Object Builder](../../php/database-object-builder.md).
`gallery\page\ImageEditPage` has been removed and `gallery\page\ImageAddPage` has been rewritten, together with their templates and JavaScript.

Files are uploaded through Core's [file uploads](../../php/api/file_uploads.md) instead of the Gallery's own upload, and the Gallery no longer stores them in `userImages/` and `userVideos/`.
The files of existing items are moved by the rebuild of the data after the update, see [Upgrading an Installation](#upgrading-an-installation).

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

The upload page no longer uploads or processes files through `ImageAction` either, see [Replaced Methods of `ImageAction`](#replaced-methods-of-imageaction).

## Files of Images and Videos

Each file of an item is a file of Core (`wcf1_file`) with one of three object types of the definition `com.woltlab.wcf.file`:

| Object type | File processor | Files | Referenced by |
|---|---|---|---|
| `com.woltlab.gallery.image` | `gallery\system\file\processor\ImageFileProcessor` | images | `gallery1_image.fileID` |
| `com.woltlab.gallery.video` | `gallery\system\file\processor\VideoFileProcessor` | uploaded videos | `gallery1_image.videoFileID` |
| `com.woltlab.gallery.video.thumbnail` | `gallery\system\file\processor\VideoThumbnailFileProcessor` | posters of videos and previews of video links | `gallery1_image.fileID` |

Both columns are nullable foreign keys to `wcf1_file` with `ON DELETE SET NULL`: a published item whose file Core deletes, for example because the rebuild of the files found it damaged, keeps its row without the file, whereas a pending upload is deleted together with the file of its image or video.

`filesize`, `width` and `height` describe the file that `fileID` references, `videoFilesize` the video, because the quota and the statistics of the Gallery are computed from these columns.
`ImageBuilder::setFile()` and `ImageBuilder::setVideoFile()` write them together with the reference, and the file processors update them whenever Core replaces the content of a file.
The columns of the legacy storage (`filename`, `fileExtension`, `fileHash`, `tinyThumbnailSize`, `smallThumbnailSize`, `mediumThumbnailSize`, `largeThumbnailSize`, `videoFilename`, `videoFileExtension` and `videoFileHash`) are empty for files, and they are deprecated, see [Upgrading an Installation](#upgrading-an-installation).

### Reading Files and Thumbnails

`Image::getFile()` returns the file of an image, of the poster of a video or of the preview of a video link, `Image::getVideoFile()` the file of a video, `null` if there is none.
The files of all items of an `ImageList` are loaded together when the first one is requested.

The files of images, posters and previews have the thumbnails `tiny` (144 × 144), `small` (280 × 210) and `medium` (560 × 420), which are cropped, and `large`, which fits the image into 1200 × 900.
Their identifiers are available as `Image::THUMBNAIL_TINY`, `Image::THUMBNAIL_SMALL`, `Image::THUMBNAIL_MEDIUM` and `Image::THUMBNAIL_LARGE`, from the smallest to the largest in `Image::THUMBNAIL_FORMATS`.
There is no `largeCropped` thumbnail any more.

Read the url and the dimensions of a thumbnail from the file instead of assuming those of its format:

- `Image::getSource($size)` returns the `gallery\data\image\ImageSource` that is served for a thumbnail or, for an empty string or `original`, for the original, with its `url`, `width`, `height` and `mimeType` and its size through `getFilesize()`.
  A thumbnail that does not exist falls back to the original, `null` is returned for items without a file.
  For items in the legacy storage, `null` is returned for a thumbnail that does not exist instead.
- `Image::hasThumbnail($size)` tells whether the thumbnail exists.
- `Image::getURL($size)` returns the url of `getSource($size)`, or of a placeholder for items without a file.
  For items in the legacy storage, it returns a placeholder for the thumbnails `tiny`, `small` and `medium` if they do not exist.
- `Image::getMimeType()` returns the type of the original, which is `image/webp` if Core created a WebP variant of it.
- `Image::getDisplayFilename()` and `Image::getVideoDisplayFilename()` return the name of the uploaded file.

These methods also work for items that are still stored in the legacy storage of the Gallery.

### Delivering Files

Images, posters and previews are static files of Core in `_data/public/`, the originals as well as their thumbnails.
Their urls change whenever their content does, for example after a rotation, and whenever the thumbnails are generated again, for example after the thumbnail area has been changed.
The original and the thumbnails `medium`, `large` and `largeCropped` of images in the legacy storage were delivered through `gallery\page\RawImagePage` with a check of the permission to view the image, now only their unguessable urls protect them, like the images in attachments.

Uploaded videos are not stored as static files, Core's `wcf\action\FileDownloadAction` delivers them and supports range requests.
`VideoFileProcessor::canDownload()` grants access to the uploader of a pending video and to everyone who may view the video otherwise (`Image::canView()`), whereas videos in the legacy storage were served statically to anyone who knew their url.
`Image::getVideoLink()` returns this link for uploaded videos.

The link has no file extension, therefore code that derives the type of a video from its url has to pass the type explicitly.
The items of the image lists provide it in `data-mime-type` for uploaded videos, which the slideshow passes to Fancybox as an `html5video` slide with its `html5videoFormat`.
`<video src="…">` elements need no type, the browser determines it from the response.

The static urls of files in the legacy storage (`userImages/…`, `userVideos/…`) stop working once an item has been migrated, like those of attachments and media before.
Links to `RawImagePage` keep working: it redirects the request of a migrated image to the url of its file or thumbnail (`largeCropped` to `large`) after checking the permissions, and it responds with a 404 for items without a file.
`RawImagePage` is deprecated, link to the url of `Image::getURL()` instead.

## Uploading Images

Images are uploaded through Core's [file uploads](../../php/api/file_uploads.md) with the object type `com.woltlab.gallery.image`.
The context of an upload is `{"tmpHash": "…"}`, the hash that identifies the uploads of one visit of the upload page.
Each uploaded image becomes a file that `gallery1_image.fileID` references, and a pending upload of its uploader, which is completed by saving its form as before.

Uploads are sent in chunks and are no longer limited by PHP's `upload_max_filesize` and `post_max_size`.
The client scales images down to the maximum dimensions of the uploader before the upload and keeps their type; an image that arrives larger is scaled down on the server by `gallery\system\event\listener\DownscaleUploadedImageListener` before it is validated.
`ImageFileProcessor::acceptUpload()` therefore checks the extension and the permission before the upload, but rejects only files above ten times the maximum size.
`ImageFileProcessor::validateUpload()` checks the uploaded file and reports `tooManyImages`, `quotaExceeded`, `noImage`, `fileExtension`, `minWidth`, `maxWidth`, `minHeight`, `maxHeight` and `maxSize` as a `validation` error of the final chunk.
The extension is checked against the type that is detected from the content of the file, and an image that the image adapter cannot read is rejected as `noImage`, unless it is too large for the memory limit to be read at all, which is accepted as before.
An image is rejected if it would exceed the quota, a single image could exceed it before.

Core's options for images apply to the originals: they lose their EXIF data if `image_strip_exif` is enabled, and they are converted according to `image_convert_format`.
`CompleteImageUpload` processes the file like Core does after an upload, in case the client did not request it, and burns the watermark into the original if the Gallery is configured to do so.

Uploading images in archives (Zip, Tar and Gzip) is no longer supported.

## Uploading Videos

Videos are uploaded through Core's [file uploads](../../php/api/file_uploads.md) as well, with the object type `com.woltlab.gallery.video` and the same context `{"tmpHash": "…"}` as images.
Each uploaded video becomes a file that `gallery1_image.videoFileID` references, and a pending upload with `isVideo = 1` of its uploader.

`VideoFileProcessor::acceptUpload()` checks the permission, the extension and the maximum size of videos before the upload.
`VideoFileProcessor::validateUpload()` reports `tooManyVideos`, `quotaExceeded`, `noVideo` and `maxSize` as a `validation` error of the final chunk.
The type that is detected from the content of the file must be a video type (`video/*`).
A video is rejected if it would exceed the video quota, a single video could exceed it before.

### Posters

The poster of an uploaded video is a file of the object type `com.woltlab.gallery.video.thumbnail`, which `gallery1_image.fileID` references like the file of an image.
It has the same thumbnails as images, which receive the watermark like those of images.
The form of a video uploads it through Core's `FileProcessorFormField` with the context `{"imageID": …}`.
A poster is saved as soon as its upload is completed, replacing the previous one, and deleting it removes it right away; saving the form leaves the poster as it is.
`gallery\command\image\SetVideoThumbnail` sets or removes the poster of a video.

The poster of a pending video may be changed by its uploader, the poster of a saved video by everyone who may edit it (`Image::canEdit()`), both only while uploading videos is enabled and with the permission to upload videos.
The rules for images apply to posters, except for the number of images and the quota: `VideoThumbnailFileProcessor::validateUpload()` reports `noImage`, `fileExtension`, `minDimensions`, `maxDimensions` and `maxSize` as a `validation` error of the final chunk, all but `noImage` with a message that explains the requirement.
Posters above the maximum dimensions are scaled down like images.

## Video Links

Video links are added through the endpoint `POST /gallery/images/video-links` with the link in `videoLink` and the `tmpHash` of the upload page, which creates the pending video link through `gallery\command\image\CreateVideoLink` and responds with its `imageID`, `title` and the url of its tile (`tinyURL`).
A link that cannot be added is rejected as a validation error of `videoLink` with the code `invalid`, `unknownVideoProvider`, `tooManyVideos` or `inaccessible`.

The title, the description and the preview of a link are fetched from the video platform by the command `gallery\command\image\FetchVideoLinkData`.
The endpoint, the background jobs `ThumbnailFetchingBackgroundJob` and `PendingVideoThumbnailFetchingBackgroundJob`, and `gallery\command\image\UpdateImage` for a changed link use it.
The preview is a file of the object type `com.woltlab.gallery.video.thumbnail` that `gallery1_image.fileID` references, and its thumbnails are generated right away; a new link previously had no thumbnails.
Previews are not watermarked, and they cannot be uploaded, replaced or deleted through Core's file endpoints.
Changing the link of a video link deletes its previous preview.

Video providers keep returning the preview in `thumbnail` of `IVideoProvider::fetchData()` as the location of a temporary file, which the Gallery moves into the file or deletes.
A preview that is not a JPEG, PNG, GIF or WebP image, cannot be read or is too large for the memory limit is discarded, the link then has no preview.

## Thumbnails, Rotation and Watermark

The Gallery generates the thumbnails of images, posters and previews in a listener of `wcf\event\file\GenerateThumbnail`, as WebP images like all thumbnails of Core.
The cropped formats `tiny`, `small` and `medium` show the thumbnail area of the image, or its center if it has none, `large` shows the whole image.
Thumbnails are never upscaled: a format that the image or its area does not fill in one dimension is smaller than the format, a format that the image does not fill in both dimensions does not exist.
The thumbnail area of an image must be at least as large as the largest cropped format that the image exceeds, at most 560 × 420 (`Image::getMinimumThumbnailAreaSize()`).

The watermark is applied to the thumbnails unless it is burned into the original.
`large` receives the watermark on its own, the cropped formats are derived from one watermarked image of 1200 × 900, which scales the watermark down with them.
Previously only `large` received it, unless the image or its thumbnail area was too small for `largeCropped`: then the largest of the remaining thumbnails received it and passed it on to the smaller ones.

These commands change the files of an image and generate its thumbnails again, which changes their urls:

- `gallery\command\image\RegenerateImageThumbnails` deletes the thumbnails and generates them again, for example after the thumbnail area or the watermark settings have changed.
- `gallery\command\image\RotateImage` turns the original, scales it down if the turned image exceeds the maximum dimensions, resets the thumbnail area and moves the markers with the image.
  The endpoint `POST /gallery/images/{id}/rotate` with the `angle` (90, 180 or 270) rotates an image and responds with its new `width`, `height`, the urls `stageURL` and `tinyURL` and the `minimumThumbnailArea`.
- `gallery\command\image\ApplyImageWatermark` burns the watermark into the original.

“Rebuild Thumbnails of Gallery Images” in the ACP (`gallery\system\worker\ThumbnailRebuildDataWorker`) generates the thumbnails of all files again and burns the watermark into the originals where it is missing.

## Deleting Images

`ImageAction::delete()` deletes each image through `gallery\command\image\DeleteImage` now.
The command deletes the files of the image through Core and its associated data, such as its comments, reactions, tags and moderation queue entries, updates the counters of its author and fires `gallery\event\image\ImageDeleted`.
The files of an item in the legacy storage are deleted as well.
Code that removed this data itself after calling `ImageAction::delete()` no longer needs to.

`ImageAction` no longer overrides `validateDelete()`, so the action `delete` can no longer be invoked through the AJAX proxy.
Use `gallery\command\image\DeleteImage` or the endpoint `DELETE /gallery/images/{id}` instead, which deletes a published image only once it is in the trash (`POST /gallery/images/{id}/soft-delete`) and requires `mod.gallery.canDeleteImageCompletely` for it, while uploaders can delete their own uploads that were never completed.

## Deleting Albums

`AlbumAction` no longer overrides `validateDelete()` and `delete()`, so the action `delete` can no longer be invoked through the AJAX proxy, and calling it directly no longer resets the `galleryAlbums` user storage of the owner.
Use `gallery\command\album\DeleteAlbum` or the endpoint `DELETE /gallery/albums/{id}` instead, which requires the user to own the album or to have `mod.gallery.canDeleteAlbum`.
The command resets the user storage, deletes the album and fires `gallery\event\album\AlbumDeleted`, the images of the album are kept without an album.

## Replaced Methods of `ImageAction`

These methods of `gallery\data\image\ImageAction` have been removed together with their validation:

| Removed method | Replacement |
|---|---|
| `upload()`, `uploadImages()`, `uploadVideo()` | Core's file upload with the object type `com.woltlab.gallery.image` or `com.woltlab.gallery.video`, see [Uploading Images](#uploading-images) and [Uploading Videos](#uploading-videos) |
| `processImages()`, `rotateImageByOrientation()` | Core processes uploaded files and turns images upright according to their orientation |
| `saveImageData()` | `gallery\command\image\CompleteImageUpload` or `gallery\command\image\UpdateImage` |
| `generateThumbnails()` | `gallery\command\image\RegenerateImageThumbnails` |
| `rotateImage()` | `gallery\command\image\RotateImage` and the endpoint `POST /gallery/images/{id}/rotate` |
| `addWatermark()` | `gallery\command\image\ApplyImageWatermark` |
| `uploadVideoThumbnail()`, `deleteVideoThumbnail()` | Core's file upload with the object type `com.woltlab.gallery.video.thumbnail` and Core's deletion of files (`DELETE /core/files/{id}`), see [Posters](#posters) |
| `createVideoLink()` | `gallery\command\image\CreateVideoLink` and the endpoint `POST /gallery/images/video-links` |
| `fetchVideoData()` | `gallery\command\image\FetchVideoLinkData` |

`ImageAction` no longer implements `wcf\data\IUploadAction`, uploads through `ajax-upload` with the action `upload` are rejected.
The `ImageUploadFileValidationStrategy`, `VideoUploadFileValidationStrategy`, `VideoThumbnailUploadFileValidationStrategy` and the unused `WatermarkUploadFileValidationStrategy` in `gallery\system\upload` have been removed, the file processors validate the files instead.

## Upgrading an Installation

The update to 6.3 adds the columns `fileID` and `videoFileID` and registers the object types of the files, but it does not move any file.
The images, videos, posters and previews of existing items stay in the legacy storage of the Gallery (`userImages/` and `userVideos/`) until the rebuild of the data moves them into files.
An installation is therefore expected to be updated like this:

1. Enable the maintenance mode.
2. Update to WoltLab Suite 6.3.
3. Rebuild the data, at least “Rebuild Gallery Images” and then “Rebuild Files”, which is also the order in which all workers run.
4. Disable the maintenance mode.

“Rebuild Gallery Images” (`gallery\system\worker\ImageRebuildDataWorker`) moves the files of the items in the legacy storage into files through `gallery\command\image\MigrateImageStorage`, which only moves them, like Core's migration of attachments.
“Rebuild Files” (Core's `wcf\system\worker\FileRebuildDataWorker`) afterwards generates their thumbnails and applies Core's options for images to the originals, such as the removal of EXIF data and the conversion of the format.
Core turns a migrated image upright according to the orientation of the file, which resets its thumbnail area and moves its markers with it.

A migrated item no longer has a `fileHash`, `filename` or `fileExtension` (`videoFileHash`, `videoFilename` and `videoFileExtension` for videos), its legacy files and thumbnails are deleted, and the static urls of these files stop working, see [Delivering Files](#delivering-files).

### Items in the Legacy Storage

Items that have not been migrated yet are shown as before, from the legacy storage.
They cannot be edited: the edit pages, `gallery\action\ImageItemFormAction`, the rotation endpoint and the upload of posters refuse them, and pending uploads in the legacy storage are not offered on the upload page.
`Image::usesLegacyStorage()` tells whether an item still has a file in the legacy storage (`Image::hasLegacyFile()`, `Image::hasLegacyVideoFile()`).

A legacy file that cannot become a file, because it is missing, cannot be read, is empty or, for images, posters and previews, is no image, is logged once and the item no longer references it, as if Core had deleted its file: the item stays and can be edited, but it has no image, poster or video any more.
The files left in `userImages/` and `userVideos/` are not touched.
Failures that may be temporary, such as a file that cannot be copied, are logged as well and the next rebuild of the data tries again.

### Deprecated Legacy Storage

Everything that only serves items in the legacy storage is deprecated and will be removed in the next version, together with the legacy storage itself.
That includes `Image::getLocation()`, `Image::getVideoLocation()`, `Image::getFilename()` and `Image::getVideoFilename()`, the columns of the legacy storage, `ImageEditor::deleteFiles()` and `ImageEditor::deleteImageFiles()`, `RawImagePage`, the constants `Image::VALID_SIZES`, `Image::UNRESTRICTED_ACCESS_SIZES`, `Image::LARGECROPPED_THUMBNAIL_WIDTH` and `Image::LARGECROPPED_THUMBNAIL_HEIGHT`, and the methods `Image::hasLegacyFile()`, `Image::hasLegacyVideoFile()` and `Image::usesLegacyStorage()`, see [Deprecations and Removals](deprecations_removals.md#deprecations).
The parameter `$ignorePlaceholder` of `Image::getURL()` only affects items in the legacy storage and is deprecated as well.
Use the methods described in [Reading Files and Thumbnails](#reading-files-and-thumbnails), which work for both.

## Importing Images

`gallery\system\importer\ImageImporter` stores the image at `fileLocation` as a file of the image now, the source itself is not modified.
The columns of the legacy storage that an exporter passes, such as `fileHash`, `filesize`, `width`, `height` and the sizes of the thumbnails, are ignored, they are read from the file instead; `filename` becomes the name of the file, and `rawExifData` is stored as its EXIF data.
The thumbnails are generated by the rebuild of the thumbnails or of the files, like for the attachments and the media of Core.
An image that does not exist or is not a JPEG, PNG, GIF or WebP image is not imported.

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

The removed classes, methods, properties, JavaScript and templates are listed in [Deprecations and Removals](deprecations_removals.md#removals).

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
The entry for archives has been removed from it.

The template `__imageViewer`, which no template included any more, has been removed.
