# Core Object Package Installation Plugin

Registers `wcf\system\SingletonFactory` objects to be accessible in templates.

## Components

Each item is described as a `<coreobject>` element with the mandatory element `objectname`.

### `<objectname>`

The fully qualified class name of the class.

## Example

{jinja{ codebox(
    "xml",
    "package/pip/coreObject.xml",
    "coreObject.xml"
) }}

This object can be accessed in templates via `$__wcf->getExampleHandler()` (in general: the method name begins with `get` and ends with the unqualified class name).
