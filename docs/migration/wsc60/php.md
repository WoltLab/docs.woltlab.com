# Migrating from WoltLab Suite 6.0 - PHP

## HtmlUpcastProcessor

The `HtmlUpcastProcessor` is intended for use in all forms where the CKEditor5 is available as an input field. Its
primary purpose is to inject necessary information into the HTML to ensure correct rendering within the editor. It is
important to note that the `HtmlUpcastProcessor` is not designed for content storage and should be utilized within
the `assignVariables` method.

#### Example

```php
namespace wcf\form;

use wcf\system\html\upcast\HtmlUpcastProcessor;

class MyForm extends AbstractForm {

    public string $messageObjectType = ''; // object type of `com.woltlab.wcf.message`
    public string $text = '';

    public function assignVariables() {
        parent::assignVariables();

        $upcastProcessor = new HtmlUpcastProcessor();
        $upcastProcessor->process($this->text ?? '', $this->messageObjectType, 0);
        WCF::getTPL()->assign('text', $upcastProcessor->getHtml());
    }
}
```

## RSS Feeds

A [new API](../../php/api/rss_feeds.md) for the output of content as an RSS feed has been introduced.

## ACP Menu Items

A [new API](../../package/acp-menu-items.md) for adding ACP menu items has been introduced. The previous option of adding menu entries via PIP is still supported, but is to be discontinued in the long term.

## User Activity Events

The user activity events have been redesigned for a modern look and better user experience.

This includes the following changes:

