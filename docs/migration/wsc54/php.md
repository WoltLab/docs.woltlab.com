# Migrating from WSC 5.4 - PHP

## File Deletion Package Installation Plugin

Three new package installation plugins have been added to delete ACP templates with [acpTemplateDelete](../../package/pip/acp-template-delete.md), files with [fileDelete](../../package/pip/file-delete.md), and templates with [templateDelete](../../package/pip/template-delete.md).


## Language Package Installation Plugin

[WoltLab/WCF#4261](https://github.com/WoltLab/WCF/pull/4261) has added support for deleting existing phrases with the `language` package installation plugin.

The current structure of the language XML files

{jinja{ codebox(
    language="xml",
    title="language/en.xml",
    filepath="migration/wsc54/en_old.xml",
) }}

is deprecated and should be replaced with the new structure with an explicit `<import>` element like in the other package installation plugins:

{jinja{ codebox(
    language="xml",
    title="language/en.xml",
    filepath="migration/wsc54/en_new.xml",
) }}

Additionally, to now also support deleting phrases with this package installation plugin, support for a `<delete>` element has been added: 

{jinja{ codebox(
    language="xml",
    title="language/en.xml",
    filepath="migration/wsc54/en_new_with_delete.xml",
) }}

Note that when deleting phrases, the category does not have to be specified because phrase identifiers are unique globally.

!!! warning "Mixing the old structure and the new structure is not supported and will result in an error message during the import!"
