# Structure of Form Builder

Forms built with form builder consist of three major structural elements listed from top to bottom:

1. form document,
1. form container,
1. form field.

The basis for all three elements are form nodes.

!!! info "The form builder API uses fluent interfaces heavily, meaning that unless a method is a getter, it generally returns the objects itself to support method chaining."


## Form Nodes

- `IFormNode` is the base interface that any node of a form has to implement.
- `IFormChildNode` extends `IFormNode` for such elements of a form that can be a child node to a parent node.
- `IFormParentNode` extends `IFormNode` for such elements of a form that can be a parent to child nodes.
- `IFormElement` extends `IFormNode` for such elements of a form that can have a description and a label.


### `IFormNode` / `TFormNode`

`IFormNode` is the base interface that any node of a form has to implement and it requires the following methods:

- `addClass($class)`, `addClasses(array $classes)`, `removeClass($class)`, `getClasses()`, and `hasClass($class)` add, remove, get, and check for CSS classes of the HTML element representing the form node.
  If the form node consists of multiple (nested) HTML elements, the classes are generally added to the top element.
  `static validateClass($class)` is used to check if a given CSS class is valid.
  By default, a form node has no CSS classes.
- `addDependency(IFormFieldDependency $dependency)`, `removeDependency($dependencyId)`, `getDependencies()`, and `hasDependency($dependencyId)` add, remove, get, and check for dependencies of this form node on other form fields.
  `checkDependencies()` checks if **all** of the node’s dependencies are met and returns a boolean value reflecting the check’s result.
  The [form builder dependency documentation](php_api_form_builder-dependencies.md) provides more detailed information about dependencies and how they work.
  By default, a form node has no dependencies.
- `attribute($name, $value = null)`, `removeAttribute($name)`, `getAttribute($name)`, `getAttributes()`, `hasAttribute($name)` add, remove, get, and check for attributes of the HTML element represting the form node.
  The attributes are added to the same element that the CSS classes are added to.
  `static validateAttribute($name)` is used to check if a given attribute is valid.
  By default, a form node has no attributes.
- `available($available = true)` and `isAvailable()` can be used to set and check if the node is available.
  The availability functionality can be used to easily toggle form nodes based, for example, on options without having to create a condition to append the relevant.
  This way of checking availability makes it easier to set up forms. 
  By default, every form node is available.
  
  The following aspects are important when working with availability:
  
  - Unavailable fields produce no output, their value is not read, they are not validated and they are not checked for save values.
  - Form fields are also able to mark themselves as unavailable, for example, a selection field without any options.
  - Form containers are automatically unavailable if they contain no available children.
  
  Availability sets the static availability for form nodes that does not change during the lifetime of a form.
  In contrast, dependencies represent a dynamic availability for form nodes that depends on the current value of certain form fields.
- `cleanup()` is called after the whole form is not used anymore to reset other APIs if the form fields depends on them and they expect such a reset.
  This method is not intended to clean up the form field’s value as a new form document object is created to show a clean form.
- `getDocument()` returns the `IFormDocument` object the node belongs to.
  (As `IFormDocument` extends `IFormNode`, form document objects simply return themselves.)
- `getHtml()` returns the HTML representation of the node.
  `getHtmlVariables()` return template variables (in addition to the form node itself) to render the node’s HTML representation.
- `id($id)` and `getId()` set and get the id of the form node.
  Every id has to be unique within a form.
  `getPrefixedId()` returns the prefixed version of the node’s id (see `IFormDocument::getPrefix()` and `IFormDocument::prefix()`).
  `static validateId($id)` is used to check if a given id is valid.
- `populate()` is called by `IFormDocument::build()` after all form nodes have been added.
  This method should finilize the initialization of the form node after all parent-child relations of the form document have been established.
  This method is needed because during the construction of a form node, it neither knows the form document it will belong to nor does it know its parent.
- `validate()` checks, after the form is submitted, if the form node is valid.
  A form node with children is valid if all of its child nodes are valid.
  A form field is valid if its value is valid.
- `static create($id)` is the factory method that has to be used to create new form nodes with the given id.

`TFormNode` provides a default implementation of most of these methods.


### `IFormChildNode` / `TFormChildNode`

`IFormChildNode` extends `IFormNode` for such elements of a form that can be a child node to a parent node and it requires the `parent(IFormParentNode $parentNode)` and `getParent()` methods used to set and get the node’s parent node.
`TFormChildNode` provides a default implementation of these two methods and also of `IFormNode::getDocument()`.


### `IFormParentNode` / `TFormParentNode`

