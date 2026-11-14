# File Uploads

Uploading files is handled through the unified upload pipeline introduced in WoltLab Suite 6.1.
The API covers the upload process, storage, thumbnail generation and serving of files to the browser.
Attachments are implemented as an extra layer on top of the upload pipeline.

The most common use cases are the attachment system that relies on the WYSIWYG editor as well as the `FileProcessorFormField`.

## Provide the `IFileProcessor`

At the very core of each uploadable type is an implementation of `IFileProcessor` that handles the validation and handling of new files.

It is strongly recommended to derive from `AbstractFileProcessor` that makes it easy to opt out of some extra features and will provide backwards compatibility with new features.

### Important Methods and Features

Please refer to the documentation in `IFileProcessor` for an explanation of methods that are not explained here.

#### Thumbnails

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

#### Resizing Images Before Uploading

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

#### Rejecting Uploaded Files

`validateUpload(File $file): void` is invoked once the file has been uploaded completely, before it is adopted.
Throw a `UserInputException` to reject the file, which deletes the file and shows the phrase `wcf.upload.validation.error.{type}` to the user.
Starting with WoltLab Suite 6.3, a generic message is shown instead if there is no such phrase.
The client looks the phrase up, so preload it through the `wcf\event\language\PreloadPhrasesCollecting` event; a phrase that has not been preloaded counts as missing.

Starting with WoltLab Suite 6.3, throw `wcf\system\file\processor\exception\UploadRejected` instead to tell the user why the file was rejected, for example, which dimensions the image must have.
The first parameter is a code that identifies the reason for clients that handle the error themselves, the second one is the message.
`UploadRejected::fromPhrase()` renders a phrase in the language of the active user and uses it as the message.

```php
#[\Override]
public function validateUpload(File $file): void
{
    if ($file->isImage() && ($file->width < 400 || $file->height < 300)) {
        throw UploadRejected::fromPhrase('minimumDimensions', 'wcf.example.upload.error.minimumDimensions', [
            'width' => 400,
            'height' => 300,
        ]);
    }
}
```

The message is plain text, the client never interprets it as HTML.
`fromPhrase()` converts the rendered phrase to plain text: markup of the phrase is removed and variables inserted with `{$var}`, such as a filename, appear exactly as they are.
Variables inserted with `{@$var}` are not escaped, so tags inside their values are removed as well.
A message passed to the constructor must not be HTML-encoded, `&amp;` is shown as is.
`UploadRejected` extends `UserInputException`, without a message it behaves like a `UserInputException` with the same code.

#### Adopting Files and Thumbnails

Files are associated with your object type after being uploaded but you possibly want to store the file id in your database table.
This is where `adopt(File $file, array $context): void` comes into play which notifies you of the successful upload of a file while providing the context that is used to upload the file in the first place.

Thumbnails are generated in a separate request for performance reasons and you are being notified through `adoptThumbnail(FileThumbnail $thumbnail): void`.
This is meant to allow you to track the thumbnail id in the database.

#### Tracking Downloads

File downloads are handled through the `FileDownloadAction` which validates the requested file and permissions to download it.
Every time a file is being downloaded, `trackDownload(File $file): void` is invoked to allow you to update any counters.

Static images are served directly by the web server for performance reasons and it is not possible to track those accesses.

### Registering the File Processor

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

Please see documentation for [FileProcessorFormField](form_builder/form_fields.md#fileprocessorformfield) to learn more.

## Implementing an Unmanaged File Upload

If you cannot use or want to use the existing form builder implementation you can still implement the UI yourself following this guide.

### Creating the Context for New Files

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
Starting with WoltLab Suite 6.3, files that are handed to `uploadFiles()` or selected through the element before it has been initialized are handed to the upload pipeline once it is, in the order in which they were handed over.

### Lifecycle of Uploaded Files

Any file that passes the pre-upload validation will be uploaded to the server.
This will trigger the `uploadStart` event on the `<woltlab-core-file-upload>` element, exposing a `<woltlab-core-file>` element as the only detail.

It is your responsibility to insert this element at a location of your choice on the page, it represents the upload progress, reports any errors when uploading the file and shows the uploaded file on completion.

The `.sourceFile` property (WoltLab Suite 6.3+) holds the `File` object that was handed over for upload, for example, through `uploadFiles()`.
Use it to map the `<woltlab-core-file>` element to the file it belongs to, because images can be resized or cropped before the upload, changing the name, size and type of the uploaded file.

The `<woltlab-core-file>` element exposes the `.ready` property that is a Promise representing the state of the upload.
A successful upload will resolve the promise without any value, you can then access the file id through the `.fileId` property.

A failed upload will reject the `.ready` promise without any value, instead you can retrieve the error through the `.apiError` property if you want to further process it.
The UI is automatically updated with the error message, you only need to handle the `.apiError` property if you need to inspect the root cause.
Starting with WoltLab Suite 6.3, failures without a response from the server, such as connection errors, reject `.ready` without an `.apiError`, these are reported through the regular error dialog for failed requests.

A file rejected by `validateUpload()` fails with a validation error whose `param` is `validation` and whose `code` is the type of the `UserInputException`, the server has discarded the upload at this point.
Starting with WoltLab Suite 6.3, a file that is detected as a GIF, JPEG, PNG or WebP image but whose dimensions cannot be read is rejected the same way with the code `noImage`, before `validateUpload()` is invoked.
The `message` of the validation error is the message of an `UploadRejected` and an empty string otherwise (WoltLab Suite 6.3+).
The UI shows this message, otherwise the phrase `wcf.upload.validation.error.{code}` if it has been preloaded, otherwise a generic message.

The file exists once the final chunk has been processed, even if the thumbnails cannot be generated afterwards.
Starting with WoltLab Suite 6.3, the `.ready` promise is resolved in this case and the element lists no thumbnails, although the server may have generated some of them.
The error is reported through the regular error dialog for failed requests.

### Aborting an Upload

Starting with WoltLab Suite 6.3, an upload can be stopped by invoking `.abort()` on the `<woltlab-core-file>` element.
The chunks that have already been uploaded are discarded on the server, an abort that arrives after the final chunk has been sent deletes the resulting file instead.
Once the abort has been processed, `.ready` is rejected without an `.apiError` and `.isAborted()` returns `true`, which distinguishes it from a connection error.

Calling `.abort()` has no effect after `.ready` has settled.
If the uploaded file cannot be deleted, the upload completes regularly and `.ready` is resolved.

Starting with WoltLab Suite 6.3, uploads that are never completed are discarded by the daily clean up 24 hours after they were started.

### Deleting a File

You can delete a file from the UI by invoking `deleteFile()` from `WoltLabSuite/Core/Api/Files` which takes the value of `.fileId`.

### Render a Previously Uploaded File

You can render the `<woltlab-core-file>` element through `File::toHtmlElement()`.
This method accepts an optional list of meta data that is serialized to JSON and exposed on the `data-meta-data` property.

The `.ready` promise exists for these files too and will resolve immediately.
