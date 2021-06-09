# Template Delete Package Installation Plugin

!!! info "Available since WoltLab Suite 5.5."

Deletes frontend templates installed with the [acpTemplate](pip/acp-template.md) package installation plugin.

!!! warning "You cannot delete templates provided by other packages."


## Components

Each item is described as a `<template>` element with an optional `application`, which behaves like it does for [acp templates](acp-template.md#application).
The templates are identified by their name like when adding [template listeners](template-listener.md), i.e. by the file name without the `.tpl` file extension.

## Example

{jinja{ codebox(
    title="templateDelete.xml",
    language="xml",
    filepath="package/pip/templateDelete.xml"
) }}
