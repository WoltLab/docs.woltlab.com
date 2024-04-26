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

## `Psr15DialogForm`

Form builder forms can also be used in dialogs. For such forms, `Psr15DialogForm` should be used which provides the additional methods `validateRequest(ServerRequestInterface $request)` and `toResponse()` to enable processing of the form via an AJAX request.

Example:

```php
<?php

namespace wcf\action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use wcf\system\form\builder\field\validation\FormFieldValidationError;
use wcf\system\form\builder\field\validation\FormFieldValidator;
use wcf\system\form\builder\Psr15DialogForm;
use wcf\system\WCF;

final class FooAction implements RequestHandlerInterface
{
    /**
     * @inheritDoc
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $form = $this->getForm();

        if ($request->getMethod() === 'GET') {
            return $form->toResponse();
        } elseif ($request->getMethod() === 'POST') {
            $response = $form->validateRequest($request);
            if ($response !== null) {
                return $response;
            }

            $data = $form->getData()['data'];

            // process data
        } else {
            throw new \LogicException('Unreachable');
        }
    }

    private function getForm(): Psr15DialogForm
    {
        $form = new Psr15DialogForm(
            static::class,
            WCF::getLanguage()->get('wcf.foo.dialog.name')
        );
        $form->appendChildren([
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
        $form->build();

        return $form;
    }
}
```

On the client side, the dialog is loaded using the [dialog API](../../../javascript/components_dialog.md). A tuple is made available as a return, which provides the status of the successful form submission (`ok: boolean`) and the server-side return (`result: any`).

Example:

```javascript
require(['WoltLabSuite/Core/Component/Dialog'], async ({ dialogFactory }) => {
	const { ok, result } = await dialogFactory().usingFormBuilder().fromEndpoint('endpoint_url');

	if (ok) {
		// Form submission was successful
	}
});
```
