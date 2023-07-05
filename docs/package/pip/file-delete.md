# File Delete Package Installation Plugin

Deletes files installed with the [file](file.md) package installation plugin.

!!! warning "You cannot delete files provided by other packages."


## Components

Each item is described as a `<file>` element with an optional `application`, which behaves like it does for [acp templates](acp-template.md#application).
The file path is relative to the installation of the app to which the file belongs.

## Example

{jinja{ codebox(
    title="fileDelete.xml",
    language="xml",
    filepath="package/pip/fileDelete.xml"
) }}
