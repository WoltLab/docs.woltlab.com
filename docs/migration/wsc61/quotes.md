# Migrating from WoltLab Suite 6.1 – Quotes

In the upcoming version of WoltLab Suite, the handling of quotes has been revamped to enhance user experience.
Quotes are now stored client-side in the browser's local storage, allowing synchronization across browser tabs.

## Using the New Quote System

The interfaces `wcf\data\IMessageQuoteAction` and `wcf\system\message\quote\IMessageQuoteHandler` are no longer required to generate the quotes, and the implemented classes or functions can be completely removed.
Since the interface `IMessageQuoteHandler` has been deprecated, the ObjectType no longer requires any information about an associated class.

```XML
<?xml version="1.0" encoding="UTF-8"?>
<data xmlns="http://www.woltlab.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.woltlab.com http://www.woltlab.com/XSD/6.0/objectType.xsd">
	<import>
		<type>
			<name>com.woltlab.foo</name>
			<definitionname>com.woltlab.wcf.message.quote</definitionname>
		</type>
	</import>
</data>
```

The object that can be quoted:

- MUST implement the interface `wcf\data\IMessage`.
- SHOULD implement the interface `wcf\data\IEmbeddedMessageObject`.

Using of `WoltLabSuite/Core/Ui/Message/Quote` is no longer required and only `WoltLabSuite/Core/Component/Quote/Message::registerContainer()` should be used so that a message can be quoted

```smarty
<!-- Previous -->
<script data-relocate="true">
    $(function() {
        require(["WoltLabSuite/Core/Ui/Message/Quote"], ({ UiMessageQuote }) => {
            {include file='shared_messageQuoteManager' wysiwygSelector='text' supportPaste=true}
            
            new UiMessageQuote($quoteManager, "wcf\\data\\foo\\Foo", "com.woltlab.foo", ".message", ".messageBody", ".messageBody > .messageText", true);
        });
    });
</script>

<!-- Use instead -->
<script data-relocate="true">
    require(["WoltLabSuite/Core/Component/Quote/Message"], ({ registerContainer }) => {
        registerContainer(".message", ".messageBody", "wcf\\data\\foo\\Foo", "com.woltlab.foo");
    });
</script>
```

## Adjustments to the forms

The functions `MessageQuoteManager::readFormParameters()` and `MessageQuoteManager::saved()` must be called both in the form for creating and editing the entry.
This is necessary if the text input does support quotes. Regardless of whether the entry itself can be quoted later or not.

```PHP
use wcf\system\message\quote\MessageQuoteManager;

class FooAddForm extends \wcf\form\MessageForm {

    #[\Override]
    public function readFormParameters()
    {
        parent::readFormParameters();

        // …

        // Read the quotes that are to be deleted after the message has been successfully saved
        MessageQuoteManager::getInstance()->readFormParameters();
    }

    #[\Override]
    protected function saved()
    {
        parent::saved();

        // …

        // Save the used quotes so that they're deleted on the client with the next request
        MessageQuoteManager::getInstance()->saved();
    }
}
```

### Pre-filling of text

The automatic pre-filling of text when creating a new entry with previously marked quotes is no longer supported and has been removed in the new version.

### Changes to the FormBuilder

The functions `WysiwygFormContainer::quoteData()` and `WysiwygFormField::quoteData()` are no longer required, it is sufficient to use the function `supportQuotes()`.
The functions `MessageQuoteManager::readFormParameters()` and `MessageQuoteManager::saved()` are called by `WysiwygFormField` and don't need to be called separately.