`IFormParentNode` extends `IFormNode` for such elements of a form that can be a parent to child nodes.
Additionally, the interface also extends `\Countable` and `\RecursiveIterator`.
The interface requires the following methods:

- `appendChild(IFormChildNode $child)`, `appendChildren(array $children)`, `insertAfter(IFormChildNode $child, $referenceNodeId)`, and `insertBefore(IFormChildNode $child, $referenceNodeId)` are used to insert new children either at the end or at specific positions.
  `validateChild(IFormChildNode $child)` is used to check if a given child node can be added.
  A child node cannot be added if it would cause an id to be used twice.
- `children()` returns the direct children of a form node.
- `getIterator()` return  a recursive iterator for a form node.
- `getNodeById($nodeId)` returns the node with the given id by searching for it in the node’s children and recursively in all of their children.
  `contains($nodeId)` can be used to simply check if a node with the given id exists.
- `hasValidationErrors()` checks if a form node or any of its children has a validation error (see `IFormField::getValidationErrors()`).
- `readValues()` recursively calls `IFormParentNode::readValues()` and `IFormField::readValue()` on its children.


### `IFormElement` / `TFormElement`

`IFormElement` extends `IFormNode` for such elements of a form that can have a description and a label and it requires the following methods:

- `label($languageItem = null, array $variables = [])` and `getLabel()` can be used to set and get the label of the form element.
  `requiresLabel()` can be checked if the form element requires a label.
  A label-less form element that requires a label will prevent the form from being rendered by throwing an exception.
- `description($languageItem = null, array $variables = [])` and `getDescription()` can be used to set and get the description of the form element.


### `IObjectTypeFormNode` / `TObjectTypeFormNode`

`IObjectTypeFormField` has to be implemented by form nodes that rely on a object type of a specific object type definition in order to function.
The implementing class has to implement the methods `objectType($objectType)`, `getObjectType()`, and `getObjectTypeDefinition()`.
`TObjectTypeFormNode` provides a default implementation of these three methods.


### `CustomFormNode`

`CustomFormNode` is a form node whose contents can be set directly via `content($content)`.

!!! warning "This class should generally not be relied on. Instead, `TemplateFormNode` should be used."


### `TemplateFormNode`

`TemplateFormNode` is a form node whose contents are read from a template.
`TemplateFormNode` has the following additional methods:

- `application($application)` and `getApplicaton()` can be used to set and get the abbreviation of the application the shown template belongs to.
  If no template has been set explicitly, `getApplicaton()` returns `wcf`.
- `templateName($templateName)` and `getTemplateName()` can be used to set and get the name of the template containing the node contents.
  If no template has been set and the node is rendered, an exception will be thrown.
- `variables(array $variables)` and `getVariables()` can be used to set and get additional variables passed to the template.


## Form Document

A form document object represents the form as a whole and has to implement the `IFormDocument` interface.
WoltLab Suite provides a default implementation with the `FormDocument` class.
`IFormDocument` should not be implemented directly but instead `FormDocument` should be extended to avoid issues if the `IFormDocument` interface changes in the future.

`IFormDocument` extends `IFormParentNode` and requires the following additional methods:

- `action($action)` and `getAction()` can be used set and get the `action` attribute of the `<form>` HTML element.
- `addButton(IFormButton $button)` and `getButtons()` can be used add and get form buttons that are shown at the bottom of the form. 
  `addDefaultButton($addDefaultButton)` and `hasDefaultButton()` can be used to set and check if the form has the default button which is added by default unless specified otherwise.
  Each implementing class may define its own default button.
  `FormDocument` has a button with id `submitButton`, label `wcf.global.button.submit`, access key `s`, and CSS class `buttonPrimary` as its default button. 
- `ajax($ajax)` and `isAjax()` can be used to set and check if the form document is requested via an AJAX request or processes data via an AJAX request.
  These methods are helpful for form fields that behave differently when providing data via AJAX.
- `build()` has to be called once after all nodes have been added to this document to trigger `IFormNode::populate()`.
- `formMode($formMode)` and `getFormMode()` sets the form mode.
  Possible form modes are:
  
  - `IFormDocument::FORM_MODE_CREATE` has to be used when the form is used to create a new object.
  - `IFormDocument::FORM_MODE_UPDATE` has to be used when the form is used to edit an existing object.
- `getData()` returns the array containing the form data and which is passed as the `$parameters` argument of the constructor of a database object action object.
- `getDataHandler()` returns the data handler for this document that is used to process the field data into a parameters array for the constructor of a database object action object.
- `getEnctype()` returns the encoding type of the form.
  If the form contains a `IFileFormField`, `multipart/form-data` is returned, otherwise `null` is returned.
