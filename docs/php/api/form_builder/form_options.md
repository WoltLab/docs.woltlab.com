# Form Options

Form options are the successor to the custom options that were previously used by the contact form and some other implementations.
They offer a foundation for use cases where administrators need to set up custon form fields for users to provide data to.
Built on top of the form builder they can be combined with any existing implementation by changing the base implementation to `AbstractFormOptionAddForm`.

This features ship with a wide area of premade form fields that have been fully configured with configuration settings and formatting options.
Additional form option types can be added through plugins utilizing the `wcf\event\form\option\SharedConfigurationFormFieldCollecting` event.

## `AbstractFormOptionAddForm`

This is the primary building block for integrating form options with any form builder form.
It offers a wide variety of customization options and does not force developers into a specific workflow but at the same time the process of storing the options and managing database columns is up to the implementers themselves.

The most important bit takes place in `createForm()` where you need to append a few form fields that are responsible for the entire logic.

1. `$this->getOptionTypeFormField()` returns the select field that is used to pick the form option type.
2. `$this->getSharedConfigurationFormFields()` returns an array of fields that are used to provide the form logic depending on the selected option type.

You MUST insert the configuration form fields immediately after the option type form field.

```php
#[\Override]
protected function createForm()
{
    parent::createForm();

    $this->form->appendChildren([
        /* Some fields that precede the type selection. */
        $this->getOptionTypeFormField(),
        ...$this->getSharedConfigurationFormFields(),
        /* Some fields that come after the type selection. */
    ]);
}
```

## `IFormOption`

Apart from the list of predefined form options that ship with the software, developers can add own implementations that will be available with every form that is build on top of `AbstractFormOptionAddForm`.
It offers a seamless integration into list views and grid views through `getFilterFormField()`, `renderFilterValue()` and `applyFilter()`.

For a full documentation of the required methods please see the `IFormOption` interface.

## Reference Implementation

The actual implementation in the frontend is a bit more difficult because the API only provides the fields and ways to interact with the field but does not predetermines how they are being used.

We highly suggest that you take a look at the implementation of the contact form because the API of the form options do not exist in a vacuum and only make sense in combination with everything around it.

- `wcf\acp\form\ContactOptionAddForm` manages adding of custom options for the contact form.
- `wcf\data\contact\option\ContactOption` implements `getFormOption()` and `getConfiguration()` to work with form options.
- `wcf\form\ContactForm` creates the form fields from the options in `getOptionFormFields()`.
- `wcf\command\contact\form\SubmitContactForm` converts the values from the user input to the formatted versions in `getFormattedOptionValues()` for use in HTML and plaintext emails.
