---
title: Form Validation and Form Data
sidebar: sidebar
permalink: php_api_form_builder-validation_data.html
folder: php/api/formBuilder
parent: php_api_form_builder
---

## Form Validation

Every form field class has to implement `IFormField::validate()` according to their internal logic of what constitutes a valid value.
If a certain constraint for the value is no met, a form field validation error object is added to the form field.
Form field validation error classes have to implement the interface `IFormFieldValidationError`.

In addition to intrinsic validations like checking the length of the value of a text form field, in many cases, there are additional constraints specific to the form like ensuring that the text is not already used by a different object of the same database object class.
Such additional validations can be added to (and removed from) the form field via implementations of the `IFormFieldValidator` interface.


### `IFormFieldValidationError` / `FormFieldValidationError`

`IFormFieldValidationError` requires the following methods:

- `__construct($type, $languageItem = null, array $information = [])` creates a new validation error object for an error with the given type and message stored in the given language items.
  The information array is used when generating the error message.
- `getHtml()` returns the HTML element representing the error that is shown to the user.
- `getMessage()` returns the error message based on the language item and information array given in the constructor.
- `getInformation()` and `getType()` are getters for the first and third parameter of the constructor.

`FormFieldValidationError` is a default implementation of the interface that shows the error in an `small.innerError` HTML element below the form field.

Form field validation errors are added to form fields via the `IFormField::addValidationError(IFormFieldValidationError $error)` method.


### `IFormFieldValidator` / `FormFieldValidator`

`IFormFieldValidator` requires the following methods:

- `__construct($id, callable $validator)` creates a new validator with the given id that passes the validated form field to the given callable that does the actual validation.
  `static validateId($id)` is used to check if the given id is valid.
- `__invoke(IFormField $field)` is used when the form field is validated to execute the validator.
- `getId()` returns the id of the validator.

`FormFieldValidator` is a default implementation of the interface.

Form field validators are added to form fields via the `addValidator(IFormFieldValidator $validator)` method.


## Form Data

After a form is successfully validated, the data of the form fields (returned by `IFormDocument::getData()`) have to be extracted which is the job of the `IFormDataHandler` object returned by `IFormDocument::getDataHandler()`.
Form data handlers themselves, however, are only iterating through all `IFormDataProcessor` instances that have been registered with the data handler.


### `IFormDataHandler` / `FormDataHandler`

`IFormDataHandler` requires the following methods:

- `addProcessor(IFormDataProcessor $processor)` adds a new data processor to the data handler.
- `getFormData(IFormDocument $document)` returns the data of the given form by applying all registered data handlers on the form.
- `getObjectData(IFormDocument $document, IStorableObject $object)` returns the data of the given object which will be used to populate the form field values of the given form.

`FormDataHandler` is the default implementation of this interface and should also be extended instead of implementing the interface directly.


### `IFormDataProcessor` / `DefaultFormDataProcessor`

`IFormDataProcessor` requires the following methods:

- `processFormData(IFormDocument $document, array $parameters)` is called by `IFormDataHandler::getFormData()`.
  The method processes the given parameters array and returns the processed version.
- `processObjectData(IFormDocument $document, array $data, IStorableObject $object)` is called by `IFormDataHandler::getObjectData()`.
  The method processes the given object data array and returns the processed version.

When `FormDocument` creates its `FormDataHandler` instance, it automatically registers an `DefaultFormDataProcessor` object as the first data processor.
`DefaultFormDataProcessor` puts the save value of all form fields that are available and have a save value into `$parameters['data']` using the form field’s object property as the array key.

!!! warning "`IFormDataProcessor` should not be implemented directly. Instead, `AbstractFormDataProcessor` should be extended."

!!! info "All form data is put into the `data` sub-array so that the whole `$parameters` array can be passed to a database object action object that requires the actual database object data to be in the `data` sub-array."
 


### Additional Data Processors

#### `CustomFormDataProcessor`

As mentioned above, the data in the `data` sub-array is intended to directly create or update the database object with.
As these values are used in the database query directly, these values cannot contain arrays.
Several form fields, however, store and return their data in form of arrays.
Thus, this data cannot be returned by `IFormField::getSaveValue()` so that `IFormField::hasSaveValue()` returns `false` and the form field’s data is not collected by the standard `DefaultFormDataProcessor` object.

Instead, such form fields register a `CustomFormDataProcessor` in their `IFormField::populate()` method that inserts the form field value into the `$parameters` array directly.
This way, the relevant database object action method has access to the data to save it appropriately.

The constructor of `CustomFormDataProcessor` requires an id (that is primarily used in error messages during the validation of the second parameter) and callables for `IFormDataProcessor::processFormData()` and `IFormDataProcessor::processObjectData()` which are passed the same parameters as the `IFormDataProcessor` methods.
Only one of the callables has to be given, the other one then defaults to simply returning the relevant array unchanged.


#### `VoidFormDataProcessor`

Some form fields might only exist to toggle the visibility of other form fields (via dependencies) but the data of form field itself is irrelevant.
As `DefaultFormDataProcessor` collects the data of all form fields, an additional data processor in the form of a `VoidFormDataProcessor` can be added whose constructor `__construct($property, $isDataProperty = true)` requires the name of the relevant object property/form id and whether the form field value is stored in the `data` sub-array or directory in the `$parameters` array.
When the data processor is invoked, it checks whether the relevant entry in the `$parameters` array exists and voids it by removing it from the array.
