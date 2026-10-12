# User Option Package Installation Plugin

Registers new user options (profile fields / user settings).

The input field and the output of a user option are provided by [form options](../../php/api/form_builder/form_options.md), the same infrastructure that is used for contact form options and other custom fields.
User options of the `profile` category tree are also automatically available as filters in the user list of the ACP, in the members list (if they are `searchable`) and in object filters, e.g. for ads, notices and automatic user group assignments.

## Category Components

Each category is described as an `<category>` element with the mandatory attribute `name`.

### `<parent>`

<span class="label label-info">Optional</span>

The category’s parent category.

### `<showorder>`

<span class="label label-info">Optional</span>

Specifies the order of this category within the parent category.

### `<options>`

<span class="label label-info">Optional</span>

The options element can contain a comma-separated list of options of which at least one needs to be enabled for the category to be shown.

### `<permissions>`

<span class="label label-info">Optional</span>

The permissions element can contain a comma-separated list of permissions of which the active user needs to have at least one for the category to be shown.

## Option Components

Each option is described as an `<option>` element with the mandatory attribute `name`.

### `<categoryname>`

The option’s category.

### `<optiontype>`

The id of the [form option](../../php/api/form_builder/form_options.md) used for this option.
The following form options are available out of the box:

`boolean`, `checkboxes`, `currency`, `date`, `email`, `float`, `icon`, `integer`, `radioButton`, `rating`, `select`, `sourceCode`, `text`, `textarea`, `url`, `wysiwyg`

Additionally, the following types are exclusive to user options:

| Type          | Description |
| ------------- | ----------- |
| `socialMedia` | Profile name on a social media platform, see `<profilenamepattern>` and `<profileurlpattern>`. |
| `birthday`    | Date of birth that respects the privacy setting for the year of birth and provides an age filter. |
| `timezone`    | Reserved for the built-in time zone setting. |

Additional types can be provided by registering a custom form option.

The legacy types of WoltLab Suite 6.2 and earlier are converted automatically:

| Legacy Type   | Form Option   |
| ------------- | ------------- |
| `aboutMe`     | `wysiwyg`     |
| `message`     | `wysiwyg`     |
| `multiSelect` | `checkboxes`  |
| `URL`         | `url`         |
| `labeledUrl`  | `socialMedia` |

### `<defaultvalue>`

<span class="label label-info">Optional</span>

The value that is set for new users.
Values of `checkboxes` options are separated by commas.

### `<validationpattern>`

<span class="label label-info">Optional</span>

Defines a regular expression that is used to validate the value of a free form option (such as `text`).

### `<selectoptions>`

<span class="label label-info">Optional</span>

Defines the selectable values of `select`, `radioButton` and `checkboxes` options.
Each line contains one value in the format `{value}:{label}`, the label can be a language item.
If a line does not contain a colon, the value is also used as the label.

### `<profilenamepattern>`

<span class="label label-info">Optional</span>

Regular expression without delimiters that is used to validate the profile name of `socialMedia` options.

### `<profileurlpattern>`

<span class="label label-info">Optional</span>

The URL of the profile of `socialMedia` options, `%s` is replaced with the profile name entered by the user.
The pattern must contain `%s` exactly once and must be an absolute `http(s)` URL.

### `<showorder>`

<span class="label label-info">Optional</span>

Specifies the order of this option within the category.

### `<required>`

<span class="label label-info">Optional</span>

Requires that a value is provided.

### `<askduringregistration>`

<span class="label label-info">Optional</span>

If set to `1` the field is shown during user registration in the frontend.

### `<editable>`

<span class="label label-info">Optional</span>

Bitfield with the following options (constants in `wcf\data\user\option\UserOption`)

| Name                                     | Value |
| ---------------------------------------- | ----- |
| EDITABILITY_OWNER                        | 1     |
| EDITABILITY_ADMINISTRATOR                | 2     |
| EDITABILITY_OWNER_DURING_REGISTRATION    | 4     |

### `<visible>`

<span class="label label-info">Optional</span>

Bitfield with the following options (constants in `wcf\data\user\option\UserOption`)

| Name                     | Value |
| ------------------------ | ----- |
| VISIBILITY_OWNER         | 1     |
| VISIBILITY_ADMINISTRATOR | 2     |
| VISIBILITY_REGISTERED    | 4     |
| VISIBILITY_GUEST         | 8     |

### `<searchable>`

<span class="label label-info">Optional</span>

If set to `1` the option is available as a filter in the members list.
The filters of the user list in the ACP and the object filters include all options of the `profile` category tree regardless of this setting.

### `<isdisabled>`

<span class="label label-info">Optional</span>

If set to `1` the option is disabled after the installation.

### `<options>`

<span class="label label-info">Optional</span>

The options element can contain a comma-separated list of options of which at least one needs to be enabled for the option to be shown.

### `<permissions>`

<span class="label label-info">Optional</span>

The permissions element can contain a comma-separated list of permissions of which the active user needs to have at least one for the option to be shown.

### Unsupported Fields

The fields `outputclass`, `enableoptions`, `contentpattern` and `messageObjectType` as well as arbitrary additional fields are no longer supported since WoltLab Suite 6.3 and are ignored.

## Language Items

All relevant language items have to be put into the `wcf.user.option` language item category.
The language items are synchronized into the localized storage of the user options during the installation.

### Categories

If you install a category named `example`, you have to provide the language item `wcf.user.option.category.example`, which is used when displaying the options.

### Options

If you install an option named `exampleOption`, you have to provide the language item `wcf.user.option.exampleOption`, which is used as a label for setting the option value.
If you want to provide an optional description of the option, you have to provide the language item `wcf.user.option.exampleOption.description`.

## Example

{jinja{ codebox(
  title="userOption.xml",
  language="xml",
  filepath="package/pip/userOption.xml"
) }}
