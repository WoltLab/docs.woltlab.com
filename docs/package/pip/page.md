# Page Package Installation Plugin

Registers page controllers, making them available for selection and configuration, including but not limited to boxes and menus.

## Components

Each item is described as a `<page>` element with the mandatory attribute `identifier` that should follow the naming pattern `<packageIdentifier>.<PageName>`, e.g. `com.woltlab.wcf.MembersList`.

### `<pageType>`

#### `system`

The special `system` type is reserved for pages that pull their properties and content from a registered PHP class. Requires the `<controller>` element.

#### `html`, `text` or `tpl`

Provide arbitrary content, requires the `<content>` element.

### `<controller>`

Fully qualified class name for the controller, must implement `wcf\page\IPage` or `wcf\form\IForm`.

### `<handler>`

Fully qualified class name that can be optionally set to provide additional methods, such as displaying a badge for unread content and verifying permissions per page object id.

### `<name>`

!!! info "The `language` attribute is required and should specify the [ISO-639-1](https://en.wikipedia.org/wiki/ISO_639-1) language code."

The internal name displayed in the admin panel only, can be fully customized by the administrator and is immutable. Only one value is accepted and will be picked based on the site's default language, but you can provide localized values by including multiple `<name>` elements.

### `<parent>`

Sets the default parent page using its internal identifier, this setting controls the breadcrumbs and active menu item hierarchy.

### `<hasFixedParent>`

Pages can be assigned any other page as parent page by default, set to `1` to make the parent setting immutable.

### `<permissions>`

!!! warning "The comma represents a logical `or`, the check is successful if at least one permission is set."

Comma separated list of permission names that will be checked one after another until at least one permission is set.

### `<options>`

!!! warning "The comma represents a logical `or`, the check is successful if at least one option is enabled."

Comma separated list of options that will be checked one after another until at least one option is set.

### `<excludeFromLandingPage>`

Some pages should not be used as landing page, because they may not always be
available and/or accessible to the user. For example, the account management
page is available to logged-in users only and any guest attempting to visit that
page would be presented with a permission denied message.

Set this to `1` to prevent this page from becoming a landing page ever.

### `<content>`

!!! info "The `language` attribute is required and should specify the [ISO-639-1](https://en.wikipedia.org/wiki/ISO_639-1) language code."

#### `<title>`

The title element is required and controls the page title shown to the end users.

#### `<content>`

The content that should be used to populate the page, only used and required if the `pageType` equals `text`, `html` and `tpl`.


## Example

{jinja{ codebox(
  title="page.xml",
  language="xml",
  filepath="package/pip/page.xml"
) }}