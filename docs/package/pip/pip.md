# Package Installation Plugin Package Installation Plugin

Registers new package installation plugins.

## Components

Each package installation plugin is described as an `<pip>` element with a `name` attribute and a PHP classname as the text content.

!!! warning "The package installation plugin’s class file must be installed into the `wcf` application and must not include classes outside the `\wcf\*` hierarchy to allow for proper uninstallation!"

## Example

{jinja{ codebox(
  title="packageInstallationPlugin.xml",
  language="xml",
  filepath="package/pip/packageInstallationPlugin.xml"
) }}
