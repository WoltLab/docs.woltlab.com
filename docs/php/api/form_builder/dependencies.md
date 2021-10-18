# Form Node Dependencies

Form node dependencies allow to make parts of a form dynamically available or unavailable depending on the values of form fields.
Dependencies are always added to the object whose visibility is determined by certain form fields.
They are **not** added to the form field’s whose values determine the visibility!
An example is a text form field that should only be available if a certain option from a single selection form field is selected.
Form builder’s dependency system supports such scenarios and also automatically making form containers unavailable once all of its children are unavailable.

If a form node has multiple dependencies and one of them is not met, the form node is unavailable.
A form node not being available due to dependencies has to the following consequences:

- The form field value is not validated. It is, however, read from the request data as all request data needs to be read first so that the dependencies can determine whether they are met or not.
- No data is collected for the form field and returned by `IFormDocument::getData()`.
- In the actual form, the form field will be hidden via JavaScript.


## `IFormFieldDependency`

The basis of the dependencies is the `IFormFieldDependency` interface that has to be implemented by every dependency class.
The interface requires the following methods:

- `checkDependency()` checks if the dependency is met, thus if the dependant form field should be considered available.
- `dependentNode(IFormNode $node)` and `getDependentNode()` can be used to set and get the node whose availability depends on the referenced form field.
  `TFormNode::addDependency()` automatically calls `dependentNode(IFormNode $node)` with itself as the dependent node, thus the dependent node is automatically set by the API.
- `field(IFormField $field)` and `getField()` can be used to set and get the form field that influences the availability of the dependent node.
- `fieldId($fieldId)` and `getFieldId()` can be used to set and get the id of the form field that influences the availability of the dependent node.
- `getHtml()` returns JavaScript code required to ensure the dependency in the form output.
- `getId()` returns the id of the dependency used to identify multiple dependencies of the same form node.
- `static create($id)` is the factory method that has to be used to create new dependencies with the given id.

`AbstractFormFieldDependency` provides default implementations for all methods except for `checkDependency()`.

Using `fieldId($fieldId)` instead of `field(IFormField $field)` makes sense when adding the dependency directly when setting up the form:

```php
$container->appendChildren([
	FooField::create('a'),
	
	BarField::create('b')
		->addDependency(
			BazDependency::create('a')
				->fieldId('a')
		)
]);
```

Here, without an additional assignment, the first field with id `a` cannot be accessed thus `fieldId($fieldId)` should be used as the id of the relevant field is known.
When the form is built, all dependencies that only know the id of the relevant field and do not have a reference for the actual object are populated with the actual form field objects.


## Default Dependencies

WoltLab Suite Core delivers the following default dependency classes by default:

### `NonEmptyFormFieldDependency`

`NonEmptyFormFieldDependency` can be used to ensure that a node is only shown if the value of the referenced form field is not empty (being empty is determined using PHP’s `empty()` language construct).

### `EmptyFormFieldDependency`

This is the inverse of `NonEmptyFormFieldDependency`, checking for `!empty()`.

### `ValueFormFieldDependency`

`ValueFormFieldDependency` can be used to ensure that a node is only shown if the value of the referenced form field is from a specified list of of values (see methods `values($values)` and `getValues()`).
  Additionally, via `negate($negate = true)` and `isNegated()`, the logic can also be inverted by requiring the value of the referenced form field not to be from a specified list of values.

### `ValueIntervalFormFieldDependency`

!!! info "Only available since version 5.5."

`ValueIntervalFormFieldDependency` can be used to ensure that a node is only shown if the value of the referenced form field is in a specific interval whose boundaries are set via `minimum(?float $minimum = null)` and `maximum(?float $maximum = null)`.

### `IsNotClickedFormFieldDependency`

`IsNotClickedFormFieldDependency` is a special dependency for [`ButtonFormField`s](./form_fields.md#buttonformfield).
Refer to the documentation of `ButtomFormField` for details.

## JavaScript Implementation

To ensure that dependent node are correctly shown and hidden when changing the value of referenced form fields, every PHP dependency class has a corresponding JavaScript module that checks the dependency in the browser.
Every JavaScript dependency has to extend `WoltLabSuite/Core/Form/Builder/Field/Dependency/Abstract` and implement the `checkDependency()` function, the JavaScript version of `IFormFieldDependency::checkDependency()`.

All of the JavaScript dependency objects automatically register themselves during initialization with the `WoltLabSuite/Core/Form/Builder/Field/Dependency/Manager` which takes care of checking the dependencies at the correct points in time.

Additionally, the dependency manager also ensures that form containers in which all children are hidden due to dependencies are also hidden and, once any child becomes available again, makes the container also available again.
Every form container has to create a matching form container dependency object from a module based on `WoltLabSuite/Core/Form/Builder/Field/Dependency/Abstract`.


## Examples

If `$booleanFormField` is an instance of `BooleanFormField` and the text form field `$textFormField` should only be available if “Yes” has been selected, the following condition has to be set up:

```php
$textFormField->addDependency(
	NonEmptyFormFieldDependency::create('booleanFormField')
		->field($booleanFormField)
);
```

If `$singleSelectionFormField` is an instance of `SingleSelectionFormField` that offers the options `1`, `2`, and `3` and `$textFormField` should only be available if `1` or `3` is selected, the following condition has to be set up:

```php
$textFormField->addDependency(
	NonEmptyFormFieldDependency::create('singleSelectionFormField')
		->field($singleSelectionFormField)
		->values([1, 3])
);
```

If, in contrast, `$singleSelectionFormField` has many available options and `7` is the only option for which `$textFormField` should **not** be available, `negate()` should be used:

```php
$textFormField->addDependency(
	NonEmptyFormFieldDependency::create('singleSelectionFormField')
		->field($singleSelectionFormField)
		->values([7])
		->negate()
);
```
