---
title: Form Builder
sidebar: sidebar
permalink: php_api_form_builder.html
folder: php/api
---

{% include callout.html content="Form builder is only available since WoltLab Suite Core 5.2." type="info" %}

{% include callout.html content="The [migration guide for WoltLab Suite Core 5.2](migration_wsc-31_form-builder.html) provides some examples of how to migrate existing forms to form builder that can also help in understanding form builder if the old way of creating forms is familiar." type="info" %}


## Advantages of Form Builder

WoltLab Suite 5.2 introduces a new powerful way of creating forms: form builder.
Before taking a closer look at form builder, let us recap how forms are created in previous versions:
In general, for each form field, there is a corresponding property of the form's PHP class whose value has to be read from the request data, validated, and passed to the database object action to store the value in a database table.
When editing an object, the property's value has to be set using the value of the corresponding property of the edited object.
In the form's template, you have to write the `<form>` element with all of its children: the `<section>` elements, the `<dl>` elements, and, of course, the form fields themselves.
In summary, this way of creating forms creates much duplicate or at least very similar code and makes it very time consuming if the structure of forms in general or a specific type of form field has to be changed.

Form builder, in contrast, relies on PHP objects representing each component of the form, from the form itself down to each form field.
This approach makes creating forms as easy as creating some PHP objects, populating them with all the relevant data, and one line of code in the template to print the form.


## Form Builder Components

Form builder consists of several components that are presented on the following pages:

1. [Structure of form builder](php_api_form_builder-structure.html)
1. [Form validation and form data](php_api_form_builder-validation_data.html)
1. [Form node dependencies](php_api_form_builder-dependencies.html)

{% include callout.html content="In general, form builder provides default implementation of interfaces by providing either abstract classes or traits.
  It is expected that the interfaces are always implemented using these abstract classes and traits!
  This way, if new methods are added to the interfaces, default implementations can be provided by the abstract classes and traits without causing backwards compatibility problems." type="warning" %}


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
