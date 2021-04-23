# Menu Package Installation Plugin

Deploy and manage menus that can be placed anywhere on the site.

## Components

Each item is described as a `<menu>` element with the mandatory attribute `identifier` that should follow the naming pattern `<packageIdentifier>.<MenuName>`, e.g. `com.woltlab.wcf.MainMenu`.

### `<title>`

!!! info "The `language` attribute is required and should specify the [ISO-639-1](https://en.wikipedia.org/wiki/ISO_639-1) language code."

The internal name displayed in the admin panel only, can be fully customized by the administrator and is immutable. Only one value is accepted and will be picked based on the site's default language, but you can provide localized values by including multiple `<title>` elements.

### `<box>`

The following elements of the [box PIP](box.md) are supported, please refer to the documentation to learn more about them:

* `<position>`
* `<showHeader>`
* `<visibleEverywhere>`
* `<visibilityExceptions>`
* `cssClassName`

## Example

{jinja{ codebox(
    "xml",
    "package/pip/menu.xml",
    "menu.xml"
) }}
