# Media Provider Package Installation Plugin

!!! info "Available since WoltLab Suite 3.1"

Media providers are responsible to detect and convert links to a 3rd party service inside messages.

## Components

Each item is described as a `<provider>` element with the mandatory attribute `name` that should equal the lower-cased provider name. If a provider provides multiple components that are (largely) unrelated to each other, it is recommended to use a dash to separate the name and the component, e. g. `youtube-playlist`.

### `<title>`

The title is displayed in the administration control panel and is only used there, the value is neither localizable nor is it ever exposed to regular users.

### `<regex>`

The regular expression used to identify links to this provider, it must not contain anchors or delimiters. It is strongly recommended to capture the primary object id using the `(?P<ID>...)` group.

### `<className>`

!!! warning "`<className>` and `<html>` are mutually exclusive."

PHP-Callback-Class that is invoked to process the matched link in case that additional logic must be applied that cannot be handled through a simple replacement as defined by the `<html>` element.

The callback-class must implement the interface `\wcf\system\bbcode\media\provider\IBBCodeMediaProvider`.

### `<html>`

!!! warning "`<className>` and `<html>` are mutually exclusive."

Replacement HTML that gets populated using the captured matches in `<regex>`, variables are accessed as `{$VariableName}`. For example, the capture group `(?P<ID>...)` is accessed using `{$ID}`.

## Example

{jinja{ codebox(
  title="mediaProvider.xml",
  language="xml",
  filepath="package/pip/mediaProvider.xml"
) }}
