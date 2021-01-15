# Migrating from WSC 3.1 - Form Builder

## Example: Two Text Form Fields

As the first example, the pre-WoltLab Suite Core 5.2 versions of the forms to add and edit persons from the [first part of the tutorial series](../../tutorial/series/part_1.md) will be updated to the new form builder API.
This form is the perfect first examples as it is very simple with only two text fields whose only restriction is that they have to be filled out and that their values may not be longer than 255 characters each.

As a reminder, here are the two relevant PHP files and the relevant template file:

```php
--8<-- "migration/wsc-31/formBuilder/PersonAddForm_old.class.php"
```

```php
--8<-- "migration/wsc-31/formBuilder/PersonEditForm_old.class.php"
```

```php
--8<-- "migration/wsc-31/formBuilder/personAdd_old.tpl"
```

Updating the template is easy as the complete form is replace by a single line of code:

```php
--8<-- "migration/wsc-31/formBuilder/personAdd_new.tpl"
```

`PersonEditForm` also becomes much simpler:
only the edited `Person` object must be read:

```php
--8<-- "migration/wsc-31/formBuilder/PersonEditForm_new.class.php"
```

Most of the work is done in `PersonAddForm`:

```php
--8<-- "migration/wsc-31/formBuilder/PersonAddForm_new.class.php"
```

But, as you can see, the number of lines almost decreased by half.
All changes are due to extending `AbstractFormBuilderForm`:

- `$formAction` is added and set to `create` as the form is used to create a new person.
  In the edit form, `$formAction` has not to be set explicitly as it is done automatically if a `$formObject` is set.
- `$objectActionClass` is set to `PersonAction::class` and is the class name of the used `AbstractForm::$objectAction` object to create and update the `Person` object.
- `AbstractFormBuilderForm::createForm()` is overridden and the form contents are added:
  a form container representing the `div.section` element from the old version and the two form fields with the same ids and labels as before.
  The contents of the old `validate()` method is put into two method calls:
  `required()` to ensure that the form is filled out and `maximumLength(255)` to ensure that the names are not longer than 255 characters.