- `loadValues(array $data, IStorableObject $object)` is used when editing an existing object to set the form field values by calling `IFormField::loadValue()` for all form fields.
  Additionally, the form mode is set to `IFormDocument::FORM_MODE_UPDATE`.
- `method($method)` and `getMethod()` can be used to set and get the `method` attribute of the `<form>` HTML element.
  By default, the method is `post`.
- `prefix($prefix)` and `getPrefix()` can be used to set and get a global form prefix that is prepended to form elements’ names and ids to avoid conflicts with other forms.
  By default, the prefix is an empty string.
  If a prefix of `foo` is set, `getPrefix()` returns `foo_` (additional trailing underscore).
- `requestData(array $requestData)`, `getRequestData($index = null)`, and `hasRequestData($index = null)` can be used to set, get and check for specific request data.
  In most cases, the relevant request data is the `$_POST` array.
  In default AJAX requests handled by database object actions, however, the request data generally is in `AbstractDatabaseObjectAction::$parameters`.
  By default, `$_POST` is the request data.

The last aspect is relevant for `DialogFormDocument` objects.
`DialogFormDocument` is a specialized class for forms in dialogs that, in contrast to `FormDocument` do not require an `action` to be set.
Additionally, `DialogFormDocument` provides the `cancelable($cancelable = true)` and `isCancelable()` methods used to determine if the dialog from can be canceled.
By default, dialog forms are cancelable.


## Form Button

A form button object represents a button shown at the end of the form that, for example, submits the form.
Every form button has to implement the `IFormButton` interface that extends `IFormChildNode` and `IFormElement`.
`IFormButton` requires four methods to be implemented:

- `accessKey($accessKey)` and `getAccessKey()` can be used to set and get the access key with which the form button can be activated.
  By default, form buttons have no access key set.
- `submit($submitButton)` and `isSubmit()` can be used to set and check if the form button is a submit button.
  A submit button is an `input[type=submit]` element.
  Otherwise, the button is a `button` element. 


## Form Container

A form container object represents a container for other form containers or form field directly.
Every form container has to implement the `IFormContainer` interface which requires the following method:

- `loadValues(array $data, IStorableObject $object)` is called by `IFormDocument::loadValuesFromObject()` to inform the container that object data is loaded.
  This method is *not* intended to generally call `IFormField::loadValues()` on its form field children as these methods are already called by `IFormDocument::loadValuesFromObject()`.
  This method is intended for specialized form containers with more complex logic.

There are multiple default container implementations:

1. `FormContainer` is the default implementation of `IFormContainer`.
1. `TabMenuFormContainer` represents the container of tab menu, while
1. `TabFormContainer` represents a tab of a tab menu and
1. `TabTabMenuFormContainer` represents a tab of a tab menu that itself contains a tab menu.
1. The children of `RowFormContainer` are shown in a row and should use `col-*` classes.
1. The children of `RowFormFieldContainer` are also shown in a row but does not show the labels and descriptions of the individual form fields.
   Instead of the individual labels and descriptions, the container's label and description is shown and both span all of fields.
1. `SuffixFormFieldContainer` can be used for one form field with a second selection form field used as a suffix.

The methods of the interfaces that `FormContainer` is implementing are well documented, but here is a short overview of the most important methods when setting up a form or extending a form with an event listener:

- `appendChild(IFormChildNode $child)`, `appendChildren(array $children)`, and `insertBefore(IFormChildNode $child, $referenceNodeId)` are used to insert new children into the form container.
- `description($languageItem = null, array $variables = [])` and `label($languageItem = null, array $variables = [])` are used to set the description and the label or title of the form container.


## Form Field

A form field object represents a concrete form field that allows entering data.
Every form field has to implement the `IFormField` interface which extends `IFormChildNode` and `IFormElement`.

`IFormField` requires the following additional methods:

