# Migrating from WoltLab Suite 6.1 – Quotes

In the upcoming version of WoltLab Suite, the handling of quotes has been revamped to enhance user experience.
Quotes are now stored client-side in the browser's local storage, allowing synchronization across browser tabs.

## Using the New Quote System

The interface `wcf\system\message\quote\IMessageQuoteHandler` has been modified to now only require the implementation of `getMessage()`.
This new method is responsible to fetch the message, perform any validation and load embedded objects whenver applicable.

All other methods previously implemented in quote handlers can be removed alongside with the methods required by the now deprecated interface `wcf\data\IMessageQuoteAction`.

`WoltLabSuite/Core/Ui/Message/Quote` is now deprecated and may only be used with legacy implementations relying on `wcf\data\IMessageQuoteAction`.
Updated implementations must use `WoltLabSuite/Core/Component/Quote/Message::registerContainer()` which requires only the container selectors and the name of the object type.

```smarty
<!-- Previous -->
<script data-relocate="true">
    $(function() {
        require(["WoltLabSuite/Core/Ui/Message/Quote"], ({ UiMessageQuote }) => {
            {include file='shared_messageQuoteManager' wysiwygSelector='text' supportPaste=true}
            
            new UiMessageQuote($quoteManager, "wcf\\data\\foo\\FooAction", "com.woltlab.foo", ".message", ".messageBody", ".messageBody > .messageText", true);
        });
    });
</script>

<!-- Use instead -->
<script data-relocate="true">
    require(["WoltLabSuite/Core/Component/Quote/Message"], ({ registerContainer }) => {
        registerContainer(".message", ".messageBody", "com.woltlab.foo");
    });
</script>
```

## Adjustments to the forms

The functions `MessageQuoteManager::readFormParameters()` and `MessageQuoteManager::saved()` must be called in the forms for creating and editing an entry.
These calls are required whenver a text input supports quotes, regardless of whether the entry itself can be quoted or not.

```php
use wcf\system\message\quote\MessageQuoteManager;

class FooAddForm extends \wcf\form\MessageForm {

    #[\Override]
    public function readFormParameters()
    {
        parent::readFormParameters();

        // …

        // Read the quotes that are to be deleted after the message has been successfully saved.
        MessageQuoteManager::getInstance()->readFormParameters();
    }

    #[\Override]
    protected function saved()
    {
        parent::saved();

        // …

        // Save the used quotes so that they're deleted on the client with the next request.
        MessageQuoteManager::getInstance()->saved();
    }
}
```

### Pre-filling of text

The automatic pre-filling of text when creating a new entry with previously marked quotes is no longer supported and has been removed in the new version.

### Changes to the FormBuilder

The functions `WysiwygFormContainer::quoteData()` and `WysiwygFormField::quoteData()` are no longer required, it is sufficient to use the function `supportQuotes()`.
The functions `MessageQuoteManager::readFormParameters()` and `MessageQuoteManager::saved()` are called by `WysiwygFormField` and don't need to be called separately.
