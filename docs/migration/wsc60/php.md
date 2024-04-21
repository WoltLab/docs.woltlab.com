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
