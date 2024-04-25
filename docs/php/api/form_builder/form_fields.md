# Form Builder Fields

## Abstract Form Fields

The following form field classes cannot be instantiated directly because they are abstract, but they can/must be used when creating own form field classes. 


### `AbstractFormField`

`AbstractFormField` is the abstract default implementation of the `IFormField` interface and it is expected that every implementation of `IFormField` implements the interface by extending this class.


### `AbstractNumericFormField`

`AbstractNumericFormField` is the abstract implementation of a form field handling a single numeric value.
The class implements `IAttributeFormField`, `IAutoCompleteFormField`, `ICssClassFormField`, `IImmutableFormField`, `IInputModeFormField`, `IMaximumFormField`, `IMinimumFormField`, `INullableFormField`, `IPlaceholderFormField` and `ISuffixedFormField`.
If the property `$integerValues` is `true`, the form field works with integer values, otherwise it works with floating point numbers.
The methods `step($step = null)` and `getStep()` can be used to set and get the step attribute of the `input` element.
The default step for form fields with integer values is `1`.
Otherwise, the default step is `any`.

### `AbstractFormFieldDecorator`

`AbstractFormFieldDecorator` is a default implementation of a decorator for form fields that forwards calls to all methods defined in `IFormField` to the respective method of the decorated object.
The class implements `IFormfield`.
If the implementation of a more specific interface is required then the remaining methods must be implemented in the concrete decorator derived from `AbstractFormFieldDecorator` and the type of the `$field` property must be narrowed appropriately.

## General Form Fields

The following form fields are general reusable fields without any underlying context.


### `BooleanFormField`

`BooleanFormField` is used for boolean (`0` or `1`, `yes` or `no`) values.
Objects of this class require a label.
The return value of `getSaveValue()` is the integer representation of the boolean value, i.e. `0` or `1`.
The class implements `IAttributeFormField`, `IAutoFocusFormField`, `ICssClassFormField`, and `IImmutableFormField`.


### `CheckboxFormField`

`CheckboxFormField` extends `BooleanFormField` and offers a simple HTML checkbox.


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


### `CurrencyFormField`

`CurrencyFormField` is an implementation designed for two-decimal currencies like EUR or USD. The API expect amounts to be provided in the smallest unit. For example, to set a value to 10 USD, provide an amount value of 1000 (i.e., 1000 cents).

Example:

```php
CurrencyFormField::create('name')
  ->currency('USD')
  ->value(1000); // = USD 10
```


### `DateFormField`

`DateFormField` is a form field to enter a date (and optionally a time).
The class implements `IAttributeFormField`, `IAutoFocusFormField`, `ICssClassFormField`, `IImmutableFormField`, and `INullableFormField`.
The following methods are specific to this form field class:

- `earliestDate($earliestDate)` and `getEarliestDate()` can be used to get and set the earliest selectable/valid date and `latestDate($latestDate)` and `getLatestDate()` can be used to get and set the latest selectable/valid date.
  The date passed to the setters must have the same format as set via `saveValueFormat()`.
  If a custom format is used, that format has to be set via `saveValueFormat()` before calling any of the setters.
