# ACP Menu Package Installation Plugin

Registers new ACP menu items.

## Components

Each item is described as an `<acpmenuitem>` element with the mandatory attribute `name`.

### `<parent>`

<span class="label label-info">Optional</span>

The item’s parent item.

### `<showorder>`

<span class="label label-info">Optional</span>

Specifies the order of this item within the parent item.

### `<controller>`

The fully qualified class name of the target controller.
If not specified this item serves as a category.

### `<link>`

Additional components if `<controller>` is set,
the full external link otherwise.

### `<icon>`

!!! info "Use an icon only for top-level and 4th-level items."

Name of the Font Awesome icon class.

### `<options>`

<span class="label label-info">Optional</span>

The options element can contain a comma-separated list of options of which at least one needs to be enabled for the tab to be shown.

### `<permissions>`

<span class="label label-info">Optional</span>

The permissions element can contain a comma-separated list of permissions of which the active user needs to have at least one for the tab to be shown.

## Example

{jinja{ codebox(
    "xml",
    "package/pip/acpMenu.xml",
    "acpMenu.xml"
) }}
