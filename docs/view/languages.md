# Languages

WoltLab Suite offers full i18n support with its integrated language system,
including dynamic phrases using template scripting.

Phrases are deployed using the [language](../package/pip/language.md) package
installation plugin.

## Naming Conventions

This section contains general rules for naming language items.
API-specific rules are listed on the relevant API page, for example for [comments](../php/api/comments.md#language-items).

### Form Fields

If you have an application `foo` and a database object `foo\data\bar\Bar` with a property `baz` that can be set via a form field, the name of the corresponding language item has to be `foo.bar.baz`.
If you want to add an additional description below the field, use the language item `foo.bar.baz.description`.

### Error Messages

If an error of type `{error type}` for the previously mentioned form field occurs during validation, use the language item `foo.bar.baz.error.{error type}` to describe the error.
Common error types are:

- `invalid` if the entered value does not conform to some special rules.
- `notUnique` if the entered value is required to be unique but is already used by another object.

Generic errors, like an empty field that is required, should use the existing general error messages such as `wcf.global.form.error.empty` instead of duplicating the same text in different language items.

### Confirmation Messages

Whenever possible, use the [built-in confirmation presets](../javascript/components_confirmation.md#available-presets) (or the corresponding [interaction confirmation types](../php/api/interactions.md#confirmation-types)) that already ship with suitable phrases.
If you need a custom confirmation message, phrase it according to the [guidelines for confirmation dialogs](../javascript/components_confirmation.md#proper-wording) and name the language item `foo.bar.action.confirmMessage` for an action `foo.bar.action`.

## Special Phrases

### `wcf.date.dateFormat`

!!! warning "Many characters in the format have a special meaning and will be replaced with date fragments. If you want to include a literal character, you'll have to use the backslash `\` as an escape sequence to indicate that the character should be output as-is rather than being replaced. For example, `Y-m-d` will be output as `2018-03-30`, but `\Y-m-d` will result in `Y-03-30`."

_Defaults to `M jS Y`._

The date format without time using PHP's format characters for the
[`date()`](https://secure.php.net/manual/en/function.date.php) function. This
value is also used inside the JavaScript implementation, where the characters
are mapped to an equivalent representation.

### `wcf.date.timeFormat`

_Defaults to `g:i a`._

The date format that is used to represent a time, but not a date. Please see the
explanation on `wcf.date.dateFormat` to learn more about the format characters.

### `wcf.date.firstDayOfTheWeek`

_Defaults to `0`._

Sets the first day of the week:
* `0` - Sunday
* `1` - Monday
