# BBCode Package Installation Plugin

Registers new BBCodes.

## Components

Each bbcode is described as an `<bbcode>` element with the mandatory attribute `name`.
The `name` attribute must contain alphanumeric characters only and is exposed to the user.

### `<htmlopen>`

!!! info "Optional: Must not be provided if the BBCode is being processed a PHP class (`<classname>`)."

The contents of this tag are literally copied into the opening tag of the bbcode.

### `<htmlclose>`

!!! info "Optional: Must not be provided if `<htmlopen>` is not given."

Must match the `<htmlopen>` tag.
Do not provide for self-closing tags.

### `<classname>`

The name of the class providing the bbcode output,
the class has to implement the `wcf\system\bbcode\IBBCode` interface.

BBCodes can be statically converted to HTML during input processing using a
`wcf\system\html\metacode\converter\*MetaConverter` class. This class does not
need to be registered.

### `<wysiwygicon>`

<span class="label label-info">Optional</span>

Name of the Font Awesome icon class or path to a `gif`, `jpg`, `jpeg`, `png`, or `svg` image (placed inside the `icon/` directory) to show in the editor toolbar.

### `<buttonlabel>`

!!! info "Optional: Must be provided if an icon is given."

Explanatory text to show when hovering the icon.

### `<sourcecode>`

!!! warning "Do not set this to `1` if you don't specify a PHP class for processing. You must perform XSS sanitizing yourself!"

If set to `1` contents of this BBCode will not be interpreted,
but literally passed through instead.

### `<isBlockElement>`

Set to `1` if the output of this BBCode is a HTML block element (according to the HTML specification).

### `<attributes>`

Each bbcode is described as an `<attribute>` element with the mandatory attribute `name`.
The `name` attribute is a 0-indexed integer.

#### `<html>`

!!! info "Optional: Must not be provided if the BBCode is being processed a PHP class (`<classname>`)."

The contents of this tag are copied into the opening tag of the bbcode.
`%s` is replaced by the attribute value.

#### `<validationpattern>`

<span class="label label-info">Optional</span>

Defines a regular expression that is used to validate the value of the attribute.

#### `<required>`

<span class="label label-info">Optional</span>

Specifies whether this attribute must be provided.

#### `<usetext>`

<span class="label label-info">Optional</span>
!!! info "Should only be set to `1` for the attribute with name `0`."

Specifies whether the text content of the BBCode should become this attribute's value.

## Example

{jinja{ codebox(
  title="bbcode.xml",
  language="xml",
  filepath="package/pip/bbcode.xml"
) }}
