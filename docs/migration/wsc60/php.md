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

* The title now includes the author's name and forms a complete sentence. Example: `<strong>{$author}</strong> replied to a comment by <strong>{$commentAuthor}</strong> on article <strong>{$article->getTitle()}</strong>.`
* The title no longer contains links.
* Keywords in the title are highlighted in bold (e.g. author's name, topic title).
* The description is a simple text version of the content (no formatting) truncated to 500 characters.
* The event as a whole can be linked with a link that leads to the content (the entire area is clickable).

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
