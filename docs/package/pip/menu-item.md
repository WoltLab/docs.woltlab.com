# Menu Item Package Installation Plugin

Adds menu items to existing menus.

## Components

Each item is described as an `<item>` element with the mandatory attribute `identifier` that should follow the naming pattern `<packageIdentifier>.<PageName>`, e.g. `com.woltlab.wcf.Dashboard`.

### `<menu>`

The target menu that the item should be added to, requires the internal identifier set by creating a menu through the [menu.xml](menu.md).

### `<title>`

!!! info "The `language` attribute is required and should specify the [ISO-639-1](https://en.wikipedia.org/wiki/ISO_639-1) language code."

The title is displayed as the link title of the menu item and can be fully customized by the administrator, thus is immutable after deployment. Supports multiple `<title>` elements to provide localized values.

### `<page>`

The page that the link should point to, requires the internal identifier set by creating a page through the [page.xml](page.md).

## Example

{jinja{ codebox(
  title="menuItem.xml",
  language="xml",
  filepath="package/pip/menuItem.xml"
) }}
