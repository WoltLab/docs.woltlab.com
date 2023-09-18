# User Menu Package Installation Plugin

Registers new user menu items.

## Components

Each item is described as an `<usermenuitem>` element with the mandatory attribute `name`.

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

### `<iconclassname>`

!!! info "Use an icon only for top-level items."

Specifiy the name of the Font Awesome icon.
For regular icons, the solid variant can be requested with the attribute `solid="true"`.

Brand icons are supported using the separate attribute `type="brand"`.

### `<options>`

<span class="label label-info">Optional</span>

The options element can contain a comma-separated list of options of which at least one needs to be enabled for the menu item to be shown.

### `<permissions>`

<span class="label label-info">Optional</span>

The permissions element can contain a comma-separated list of permissions of which the active user needs to have at least one for the menu item to be shown.

### `<classname>`

The name of the class providing the user menu item’s behaviour,
the class has to implement the `wcf\system\menu\user\IUserMenuItemProvider` interface.



## Example

{jinja{ codebox(
  title="userMenu.xml",
  language="xml",
  filepath="package/pip/userMenu.xml"
) }}
