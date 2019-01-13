---
title: Form Builder Fields
sidebar: sidebar
permalink: php_api_form_builder-form_fields.html
folder: php/api/formBuilder
parent: php_api_form_builder
---

## Abstract Form Fields

The following form field classes cannot be instantiated directly because they are abstract, but they can/must be used when creating own form field classes. 


### `AbstractFormField`

`AbstractFormField` is the abstract default implementation of the `IFormField` interface and it is expected that every implementation of `IFormField` implements the interface by extending this class.


### `AbstractNumericFormField`

`AbstractNumericFormField` is the abstract implementation of a form field handling a single numeric value.
The class implements `IImmutableFormField`, `IMaximumFormField`, `IMinimumFormField`, `INullableFormField`, `IPlaceholderFormField` and `ISuffixedFormField`.
If the property `$integerValues` is `true`, the form field works with integer values, otherwise it works with floating point numbers.
The methods `step($step = null)` and `getStep()` can be used to set and get the step attribute of the `input` element.
The default step for form fields with integer values is `1`.
Otherwise, the default step is `any`.


## General Form Fields

The following form fields are general reusable fields without any underlying context.


### `BooleanFormField`

`BooleanFormField` is used for boolean (`0` or `1`, `yes` or `no`) values.
Objects of this class require a label.
The return value of `getSaveValue()` is the integer representation of the boolean value, i.e. `0` or `1`.


### `ClassNameFormField`

