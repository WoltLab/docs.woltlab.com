# Form Builder

WoltLab Suite includes a powerful way of creating forms: Form Builder.
Form builder allows you to easily define all the fields and their constraints and interdependencies within PHP with full IDE support.
It will then automatically generate the necessary HTML with full interactivity to render all the fields and also validate the fields’ contents upon submission.

!!! info "The [migration guide for WoltLab Suite Core 5.2](../../../migration/wsc31/form-builder.md) provides some examples of how to migrate existing forms to form builder that can also help in understanding form builder if the old way of creating forms is familiar."

## Form Builder Components

Form builder consists of several components that are presented on the following pages:

1. [Structure of form builder](structure.md)
1. [Form validation and form data](validation_data.md)
1. [Form node dependencies](dependencies.md)

!!! warning "In general, form builder provides default implementation of interfaces by providing either abstract classes or traits. It is expected that the interfaces are always implemented using these abstract classes and traits! This way, if new methods are added to the interfaces, default implementations can be provided by the abstract classes and traits without causing backwards compatibility problems."


## `AbstractFormBuilderForm`

To make using form builder easier, `AbstractFormBuilderForm` extends `AbstractForm` and provides most of the code needed to set up a form (of course without specific fields, those have to be added by the concrete form class), like reading and validating form values and using a database object action to use the form data to create or update a database object.

In addition to the existing methods inherited by `AbstractForm`, `AbstractFormBuilderForm` provides the following methods:

- `buildForm()` builds the form in the following steps:
  
  1. Call `AbtractFormBuilderForm::createForm()` to create the `IFormDocument` object and add the form fields.
  2. Call `IFormDocument::build()` to build the form.
  3. Call `AbtractFormBuilderForm::finalizeForm()` to finalize the form like adding dependencies.
  
  Additionally, between steps 1 and 2 and after step 3, the method provides two events, `createForm` and `buildForm` to allow plugins to register event listeners to execute additional code at the right point in time.
- `createForm()` creates the `FormDocument` object and sets the form mode.
  Classes extending `AbstractFormBuilderForm` have to override this method (and call `parent::createForm()` as the first line in the overridden method) to add concrete form containers and form fields to the bare form document.
- `finalizeForm()` is called after the form has been built and the complete form hierarchy has been established.
  This method should be overridden to add dependencies, for example.
- `setFormAction()` is called at the end of `readData()` and sets the form document’s action based on the controller class name and whether an object is currently edited.
- If an object is edited, at the beginning of `readData()`, `setFormObjectData()` is called which calls `IFormDocument::loadValuesFromObject()`.
  If values need to be loaded from additional sources, this method should be used for that.

`AbstractFormBuilderForm` also provides the following (public) properties:

- `$form` contains the `IFormDocument` object created in `createForm()`.
- `$formAction` is either `create` (default) or `edit` and handles which method of the database object is called by default (`create` is called for `$formAction = 'create'` and `update` is called for `$formAction = 'edit'`) and is used to set the value of the `action` template variable.
- `$formObject` contains the `IStorableObject` if the form is used to edit an existing object.
  For forms used to create objects, `$formObject` is always `null`.
  Edit forms have to manually identify the edited object based on the request data and set the value of `$formObject`. 
- `$objectActionName` can be used to set an alternative action to be executed by the database object action that deviates from the default action determined by the value of `$formAction`.
- `$objectActionClass` is the name of the database object action class that is used to create or update the database object.

Example:

```php
<?php

namespace wcf\acp\form;

use wcf\form\AbstractFormBuilderForm;
use wcf\system\form\builder\field\BooleanFormField;
use wcf\system\form\builder\field\TextFormField;
use wcf\system\form\builder\field\validation\FormFieldValidationError;
use wcf\system\form\builder\field\validation\FormFieldValidator;

class FooAddForm extends AbstractFormBuilderForm
{
    /**
     * @inheritDoc
     */
    public $objectActionClass = FooAction::class;

    #[\Override]
    protected function createForm()
    {
        parent::createForm();

        $this->form->appendChildren([
            TextFormField::create('name')
                ->label('wcf.foo.name')
                ->description('wcf.foo.name.description')
                ->required()
                ->maximumLength(255)
                ->addValidator(new FormFieldValidator('notFoo', function (TextFormField $formField) {
                    if ($formField->getValue() === 'foo') {
                        $formField->addValidationError(
                            new FormFieldValidationError(
                                'isFoo',
                                'wcf.foo.name.error.isFoo'
                            )
                        );
                    }
                })),
            BooleanFormField::create('isCool')
                ->label('wcf.foo.isCool')
                ->value(true)
        ]);
    }
}
```

## `DialogFormDocument`

Form builder forms can also be used in dialogs.
For such forms, `DialogFormDocument` should be used which provides the additional methods `cancelable($cancelable = true)` and `isCancelable()` to set and check if the dialog can be canceled.
If a dialog form can be canceled, a cancel button is added.

If the dialog form is fetched via an AJAX request, `IFormDocument::ajax()` has to be called.
AJAX forms are registered with `WoltLabSuite/Core/Form/Builder/Manager` which also supports getting all of the data of a form via the `getData(formId)` function.
The `getData()` function relies on all form fields creating and registering a `WoltLabSuite/Core/Form/Builder/Field/Field` object that provides the data of a specific field.

To make it as easy as possible to work with AJAX forms in dialogs, `WoltLabSuite/Core/Form/Builder/Dialog` (abbreviated as `FormBuilderDialog` from now on) should generally be used instead of `WoltLabSuite/Core/Form/Builder/Manager` directly. 
The constructor of `FormBuilderDialog` expects the following parameters:

- `dialogId`: id of the dialog element
- `className`: PHP class used to get the form dialog (and save the data if `options.submitActionName` is set)
- `actionName`: name of the action/method of `className` that returns the dialog; the method is expected to return an array with `formId` containg the id of the returned form and `dialog` containing the rendered form dialog
- `options`: additional options:
  - `actionParameters` (default: empty): additional parameters sent during AJAX requests
  - `destroyOnClose` (default: `false`): if `true`, whenever the dialog is closed, the form is destroyed so that a new form is fetched if the dialog is opened again
  - `dialog`: additional dialog options used as `options` during dialog setup
  - `onSubmit`: callback when the form is submitted (takes precedence over `submitActionName`)
  - `submitActionName` (default: not set): name of the action/method of `className` called when the form is submitted

The three public functions of `FormBuilderDialog` are:

- `destroy()` destroys the dialog, the form, and all of the form fields.
- `getData()` returns a Promise that returns the form data.
- `open()` opens the dialog.

Example:

```javascript
require(['WoltLabSuite/Core/Form/Builder/Dialog'], function(FormBuilderDialog) {
	var dialog = new FormBuilderDialog(
		'testDialog',
		'wcf\\data\\test\\TestAction',
		'getDialog',
		{
			destroyOnClose: true,
			dialog: {
				title: 'Test Dialog'
			},
			submitActionName: 'saveDialog'
		}
	);
	
	elById('testDialogButton').addEventListener('click', function() {
		dialog.open();
	});
});
```