- `saveValueFormat($saveValueFormat)` and `getSaveValueFormat()` can be used to specify the date format of the value returned by `getSaveValue()`.
  By default, `U` is used as format.
  The [PHP manual](https://secure.php.net/manual/en/function.date.php) provides an overview of supported formats.
- `supportTime($supportsTime = true)` and `supportsTime()` can be used to toggle whether, in addition to a date, a time can also be specified.
  By default, specifying a time is disabled.


### `DescriptionFormField`

`DescriptionFormField` is a [multi-line text form field](#multilinetextformfield) with `description` as the default id and `wcf.global.description` as the default label.


### `EmailFormField`

`EmailFormField` is a form field to enter an email address which is internally validated using `UserUtil::isValidEmail()`.
The class implements `IAttributeFormField`, `IAutoCompleteFormField`, `IAutoFocusFormField`, `ICssClassFormField`, `II18nFormField`, `IImmutableFormField`, `IInputModeFormField`, `IPatternFormField`, and `IPlaceholderFormField`.


### `FloatFormField`

`FloatFormField` is an implementation of [AbstractNumericFormField](#abstractnumericformfield) for floating point numbers.


### `HiddenFormField`

`HiddenFormField` is a form field without any user-visible UI.
Even though the form field is invisible to the user, the value can still be modified by the user, e.g. by leveraging the web browsers developer tools.
The `HiddenFormField` *must not* be used to transfer sensitive information or information that the user should not be able to modify.


### `IconFormField`

`IconFormField` is a form field to select a FontAwesome icon.


### `IntegerFormField`

`IntegerFormField` is an implementation of [AbstractNumericFormField](#abstractnumericformfield) for integers.


### `IsDisabledFormField`

`IsDisabledFormField` is a [boolean form field](#booleanformfield) with `isDisabled` as the default id.


### `ItemListFormField`

`ItemListFormField` is a form field in which multiple values can be entered and returned in different formats as save value.
The class implements `IAttributeFormField`, `IAutoFocusFormField`, `ICssClassFormField`, `IImmutableFormField`, and `IMultipleFormField`.
The `saveValueType($saveValueType)` and `getSaveValueType()` methods are specific to this form field class and determine the format of the save value.
The following save value types are supported:

- `ItemListFormField::SAVE_VALUE_TYPE_ARRAY` adds a custom data processor that writes the form field data directly in the parameters array and not in the data sub-array of the parameters array.
- `ItemListFormField::SAVE_VALUE_TYPE_CSV` lets the value be returned as a string in which the values are concatenated by commas.
- `ItemListFormField::SAVE_VALUE_TYPE_NSV` lets the value be returned as a string in which the values are concatenated by `\n`.
- `ItemListFormField::SAVE_VALUE_TYPE_SSV` lets the value be returned as a string in which the values are concatenated by spaces.

By default, `ItemListFormField::SAVE_VALUE_TYPE_CSV` is used.

If `ItemListFormField::SAVE_VALUE_TYPE_ARRAY` is used as save value type, `ItemListFormField` objects register a [custom form field data processor](validation_data.md#customformfielddataprocessor) to add the relevant array into the `$parameters` array directly using the object property as the array key.


### `LanguageItemFormNode`

`LanguageItemFormNode` is a form node that shows a language item without any surrounding HTML code.

Example:

```php
LanguageItemFormNode::create('name')
  ->languageItem('name_of_language_item');
```


### `MultilineTextFormField`

`MultilineTextFormField` is a [text form field](#textformfield) that supports multiple rows of text.
The methods `rows($rows)` and `getRows()` can be used to set and get the number of rows of the `textarea` elements.
The default number of rows is `10`.
These methods do **not**, however, restrict the number of text rows that can be entered.


### `MultipleSelectionFormField`

`MultipleSelectionFormField` is a form fields that allows the selection of multiple options out of a predefined list of available options.
The class implements `IAttributeFormField`, `ICssClassFormField`, `IFilterableSelectionFormField`, and `IImmutableFormField`.


### `RadioButtonFormField`

`RadioButtonFormField` is a form fields that allows the selection of a single option out of a predefined list of available options using radiobuttons.
The class implements `IAttributeFormField`, `ICssClassFormField`, `IImmutableFormField`, and `ISelectionFormField`.


### `RatingFormField`

`RatingFormField` is a form field to set a rating for an object.
The class implements `IImmutableFormField`, `IMaximumFormField`, `IMinimumFormField`, and `INullableFormField`.
Form fields of this class have `rating` as their default id, `wcf.form.field.rating` as their default label, `1` as their default minimum, and `5` as their default maximum.
For this field, the minimum and maximum refer to the minimum and maximum rating an object can get.
When the field is shown, there will be `maximum() - minimum() + 1` icons be shown with additional CSS classes that can be set and gotten via `defaultCssClasses(array $cssClasses)` and `getDefaultCssClasses()`.
If a rating values is set, the first `getValue()` icons will instead use the classes that can be set and gotten via `activeCssClasses(array $cssClasses)` and `getActiveCssClasses()`.
By default, the only default class is `star-o` and the active classes are `star` and `orange`. 


### `SelectFormField`

`SelectFormField` is a form fields that allows the selection of a single option out of a predefined list of available options.
The class implements `ICssClassFormField` and `IImmutableFormField`.

Example:

```php
SelectFormField::create('select')
  ->options(['option1', 'option2', 'option3']);
```

### `ShowOrderFormField`

`ShowOrderFormField` is a [single selection form field](#singleselectionformfield) for which the selected value determines the position at which an object is shown.
The show order field provides a list of all siblings and the object will be positioned **after** the selected sibling.
To insert objects at the very beginning, the `options()` automatically method prepends an additional option for that case so that only the existing siblings need to be passed.
The default id of instances of this class is `showOrder` and their default label is `wcf.form.field.showOrder`.

!!! info "It is important that the relevant object property is always kept updated. Whenever a new object is added or an existing object is edited or delete, the values of the other objects have to be adjusted to ensure consecutive numbering."


### `SingleSelectionFormField`

`SingleSelectionFormField` is a form fields that allows the selection of a single option out of a predefined list of available options.
The class implements `ICssClassFormField`, `IFilterableSelectionFormField`, `IImmutableFormField`, and `INullableFormField`.
If the field is nullable and the current form field value is considered `empty` by PHP, `null` is returned as the save value.


### `SortOrderFormField`

`SingleSelectionFormField` is a [single selection form field](#singleselectionformfield) with default id `sortOrder`, default label `wcf.global.showOrder` and default options `ASC: wcf.global.sortOrder.ascending` and `DESC: wcf.global.sortOrder.descending`.


### `TextFormField`

`TextFormField` is a form field that allows entering a single line of text.
The class implements `IAttributeFormField`, `IAutoCompleteFormField`, `ICssClassFormField`, `IImmutableFormField`, `II18nFormField`, `IInputModeFormField`, `IMaximumLengthFormField`, `IMinimumLengthFormField`, `IPatternFormField`, and `IPlaceholderFormField`.


### `TitleFormField`

`TitleFormField` is a [text form field](#textformfield) with `title` as the default id and `wcf.global.title` as the default label.


### `UrlFormField`

`UrlFormField` is a [text form field](#textformfield) whose values are checked via `Url::is()`.



## Specific Fields

The following form fields are reusable fields that generally are bound to a certain API or `DatabaseObject` implementation.


### `AclFormField`

`AclFormField` is used for setting up acl values for specific objects.
The class implements `IObjectTypeFormField` and requires an object type of the object type definition `com.woltlab.wcf.acl`.
Additionally, the class provides the methods `categoryName($categoryName)` and `getCategoryName()` that allow setting a specific name or filter for the acl option categories whose acl options are shown.
A category name of `null` signals that no category filter is used.

!!! info "Since version 5.5, the category name also supports filtering using a wildcard like `user.*`, see [WoltLab/WCF#4355](https://github.com/WoltLab/WCF/pull/4355)."

`AclFormField` objects register a [custom form field data processor](validation_data.md#customformfielddataprocessor) to add the relevant ACL object type id into the `$parameters` array directly using `{$objectProperty}_aclObjectTypeID` as the array key.
The relevant database object action method is expected, based on the given ACL object type id, to save the ACL option values appropriately.


### `ButtonFormField`

`ButtonFormField` shows a submit button as part of the form.
The class implements `IAttributeFormField` and `ICssClassFormField`.

Specifically for this form field, there is the `IsNotClickedFormFieldDependency` dependency with which certain parts of the form will only be processed if the relevent button has not clicked. 


### `CaptchaFormField`

`CaptchaFormField` is used to add captcha protection to the form.

You must specify a captcha object type (`com.woltlab.wcf.captcha`) using the `objectType()` method.


### `ColorFormField`

`ColorFormField` is used to specify RGBA colors using the `rgba(r, g, b, a)` format.
The class implements `IImmutableFormField`.


### `ContentLanguageFormField`

`ContentLanguageFormField` is used to select the content language of an object.
Fields of this class are only available if multilingualism is enabled and if there are content languages. 
The class implements `IImmutableFormField`.


### `LabelFormField`

`LabelFormField` is used to select a label from a specific label group.
The class implements `IObjectTypeFormNode`.

The `labelGroup(ViewableLabelGroup $labelGroup)` and `getLabelGroup()` methods are specific to this form field class and can be used to set and get the label group whose labels can be selected.
Additionally, there is the static method `createFields($objectType, array $labelGroups, $objectProperty = 'labelIDs)` that can be used to create all relevant label form fields for a given list of label groups.
In most cases, `LabelFormField::createFields()` should be used.


### `OptionFormField`

`OptionFormField` is an [item list form field](#itemlistformfield) to set a list of options.
The class implements `IPackagesFormField` and only options of the set packages are considered available.
The default label of instances of this class is `wcf.form.field.option` and their default id is `options`.


### `SimpleAclFormField`

`SimpleAclFormField` is used for setting up simple acl values (one `yes`/`no` option per user and user group) for specific objects.

`SimpleAclFormField` objects register a [custom form field data processor](validation_data.md#customformfielddataprocessor) to add the relevant simple ACL data array into the `$parameters` array directly using the object property as the array key.

!!! info "Since version 5.5, the field also supports inverted permissions, see [WoltLab/WCF#4570](https://github.com/WoltLab/WCF/pull/4570)."

The `SimpleAclFormField` supports inverted permissions, allowing the administrator to grant access to all non-selected users and groups. If this behavior is desired, it needs to be enabled by calling `supportInvertedPermissions`. An `invertPermissions` key containing a boolean value with the users selection will be provided together with the ACL values when saving the field.

### `SingleMediaSelectionFormField`

`SingleMediaSelectionFormField` is used to select a specific media file.
The class implements `IImmutableFormField`.

The following methods are specific to this form field class:

- `imageOnly($imageOnly = true)` and `isImageOnly()` can be used to set and check if only images may be selected.
- `getMedia()` returns the media file based on the current field value if a field is set.


### `TagFormField`

`TagFormField` is a form field to enter tags.
The class implements `IAttributeFormField` and `IObjectTypeFormNode`.
Arrays passed to `TagFormField::values()` can contain tag names as strings and `Tag` objects.
The default label of instances of this class is `wcf.tagging.tags` and their default description is `wcf.tagging.tags.description`.

`TagFormField` objects register a [custom form field data processor](validation_data.md#customformfielddataprocessor) to add the array with entered tag names into the `$parameters` array directly using the object property as the array key.


### `UploadFormField`

`UploadFormField` is a form field that allows uploading files by the user.

`UploadFormField` objects register a [custom form field data processor](validation_data.md#customformfielddataprocessor) to add the array of `wcf\system\file\upload\UploadFile\UploadFile` into the `$parameters` array directly using the object property as the array key. Also it registers the removed files as an array of `wcf\system\file\upload\UploadFile\UploadFile` into the `$parameters` array directly using the object property with the suffix `_removedFiles` as the array key.  

The field supports additional settings:

- `imageOnly($imageOnly = true)` and `isImageOnly()` can be used to ensure that the uploaded files are only images.
- `allowSvgImage($allowSvgImages = true)` and `svgImageAllowed()` can be used to allow SVG images, if the image only mode is enabled (otherwise, the method will throw an exception). By default, SVG images are not allowed.

#### Provide value from database object 

To provide values from a database object, you should implement the method `get{$objectProperty}UploadFileLocations()` to your database object class. This method must return an array of strings with the locations of the files.

#### Process files 

To process files in the database object action class, you must [`rename`](https://secure.php.net/manual/en/function.rename.php) the file to the final destination. You get the temporary location, by calling the method `getLocation()` on the given `UploadFile` objects. After that, you call `setProcessed($location)` with `$location` contains the new file location. This method sets the `isProcessed` flag to true and saves the new location. For updating files, it is relevant, whether a given file is already processed or not. For this case, the `UploadFile` object has an method `isProcessed()` which indicates, whether a file is already processed or new uploaded.


### `UserFormField`

`UserFormField` is a form field to enter existing users.
The class implements `IAutoCompleteFormField`, `IAutoFocusFormField`, `IImmutableFormField`, `IMultipleFormField`, and `INullableFormField`.
While the user is presented the names of the specified users in the user interface, the field returns the ids of the users as data.
The relevant `UserProfile` objects can be accessed via the `getUsers()` method.


### `UserPasswordField`

`UserPasswordField` is a form field for users' to enter their current password.
The class implements `IAttributeFormField`, `IAttributeFormField`, `IAutoCompleteFormField`, `IAutoFocusFormField`, and `IPlaceholderFormField`


### `UserGroupOptionFormField`

`UserGroupOptionFormField` is an [item list form field](#itemlistformfield) to set a list of user group options/permissions.
The class implements `IPackagesFormField` and only user group options of the set packages are considered available.
The default label of instances of this class is `wcf.form.field.userGroupOption` and their default id is `permissions`.


### `UsernameFormField`

`UsernameFormField` is used for entering one non-existing username.
The class implements `IAttributeFormField`, `IImmutableFormField`, `IMaximumLengthFormField`, `IMinimumLengthFormField`, `INullableFormField`, and `IPlaceholderFormField`.
As usernames have a system-wide restriction of a minimum length of 3 and a maximum length of 100 characters, these values are also used as the default value for the field’s minimum and maximum length.



## Wysiwyg form container

To integrate a wysiwyg editor into a form, you have to create a `WysiwygFormContainer` object.
This container takes care of creating all necessary form nodes listed below for a wysiwyg editor.

!!! warning "When creating the container object, its id has to be the id of the form field that will manage the actual text."

The following methods are specific to this form container class:

- `addSettingsNode(IFormChildNode $settingsNode)` and `addSettingsNodes(array $settingsNodes)` can be used to add nodes to the settings tab container.
- `attachmentData($objectType, $parentObjectID)` can be used to set the data relevant for attachment support.
  By default, not attachment data is set, thus attachments are not supported.
- `getAttachmentField()`, `getPollContainer()`, `getSettingsContainer()`, `getSmiliesContainer()`, and `getWysiwygField()` can be used to get the different components of the wysiwyg form container once the form has been built.
- `enablePreviewButton($enablePreviewButton)` can be used to set whether the preview button for the message is shown or not.
  By default, the preview button is shown.
  This method is only relevant before the form is built.
  Afterwards, the preview button availability can not be changed.
- `getObjectId()` returns the id of the edited object or `0` if no object is edited.
- `getPreselect()`, `preselect($preselect)` can be used to set the value of the wysiwyg tab menu's `data-preselect` attribute used to determine which tab is preselected.
  By default, the preselect is `'true'` which is used to pre-select the first tab.
- `messageObjectType($messageObjectType)` can be used to set the message object type.
- `pollObjectType($pollObjectType)` can be used to set the poll object type.
  By default, no poll object type is set, thus the poll form field container is not available.
- `supportMentions($supportMentions)` can be used to set if mentions are supported.
  By default, mentions are not supported.
  This method is only relevant before the form is built.
  Afterwards, mention support can only be changed via the wysiwyg form field.
- `supportSmilies($supportSmilies)` can be used to set if smilies are supported.
  By default, smilies are supported.
  This method is only relevant before the form is built.
  Afterwards, smiley availability can only be changed via changing the availability of the smilies form container.

### `WysiwygAttachmentFormField`

`WysiwygAttachmentFormField` provides attachment support for a wysiwyg editor via a tab in the menu below the editor.
This class should not be used directly but only via `WysiwygFormContainer`.
The methods `attachmentHandler(AttachmentHandler $attachmentHandler)` and `getAttachmentHandler()` can be used to set and get the `AttachmentHandler` object that is used for uploaded attachments.

### `WysiwygPollFormContainer`

`WysiwygPollFormContainer` provides poll support for a wysiwyg editor via a tab in the menu below the editor.
This class should not be used directly but only via `WysiwygFormContainer`.
`WysiwygPollFormContainer` contains all form fields that are required to create polls and requires edited objects to implement `IPollContainer`.

The following methods are specific to this form container class:

- `getEndTimeField()` returns the form field to set the end time of the poll once the form has been built.
- `getIsChangeableField()` returns the form field to set if poll votes can be changed once the form has been built.
- `getIsPublicField()` returns the form field to set if poll results are public once the form has been built.
- `getMaxVotesField()` returns the form field to set the maximum number of votes once the form has been built.
- `getOptionsField()` returns the form field to set the poll options once the form has been built.
- `getQuestionField()` returns the form field to set the poll question once the form has been built.
- `getResultsRequireVoteField()` returns the form field to set if viewing the poll results requires voting once the form has been built.
- `getSortByVotesField()` returns the form field to set if the results are sorted by votes once the form has been built.

### `WysiwygSmileyFormContainer`

`WysiwygSmileyFormContainer` provides smiley support for a wysiwyg editor via a tab in the menu below the editor.
This class should not be used directly but only via `WysiwygFormContainer`.
`WysiwygSmileyFormContainer` creates a sub-tab for each smiley category.

#### `WysiwygSmileyFormNode`

`WysiwygSmileyFormNode` is contains the smilies of a specific category.
This class should not be used directly but only via `WysiwygSmileyFormContainer`.

### Example

The following code creates a WYSIWYG editor component for a `message` object property.
As smilies are supported by default and an attachment object type is given, the tab menu below the editor has two tabs: “Smilies” and “Attachments”.
Additionally, mentions and quotes are supported.

```php
WysiwygFormContainer::create('message')
	->label('foo.bar.message')
	->messageObjectType('com.example.foo.bar')
	->attachmentData('com.example.foo.bar')
	->supportMentions()
	->supportQuotes()
```


### `WysiwygFormField`

`WysiwygFormField` is used for wysiwyg editor form fields.
This class should, in general, not be used directly but only via `WysiwygFormContainer`.
The class implements `IAttributeFormField`, `IMaximumLengthFormField`, `IMinimumLengthFormField`, and `IObjectTypeFormNode` and requires an object type of the object type definition `com.woltlab.wcf.message`.
The following methods are specific to this form field class:

- `autosaveId($autosaveId)` and `getAutosaveId()` can be used enable automatically saving the current editor contents in the browser using the given id.
  An empty string signals that autosaving is disabled.
- `lastEditTime($lastEditTime)` and `getLastEditTime()` can be used to set the last time the contents have been edited and saved so that the JavaScript can determine if the contents stored in the browser are older or newer.
  `0` signals that no last edit time has been set.
- `supportAttachments($supportAttachments)` and `supportsAttachments()` can be used to set and check if the form field supports attachments.

    !!! warning "It is not sufficient to simply signal attachment support via these methods for attachments to work. These methods are relevant internally to signal the Javascript code that the editor supports attachments. Actual attachment support is provided by `WysiwygAttachmentFormField`."

- `supportMentions($supportMentions)` and `supportsMentions()` can be used to set and check if the form field supports mentions of other users.

`WysiwygFormField` objects register a [custom form field data processor](validation_data.md#customformfielddataprocessor) to add the relevant simple ACL data array into the `$parameters` array directly using the object property as the array key.


### `TWysiwygFormNode`

All form nodes that need to know the id of the `WysiwygFormField` field should use `TWysiwygFormNode`.
This trait provides `getWysiwygId()` and `wysiwygId($wysiwygId)` to get and set the relevant wysiwyg editor id.



## Application-Specific Form Fields

### WoltLab Suite Forum

#### `MultipleBoardSelectionFormField`

`MultipleBoardSelectionFormField` is used to select multiple forums.
The class implements `IAttributeFormField`, `ICssClassFormField`, and `IImmutableFormField`.

The field supports additional settings:

- `boardNodeList(BoardNodeList $boardNodeList): self` and `getBoardNodeList(): BoardNodeList` are used to set and get the list of board nodes used to render the board selection.
  `boardNodeList(BoardNodeList $boardNodeList): self` will automatically call `readNodeTree()` on the given board node list.
- `categoriesSelectable(bool $categoriesSelectable = true): self` and `areCategoriesSelectable(): bool` are used to set and check if the categories in the board node list are selectable.
  By default, categories are selectable.
  This option is useful if only actual boards, in which threads can be posted, should be selectable but the categories must still be shown so that the overall forum structure is still properly shown.
- `supportExternalLinks(bool $supportExternalLinks): self` and `supportsExternalLinks(): bool` are used to set and check if external links will be shown in the selection list.
  By default, external links are shown.
  Like in the example given before, in cases where only actual boards, in which threads can be posted, are relevant, this option allows to exclude external links.



## Single-Use Form Fields

The following form fields are specific for certain forms and hardly reusable in other contexts.


### `BBCodeAttributesFormField`

`BBCodeAttributesFormField` is a form field for setting the attributes of a BBCode.


### `DevtoolsProjectExcludedPackagesFormField`

`DevtoolsProjectExcludedPackagesFormField` is a form field for setting the excluded packages of a devtools project.


### `DevtoolsProjectInstructionsFormField`

`DevtoolsProjectInstructionsFormField` is a form field for setting the installation and update instructions of a devtools project.


### `DevtoolsProjectOptionalPackagesFormField`

`DevtoolsProjectOptionalPackagesFormField` is a form field for setting the optional packages of a devtools project.


### `DevtoolsProjectRequiredPackagesFormField`

`DevtoolsProjectRequiredPackagesFormField` is a form field for setting the required packages of a devtools project.