`ClassNameFormField` is a [text form field](#textformfield) that supports additional settings, specific to entering a PHP class name:

- `classExists($classExists = true)` and `getClassExists()` can be used to ensure that the entered class currently exists in the installation.
  By default, the existance of the entered class is required.
- `implementedInterface($interface)` and `getImplementedInterface()` can be used to ensure that the entered class implements the specified interface.
  By default, no interface is required.
- `parentClass($parentClass)` and `getParentClass()` can be used to ensure that the entered class extends the specified class.
  By default, no parent class is required.
- `instantiable($instantiable = true)` and `isInstantiable()` can be used to ensure that the entered class is instantiable.
  By default, entered classes have to instantiable.

Additionally, the default id of a `ClassNameFormField` object is `className`, the default label is `wcf.form.field.className`, and if either an interface or a parent class is required, a default description is set if no description has already been set (`wcf.form.field.className.description.interface` and `wcf.form.field.className.description.parentClass`, respectively).


### `DateFormField`

`DateFormField` is a form field to enter a date (and optionally a time).
The following methods are specific to this form field class:

- `saveValueFormat($saveValueFormat)` and `getSaveValueFormat()` can be used to specify the date format of the value returned by `getSaveValue()`.
  By default, `U` is used as format.
  The [PHP manual](https://secure.php.net/manual/en/function.date.php) provides an overview of supported formats.
- `supportTime($supportsTime = true)` and `supportsTime()` can be used to toggle whether, in addition to a date, a time can also be specified.
  By default, specifying a time is disabled.


### `DescriptionFormField`

`DescriptionFormField` is a [multi-line text form field](#multilinetextformfield) with `description` as the default id and `wcf.global.description` as the default label.


### `FloatFormField`

`FloatFormField` is an implementation of [AbstractNumericFormField](#abstractnumericformfield) for floating point numbers.


### `IconFormField`

`IconFormField` is a form field to select a FontAwesome icon.


### `IntegerFormField`

`IntegerFormField` is an implementation of [AbstractNumericFormField](#abstractnumericformfield) for integers.


### `IsDisabledFormField`

`IsDisabledFormField` is a [boolean form field](#booleanformfield) with `isDisabled` as the default id.


### `ItemListFormField`

`ItemListFormField` is a form field in which multiple values can be entered and returned in different formats as save value.
The `saveValueType($saveValueType)` and `getSaveValueType()` methods are specific to this form field class and determine the format of the save value.
The following save value types are supported:

- `ItemListFormField::SAVE_VALUE_TYPE_ARRAY` adds a custom data processor that writes the form field data directly in the parameters array and not in the data sub-array of the parameters array.
- `ItemListFormField::SAVE_VALUE_TYPE_CSV` lets the value be returned as a string in which the values are concatenated by commas.
- `ItemListFormField::SAVE_VALUE_TYPE_NSV` lets the value be returned as a string in which the values are concatenated by `\n`.
- `ItemListFormField::SAVE_VALUE_TYPE_SSV` lets the value be returned as a string in which the values are concatenated by spaces.

By default, `ItemListFormField::SAVE_VALUE_TYPE_CSV` is used.

If `ItemListFormField::SAVE_VALUE_TYPE_ARRAY` is used as save value type, `ItemListFormField` objects register a [custom form field data processor](php_api_form_builder-validation_data.html#customformfielddataprocessor) to add the relevant array into the `$parameters` array directly using the object property as the array key.


### `MultilineTextFormField`

`MultilineTextFormField` is a [text form field](#textformfield) that supports multiple rows of text.
The methods `rows($rows)` and `getRows()` can be used to set and get the number of rows of the `textarea` elements.
The default number of rows is `10`.
These methods do **not**, however, restrict the number of text rows that canbe entered.


### `MultipleSelectionFormField`

`MultipleSelectionFormField` is a form fields that allows the selection of multiple options out of a predefined list of available options.
The class implements `IFilterableSelectionFormField`, `IImmutableFormField`, and `INullableFormField`.
If the field is nullable and no option is selected, `null` is returned as the save value.


### `RadioButtonFormField`

`RadioButtonFormField` is a form fields that allows the selection of a single option out of a predefined list of available options using radiobuttons.
The class implements `IImmutableFormField` and `ISelectionFormField`.


### `ShowOrderFormField`

`ShowOrderFormField` is a [single selection form field](#singleselectionformfield) for which the selected value determines the position at which an object is shown.
The show order field provides a list of all siblings and the object will be positioned **after** the selected sibling.
To insert objects at the very beginning, the `options()` automatically method prepends an additional option for that case so that only the existing siblings need to be passed.
The default id of instances of this class is `showOrder` and their default label is `wcf.form.field.showOrder`.


### `SingleSelectionFormField`

`SingleSelectionFormField` is a form fields that allows the selection of a single option out of a predefined list of available options.
The class implements `IFilterableSelectionFormField`, `IImmutableFormField`, and `INullableFormField`.
If the field is nullable and the current form field value is considered `empty` by PHP, `null` is returned as the save value.


### `SortOrderFormField`

`SingleSelectionFormField` is a [single selection form field](#singleselectionformfield) with default id `sortOrder`, default label `wcf.global.showOrder` and default options `ASC: wcf.global.sortOrder.ascending` and `DESC: wcf.global.sortOrder.descending`.


### `TextFormField`

`TextFormField` is a form field that allows entering a single line of text.
The class implements `IImmutableFormField`, `II18nFormField`, `IMaximumLengthFormField`, `IMinimumLengthFormField`, and `IPlaceholderFormField`.


### `TitleFormField`

`TitleFormField` is a [text form field](#textformfield) with `title` as the default id and `wcf.global.title` as the default label.


### `UrlFormField`

`UrlFormField` is a [text form field](#textformfield) whose values are checked via `Url::is()`.


### `WysiwygFormField`

`WysiwygFormField` is used for WYSIWYG editor form fields.
The class implements `IMaximumLengthFormField`, `IMinimumLengthFormField`, and `IObjectTypeFormField` and requires an object type of the object type definition `com.woltlab.wcf.message`.
The following methods are specific to this form field class:

- `autosaveId($autosaveId)` and `getAutosaveId()` can be used enable automatically saving the current editor contents in the browser using the given id.
  An empty string signals that autosaving is disabled.
- `lastEditTime($lastEditTime)` and `getLastEditTime()` can be used to set the last time the contents have been edited and saved so that the JavaScript can determine if the contents stored in the browser are older or newer.
  `0` signals that no last edit time has been set.

`WysiwygFormField` objects register a [custom form field data processor](php_api_form_builder-validation_data.html#customformfielddataprocessor) to add the relevant simple ACL data array into the `$parameters` array directly using the object property as the array key.



## Specific Fields

The following form fields are reusable fields that generally are bound to a certain API or `DatabaseObject` implementation.


### `AclFormField`

`AclFormField` is used for setting up acl values for specific objects.
The class implements `IObjectTypeFormField` and requires an object type of the object type definition `com.woltlab.wcf.acl`.
Additionally, the class provides the methods `categoryName($categoryName)` and `getCategoryName()` that allow setting a specific name or filter for the acl option categories whose acl options are shown.
A category name of `null` signals that no category filter is used.

`AclFormField` objects register a [custom form field data processor](php_api_form_builder-validation_data.html#customformfielddataprocessor) to add the relevant ACL object type id into the `$parameters` array directly using `{$objectProperty}_aclObjectTypeID` as the array key.
The relevant database object action method is expected, based on the given ACL object type id, to save the ACL option values appropriately.


### `OptionFormField`

`OptionFormField` is an [item list form field](#itemlistformfield) to set a list of options.
The class implements `IPackagesFormField` and only options of the set packages are considered available.
The default label of instances of this class is `wcf.form.field.option` and their default id is `options`.


### `SimpleAclFormField`

`SimpleAclFormField` is used for setting up simple acl values (one `yes`/`no` option per user and user group) for specific objects.

`SimpleAclFormField` objects register a [custom form field data processor](php_api_form_builder-validation_data.html#customformfielddataprocessor) to add the relevant simple ACL data array into the `$parameters` array directly using the object property as the array key.


### `TagFormField`

`TagFormField` is a form field to enter tags.
Arrays passed to `TagFormField::values()` can contain tag names as strings and `Tag` objects.
The default label of instances of this class is `wcf.tagging.tags` and their default description is `wcf.tagging.tags.description`.

`TagFormField` objects register a [custom form field data processor](php_api_form_builder-validation_data.html#customformfielddataprocessor) to add the array with entered tag names into the `$parameters` array directly using the object property as the array key.


### `UserFormField`

`UserFormField` is a form field to enter existing users.
The class implements `IImmutableFormField`, `IMultipleFormField`, and `INullableFormField`.


### `UserGroupOptionFormField`

`UserGroupOptionFormField` is an [item list form field](#itemlistformfield) to set a list of user group options/permissions.
The class implements `IPackagesFormField` and only user group options of the set packages are considered available.
The default label of instances of this class is `wcf.form.field.userGroupOption` and their default id is `permissions`.


### `UsernameFormField`

`UsernameFormField` is used for entering one non-existing username.
The class implements `IImmutableFormField`, `IMaximumLengthFormField`, `IMinimumLengthFormField`, `INullableFormField`, and `IPlaceholderFormField`.
As usernames have a system-wide restriction of a minimum length of 3 and a maximum length of 100 characters, these values are also used as the default value for the field’s minimum and maximum length.



## Single-Use Form Fields

The following form fields are specific for certain forms and hardly reusable in other contexts.


### `BBCodeAttributesFormField`

`DevtoolsProjectExcludedPackagesFormField` is a form field for setting the attributes of a BBCode.


### `DevtoolsProjectExcludedPackagesFormField`

`DevtoolsProjectExcludedPackagesFormField` is a form field for setting the excluded packages of a devtools project.


### `DevtoolsProjectInstructionsFormField`

`DevtoolsProjectExcludedPackagesFormField` is a form field for setting the installation and update instructions of a devtools project.


### `DevtoolsProjectOptionalPackagesFormField`

`DevtoolsProjectExcludedPackagesFormField` is a form field for setting the optional packages of a devtools project.


### `DevtoolsProjectRequiredPackagesFormField`

`DevtoolsProjectExcludedPackagesFormField` is a form field for setting the required packages of a devtools project.