- `addValidationError(IFormFieldValidationError $error)` and `getValidationErrors()` can be used to get and set validation errors of the form field (see [form validation](php_api_form_builder-validation_data.md#form-validation)).
- `addValidator(IFormFieldValidator $validator)`, `getValidators()`, `removeValidator($validatorId)`, and `hasValidator($validatorId)` can be used to get, set, remove, and check for validators for the form field (see [form validation](php_api_form_builder-validation_data.md#form-validation)).
- `getFieldHtml()` returns the field's HTML output without the surrounding `dl` structure.
- `objectProperty($objectProperty)` and `getObjectProperty()` can be used to get and set the object property that the field represents.
  When setting the object property is set to an empty string, the previously set object property is unset.
  If no object property has been set, the field’s (non-prefixed) id is returned.
  
  The object property allows having different fields (requiring different ids) that represent the same object property which is handy when available options of the field’s value depend on another field.
  Having object property allows to define different fields for each value of the other field and to use form field dependencies to only show the appropriate field.
- `readValue()` reads the form field value from the request data after the form is submitted.
- `required($required = true)` and `isRequired()` can be used to determine if the form field has to be filled out.
  By default, form fields do not have to be filled out.
- `value($value)` and `getSaveValue()` can be used to get and set the value of the form field to be used outside of the context of forms.
  `getValue()`, in contrast, returns the internal representation of the form field’s value.
  In general, the internal representation is only relevant when validating the value in additional validators.
  `loadValue(array $data, IStorableObject $object)` extracts the form field value from the given data array (and additional, non-editable data from the object if the field needs them).

`AbstractFormField` provides default implementations of many of the listed methods above and should be extended instead of implementing `IFormField` directly.

An overview of the form fields provided by default can be found [here](php_api_form_builder-form_fields.md).


### Form Field Interfaces and Traits

WoltLab Suite Core provides a variety of interfaces and matching traits with default implementations for several common features of form fields:


#### `IAutoFocusFormField` / `TAutoFocusFormField`

`IAutoFocusFormField` has to be implemented by form fields that can be auto-focused.
The implementing class has to implement the methods `autoFocus($autoFocus = true)` and `isAutoFocused()`.
By default, form fields are not auto-focused.
`TAutoFocusFormField` provides a default implementation of these two methods.


#### `IFileFormField`

`IFileFormField` has to be implemented by every form field that uploads files so that the `enctype` attribute of the form document is `multipart/form-data` (see `IFormDocument::getEnctype()`).


#### `IFilterableSelectionFormField` / `TFilterableSelectionFormField`

`IFilterableSelectionFormField` extends `ISelectionFormField` by the possibilty for users when selecting the value(s) to filter the list of available options.
The implementing class has to implement the methods `filterable($filterable = true)` and `isFilterable()`.
`TFilterableSelectionFormField` provides a default implementation of these two methods.


#### `II18nFormField` / `TI18nFormField`

`II18nFormField` has to be implemented by form fields if the form field value can be entered separately for all available languages.
The implementing class has to implement the following methods:

- `i18n($i18n = true)` and `isI18n()` can be used to set whether a specific instance of the class actually supports multilingual input.
- `i18nRequired($i18nRequired = true)` and `isI18nRequired()` can be used to set whether a specific instance of the class requires separate values for all languages.
- `languageItemPattern($pattern)` and `getLanguageItemPattern()` can be used to set the pattern/regular expression for the language item used to save the multilingual values.
- `hasI18nValues()` and `hasPlainValue()` check if the current value is a multilingual or monolingual value.

`TI18nFormField` provides a default implementation of these eight methods and additional default implementations of some of the `IFormField` methods.
If multilingual input is enabled for a specific form field, classes using `TI18nFormField` register a [custom form field data processor](php_api_form_builder-validation_data.md#customformfielddataprocessor) to add the array with multilingual input into the `$parameters` array directly using `{$objectProperty}_i18n` as the array key.
If multilingual input is enabled but only a monolingual value is entered, the custom form field data processor does nothing and the form field’s value is added by the `DefaultFormDataProcessor` into the `data` sub-array of the `$parameters` array.

!!! info "`TI18nFormField` already provides a default implementation of `IFormField::validate()`."


#### `IImmutableFormField` / `TImmutableFormField`

`IImmutableFormField` has to be implemented by form fields that support being displayed but whose value cannot be changed.
The implementing class has to implement the methods `immutable($immutable = true)` and `isImmutable()` that can be used to determine if the value of the form field is mutable or immutable.
By default, form field are mutable.


#### `IMaximumFormField` / `TMaximumFormField`

`IMaximumFormField` has to be implemented by form fields if the entered value must have a maximum value.
The implementing class has to implement the methods `maximum($maximum = null)` and `getMaximum()`.
A maximum of `null` signals that no maximum value has been set.
`TMaximumFormField` provides a default implementation of these two methods.

!!! warning "The implementing class has to validate the entered value against the maximum value manually."


#### `IMaximumLengthFormField` / `TMaximumLengthFormField`

`IMaximumLengthFormField` has to be implemented by form fields if the entered value must have a maximum length.
The implementing class has to implement the methods `maximumLength($maximumLength = null)`, `getMaximumLength()`, and `validateMaximumLength($text, Language $language = null)`.
A maximum length of `null` signals that no maximum length has been set.
`TMaximumLengthFormField` provides a default implementation of these two methods.

!!! warning "The implementing class has to validate the entered value against the maximum value manually by calling `validateMaximumLength()`."


#### `IMinimumFormField` / `TMinimumFormField`

`IMinimumFormField` has to be implemented by form fields if the entered value must have a minimum value.
The implementing class has to implement the methods `minimum($minimum = null)` and `getMinimum()`.
A minimum of `null` signals that no minimum value has been set.
`TMinimumFormField` provides a default implementation of these three methods.

!!! warning "The implementing class has to validate the entered value against the minimum value manually."


#### `IMinimumLengthFormField` / `TMinimumLengthFormField`

`IMinimumLengthFormField` has to be implemented by form fields if the entered value must have a minimum length.
The implementing class has to implement the methods `minimumLength($minimumLength = null)`, `getMinimumLength()`, and `validateMinimumLength($text, Language $language = null)`.
A minimum length of `null` signals that no minimum length has been set.
`TMinimumLengthFormField` provides a default implementation of these three methods.

!!! warning "The implementing class has to validate the entered value against the minimum value manually by calling `validateMinimumLength()`."


#### `IMultipleFormField` / `TMultipleFormField`

`IMinimumLengthFormField` has to be implemented by form fields that support selecting or setting multiple values.
The implementing class has to implement the following methods:

- `multiple($multiple = true)` and `allowsMultiple()` can be used to set whether a specific instance of the class actually should support multiple values.
  By default, multiple values are not supported.
- `minimumMultiples($minimum)` and `getMinimumMultiples()` can be used to set the minimum number of values that have to be selected/entered.
  By default, there is no required minimum number of values.
- `maximumMultiples($minimum)` and `getMaximumMultiples()` can be used to set the maximum number of values that have to be selected/entered.
  By default, there is no maximum number of values.
  `IMultipleFormField::NO_MAXIMUM_MULTIPLES` is returned if no maximum number of values has been set and it can also be used to unset a previously set maximum number of values.

`TMultipleFormField` provides a default implementation of these six methods and classes using `TMultipleFormField` register a [custom form field data processor](php_api_form_builder-validation_data.md#customformfielddataprocessor) to add the `HtmlInputProcessor` object with the text into the `$parameters` array directly using `{$objectProperty}_htmlInputProcessor` as the array key.

!!! warning "The implementing class has to validate the values against the minimum and maximum number of values manually."


#### `INullableFormField` / `TNullableFormField`

`INullableFormField` has to be implemented by form fields that support `null` as their (empty) value.
The implementing class has to implement the methods `nullable($nullable = true)` and `isNullable()`.
`TNullableFormField` provides a default implementation of these two methods.

`null` should be returned by `IFormField::getSaveValue()` is the field is considered empty and the form field has been set as nullable.


#### `IPackagesFormField` / `TPackagesFormField`

`IPackagesFormField` has to be implemented by form fields that, in some way, considers packages whose ids may be passed to the field object.
The implementing class has to implement the methods `packageIDs(array $packageIDs)` and `getPackageIDs()`.
`TPackagesFormField` provides a default implementation of these two methods.


#### `IPlaceholderFormField` / `TPlaceholderFormField`

`IPlaceholderFormField` has to be implemented by form fields that support a placeholder value for empty fields.
The implementing class has to implement the methods `placeholder($languageItem = null, array $variables = [])` and `getPlaceholder()`.
`TPlaceholderFormField` provides a default implementation of these two methods.


#### `ISelectionFormField` / `TSelectionFormField`

`ISelectionFormField` has to be implemented by form fields with a predefined set of possible values.
The implementing class has to implement the getter and setter methods `options($options, $nestedOptions = false, $labelLanguageItems = true)` and `getOptions()` and additionally two methods related to nesting, i.e. whether the selectable options have a hierarchy:
`supportsNestedOptions()` and `getNestedOptions()`.
`TSelectionFormField` provides a default implementation of these four methods.


#### `ISuffixedFormField` / `TSuffixedFormField`

`ISuffixedFormField` has to be implemented by form fields that support supports displaying a suffix behind the actual input field.
The implementing class has to implement the methods `suffix($languageItem = null, array $variables = [])` and `getSuffix()`.
`TSuffixedFormField` provides a default implementation of these two methods.


#### `TDefaultIdFormField`

Form fields that have a default id have to use `TDefaultIdFormField` and have to implement the method `getDefaultId()`.


## Displaying Forms

The only thing to do in a template to display the **whole** form including all of the necessary JavaScript is to put

```smarty
{@$form->getHtml()}
```

into the template file at the relevant position.