- The title now includes the author's name and forms a complete sentence. Example: `<strong>{$author}</strong> replied to a comment by <strong>{$commentAuthor}</strong> on article <strong>{$article->getTitle()}</strong>.`
- The title no longer contains links.
- Keywords in the title are highlighted in bold (e.g. author's name, topic title).
- The description is a simple text version of the content (no formatting) truncated to 500 characters.
- The event as a whole can be linked with a link that leads to the content (the entire area is clickable).

The changes are backwards compatible, but we recommend to apply them for a uniform user experience.

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
```

## Box Configuration

The Methods `wcf\system\box\BoxHandler::createBoxCondition()` and `wcf\system\box\BoxHandler::addBoxToPageAssignments()` were used for the configuration of boxes during package installation. These methods were deprecated with version 6.1, as they led to an initialization of the box handler and can therefore cause undesirable side effects.

The new commands `wcf\system\box\command\CreateBoxCondition` and `wcf\system\box\command\CreateBoxToPageAssignments` can be used instead.

Example:

```php
(new \wcf\system\box\command\CreateBoxCondition(
    'boxIdentifier',
    'conditionDefinition',
    'conditionObjectType',
    ['parameter' => 12345]
))();

(new \wcf\system\box\command\CreateBoxToPageAssignments(
    'boxIdentifier',
    ['pageIdentifier']
))();
```

## PSR-14 Events

The old practice of placing events where they are used is somewhat inconsistent and worst of all highly intransparent for discovery purposes. WoltLab Suite 6.1 therefore introduces a unified directory structure grouped by the app namespace.

All PSR-14 events now use the new `event` namespace (located under `lib/event`). See the [PSR-14 event documentation](../../php/api/events.md) for details.

The changes are backwards compatible, the old namespaces can still be used.

## Comment Backend

The backend of the comment system has been revised and is now based on the new RPC controllers and commands.
The previous backend (the methods of `CommentAction` and `CommentResponseAction`) remains for backward compatibility reasons, but has been deprecated.
If you do not interact directly with the backend, no changes are usually required. See [WoltLab/WCF#5944](https://github.com/WoltLab/WCF/pull/5944) for more details.

## Enable the Sandbox for Templates Inside of BBCodes

BBCodes can appear in a lot of different places and assigning template variables through `WCF::getTPL()->assign()` can cause variables from the ambient enviroment to be overwritten.
You should not use this method in BBCodes at all and instead pass the variables as the third argument to `WCF::getTPL()->fetch()` as well as enabling the sandbox.

```php
// Before
WCF::getTPL()->assign([
    'foo' => 'bar',
]);
return WCF::getTPL()->fetch('templateName', 'application');

// After
return WCF::getTPL()->fetch('templateName', 'application', [
    'foo' => 'bar',
], true);
```

See [WoltLab/WCF#5910](https://github.com/WoltLab/WCF/issues/5910) for more details.

## Creating Categories Based on the Formbuilder

A new basic implementation based on the FormBuilder for creating and editing categories has been introduced.
The old implementation (`AbstractCategoryAddForm`) remains for backward compatibility reasons, but has been deprecated.

Usage (form for creating categories):

```php
class FooBarCategoryAddForm extends CategoryAddFormBuilderForm {
    public string $objectTypeName = 'foo.bar.category';
}
```

Usage (form for editing categories):

```php
class FooBarCategoryEditForm extends FooBarCategoryAddForm {
    public $formAction = 'edit';
}
```

See [WoltLab/WCF#5657](https://github.com/WoltLab/WCF/pull/5657
) for more details. 

## Loading embedded objects for quotes

When saving a quote, it is necessary to load embedded objects before adding the quote to the `MessageQuoteManager`.
This is to ensure that the embedded objects are displayed correctly in the quote preview.

```PHP
public class FooBarAction extends AbstractDatabaseObjectAction implements IMessageQuoteAction
{   
    private function loadEmbeddedObjects(): void
    {
        if ($this->object->hasEmbeddedObjects) {
            ObjectTypeCache::getInstance()
                ->getObjectTypeByName('com.woltlab.wcf.attachment.objectType', 'foo.bar.attachment')
                ->getProcessor()
                ->cacheObjects([$this->object->objectID]);
            MessageEmbeddedObjectManager::getInstance()->loadObjects(
                'foo.bar.message',
                [$this->object->objectID]
            );
        }
    }

    public function saveFullQuote()
    {
        $this->loadEmbeddedObjects();

        $quoteID = MessageQuoteManager::getInstance()->addQuote(
            'foo.bar.message',
            $this->object->parentObjectID,
            $this->object->objectID,
            $this->object->getExcerpt(),
            $this->object->getMessage()
        );
        …
    }

    public function saveQuote()
    {
        $this->loadEmbeddedObjects();

        $quoteID = MessageQuoteManager::getInstance()->addQuote(
            'foo.bar.message',
            $this->object->parentObjectID,
            $this->object->objectID,
            $this->parameters['message'],
            false
        );
        …
    }
}
```

## Migration to `FileProcessorFormField`

Previously, the `UploadFormField` class was used to create file upload fields in forms.
Now, the new `FileProcessorFormField` should be used,
which separates file validation and processing into a dedicated class, the `IFileProcessor`.

Only the fileID or several fileIDs now need to be saved in the database.
These should have a foreign key to `wcf1_file.fileID`.

The previously required function (`getFooUploadFiles`) to get `UploadFile[]` is no longer needed and can be removed.

### Example

In this example, the `Foo` object will store the `imageID` of the uploaded file.

#### Example using `FileProcessorFormField`

The form field now provides information about which `IFileProcessor` should be used for the file upload,
by specifying the object type of `com.woltlab.wcf.file`.

```PHP
final class FooAddForm extends AbstractFormBuilderForm
{
    #[\Override]
    protected function createForm(): void
    {
        parent::createForm();

        $this->form->appendChildren([
            FormContainer::create('imageContainer')
                ->appendChildren([
                    FileProcessorFormField::create('imageID')
                        ->singleFileUpload()
                        ->required()
                        ->objectType('foo.bar.image')
                ]),
        ]);
    }
}
```

#### Example for implementing an `IFileProcessor`

The `objectID` in the `$context` comes from the form and corresponds to the objectID of the `FooAddForm::$formObject`.

```PHP
final class FooImageFileProcessor extends AbstractFileProcessor
{    
    #[\Override]
    public function acceptUpload(string $filename, int $fileSize, array $context): FileProcessorPreflightResult
    {
        if (isset($context['objectID'])) {
            $foo = $this->getFoo($context);
            if ($foo === null) {
                return FileProcessorPreflightResult::InvalidContext;
            }

            if (!$foo->canEdit()) {
                return FileProcessorPreflightResult::InsufficientPermissions;
            }
        } elseif (!WCF::getSession()->getPermission('foo.bar.canAdd')) {
            return FileProcessorPreflightResult::InsufficientPermissions;
        }

        if ($fileSize > $this->getMaximumSize($context)) {
            return FileProcessorPreflightResult::FileSizeTooLarge;
        }

        if (!FileUtil::endsWithAllowedExtension($filename, $this->getAllowedFileExtensions($context))) {
            return FileProcessorPreflightResult::FileExtensionNotPermitted;
        }

        return FileProcessorPreflightResult::Passed;
    }

    #[\Override]
    public function getMaximumSize(array $context): ?int
    {
        return WCF::getSession()->getPermission('foo.bar.image.maxSize');
    }

    #[\Override]
    public function getAllowedFileExtensions(array $context): array
    {
        return \explode("\n", WCF::getSession()->getPermission('foo.bar.image.allowedFileExtensions'));
    }

    #[\Override]
    public function canAdopt(File $file, array $context): bool
    {
        $fooFromContext = $this->getFoo($context);
        $fooFromCoreFile = $this->getFooByFile($file);

        if ($fooFromCoreFile === null) {
            return true;
        }

        if ($fooFromCoreFile->fooID === $fooFromContext->fooID) {
            return true;
        }

        return false;
    }

    #[\Override]
    public function adopt(File $file, array $context): void
    {
        $foo = $this->getFoo($context);
        if ($foo === null) {
            return;
        }

        (new FooEditor($foo))->update([
            'imageID' => $file->fileID,
        ]);
    }

    #[\Override]
    public function canDelete(File $file): bool
    {
        $foo = $this->getFooByFile($file);
        if ($foo === null) {
            return WCF::getSession()->getPermission('foo.bar.canAdd');
        }

        return false;
    }

    #[\Override]
    public function canDownload(File $file): bool
    {
        $foo = $this->getFooByFile($file);
        if ($foo === null) {
            return WCF::getSession()->getPermission('foo.bar.canAdd');
        }

        return $foo->canRead();
    }

    #[\Override]
    public function delete(array $fileIDs, array $thumbnailIDs): void
    {
        $fooList = new FooList();
        $fooList->getConditionBuilder()->add('imageID IN (?)', [$fileIDs]);
        $fooList->readObjects();

        if ($fooList->count() === 0) {
            return;
        }

        (new FooAction($fooList->getObjects(), 'delete'))->executeAction();
    }

    #[\Override]
    public function getObjectTypeName(): string
    {
        return 'foo.bar.image';
    }
    
    #[\Override]
    public function countExistingFiles(array $context): ?int
    {
        $foo = $this->getFoo($context);
        if ($foo === null) {
            return null;
        }

        return $foo->imageID === null ? 0 : 1;
    }
    
    private function getFoo(array $context): ?Foo
    {
        // extract foo from context
    }
    
    private function getFooByFile(File $file): ?Foo
    {
        // search foo in database by given file
    }
}
```

### Migrating existing files

To insert existing files into the upload pipeline,
a `RebuildDataWorker` should be used which calls `FileEditor::createFromExistingFile()`.

#### Example for a `RebuildDataWorker`

```PHP
final class FooRebuildDataWorker extends AbstractLinearRebuildDataWorker
{
    /**
     * @inheritDoc
     */
    protected $objectListClassName = FooList::class;

    /**
     * @inheritDoc
     */
    protected $limit = 100;

    #[\Override]
    public function execute()
    {
        parent::execute();

        $fooToFileID = [];
        $defunctFileIDs = [];

        foreach ($this->objectList as $foo) {
            if ($foo->imageID !== null) {
                continue;
            }

            $file = FileEditor::createFromExistingFile(
                $foo->getLocation(),
                $foo->getFilename(),
                'foo.bar.image'
            );

            if ($file === null) {
                $defunctFileIDs[] = $foo->fooID;
                continue;
            }

            $fooToFileID[$foo->fooID] = $file->fileID;
        }

        $this->saveFileIDs($fooToFileID);
        // disable or delete defunct foo objects
    }

    /**
     * @param array<int,int> $fooToFileID
     */
    private function saveFileIDs(array $fooToFileID): void
    {
        // store fileIDs in database
    }
}
```

See [WoltLab/WCF#5911](https://github.com/WoltLab/WCF/pull/5951) for more details.
