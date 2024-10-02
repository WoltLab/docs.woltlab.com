# File Uploads

Uploading files is handled through the unified upload pipeline introduced in WoltLab Suite 6.1.
The API covers the upload process, storage, thumbnail generation and serving of files to the browser.
Attachments are implemented as an extra layer on top of the upload pipeline.

The most common use cases are the attachment system that relies on the WYSIWYG editor as well as the `FileProcessorFormField`.

# Provide the `IFileProcessor`

At the very core of each uploadable type is an implementation of `IFileProcessor` that handles the validation and handling of new files.

It is strongly recommended to derive from `AbstractFileProcessor` that makes it easy to opt out of some extra features and will provide backwards compatibility with new features.

## Important Methods and Features

Please refer to the documentation in `IFileProcessor` for an explanation of methods that are not explained here.

### Thumbnails

It is possible to automatically generate thumbnails for any uploaded file.
The number of thumbnail variants is not limited but each thumbnail must have an identifier that is unique for your file processor.

```php
#[\Override]
public function getThumbnailFormats(): array
{
    return [
        new ThumbnailFormat(
            '', // An empty string is a valid identifier.
            800,
            600,
            true, // Retaining the dimensions can cause _one_ of the sides to underflow the configured width or height.
        ),
        new ThumbnailFormat(
            'square',
            100,
            100,
            false, // This will generate a 100x100 thumbnail where the longest size will be cropped from the center.
        ),
    ];
}
```

The abstract implementation returns an empty array which will disable thumbnails entirely.
Changes to the thumbnail configuration, for example, updating the dimensions or adding new thumbnail formats are not applied to existing files automatically.

The system tracks the configuration used to generate a thumbnail.
The existing rebuild data worker for files will check the existing thumbnails against the formats provided by your processor and regenerate any thumbnail when possible.

The identifier must be stable because it is used to verify if a specific thumbnail still matches the configured settings.

### Resizing Images Before Uploading

You can opt-in to the resize feature by returning a custom `ResizeConfiguration` from `getResizeConfiguration`, otherwise images of arbitrary size will be accepted.

```php
#[\Override]
public function getResizeConfiguration(): ResizeConfiguration
{
    if (!\ATTACHMENT_IMAGE_AUTOSCALE) {
        // The resizing has been disabled through the options.
        return ResizeConfiguration::unbounded();
    }

    return new ResizeConfiguration(
        \ATTACHMENT_IMAGE_AUTOSCALE_MAX_WIDTH,
        \ATTACHMENT_IMAGE_AUTOSCALE_MAX_HEIGHT,
        ResizeFileType::fromString(\ATTACHMENT_IMAGE_AUTOSCALE_FILE_TYPE),
        \ATTACHMENT_IMAGE_AUTOSCALE_QUALITY
    );
}
```

The `ResizeFileType` controls the output format of the resized image.
The available options are `jpeg` and `webp` but it is also possible to keep the existing image format.

### Adopting Files and Thumbnails

Files are associated with your object type after being uploaded but you possibly want to store the file id in your database table.
This is where `adopt(File $file, array $context): void` comes into play which notifies you of the successful upload of a file while providing the context that is used to upload the file in the first place.

Thumbnails are generated in a separate request for performance reasons and you are being notified through `adoptThumbnail(FileThumbnail $thumbnail): void`.
This is meant to allow you to track the thumbnail id in the database.

### Tracking Downloads

File downloads are handled through the `FileDownloadAction` which validates the requested file and permissions to download it.
Every time a file is being downloaded, `trackDownload(File $file): void` is invoked to allow you to update any counters.

Static images are served directly by the web server for performance reasons and it is not possible to track those accesses.

## Registering the File Processor

The file processor is registered as an object type for `com.woltlab.wcf.file` through the `objectType.xml`:

```xml
<type>
	<name>com.woltlab.wcf.attachment</name>
	<definitionname>com.woltlab.wcf.file</definitionname>
	<classname>wcf\system\file\processor\AttachmentFileProcessor</classname>
</type>
```

# `FileProcessorFormField`

It is highly recommended that you take advantage of the existing form builder field `FileProcessorFormField`.
The integration with the form builder enables you to focus on the file processing and does not require you to manually handle the integration of the upload field.

TODO: Explain the usage.

# Implementing an Unmanaged File Upload

If you cannot use or want to use the existing form builder implementation you can still implement the UI yourself following this guide.

## Creating the Context for New Files

The HTML element for the file upload is generated through the helper method `FileProcessor::getHtmlElement()` that expects a reference to your `IFileProcessor` as well as a context for new files.

The context is an array with arbitrary values that will be provided to your `IFileProcessor` when processing uploaded files.
You can provide anything you need in order to recognize what the file belongs to, for example, an identifier, object ids, et cetera.

```php
final class ExampleFileProcessor extends AbstractFileProcessor {
    public function toHtmlElement(string $someIdentifier, int $someObjectID): string {
        return FileProcessor::getInstance()->getHtmlElement(
            $this,
            [
                'identifier' => $someIdentifier,
                'objectID' => $someObjectID,
            ],
        );
    }
}
```

This code will generate a `<woltlab-core-file-upload>` HTML element that can be inserted anywhere on the page.
You do not need to initialize any extra JavaScript to make the element work, it will be initialized dynamically.

## Lifecycle of Uploaded Files

Any file that passes the pre-upload validation will be uploaded to the server.
This will trigger the `uploadStart` event on the `<woltlab-core-file-upload>` element, exposing a `<woltlab-core-file>` element as the only detail.

It is your responsibility to insert this element at a location of your choice on the page, it represents the upload progress, reports any errors when uploading the file and shows the uploaded file on completion.

The `<woltlab-core-file>` element exposes the `.ready` property that is a Promise representing the state of the upload.
A successful upload will resolve the promise without any value, you can then access the file id through the `.fileId` property.

A failed upload will reject the `.ready` promise without any value, instead you can retrieve the error through the `.apiError` property if you want to further process it.
The UI is automatically updated with the error message, you only need to handle the `.apiError` property if you need to inspect the root cause.

## Deleting a File

You can delete a file from the UI by invoking `deleteFile()` from `WoltLabSuite/Core/Api/Files` which takes the value of `.fileId`.

## Render a Previously Uploaded File

You can render the `<woltlab-core-file>` element through `File::toHtmlElement()`.
This method accepts an optional list of meta data that is serialized to JSON and exposed on the `data-meta-data` property.

The `.ready` promise exists for these files too and will resolve immediately.
