# Object Type Package Installation Plugin

Registers an object type.
Read about object types in the [objectTypeDefinition](object-type-definition.md) PIP.

## Components

Each item is described as a `<type>` element with the mandatory child `<name>` that should follow the naming pattern `<packageIdentifier>.<definition>`, e.g. `com.woltlab.wcf.example`.

### `<definitionname>`

The `<name>` of the [objectTypeDefinition](object-type-definition.md).

### `<classname>`

The name of the class providing the object types's behaviour,
the class has to implement the `<interfacename>` interface of the object type definition.

### `<*>`

<span class="label label-info">Optional</span>

Additional fields may be defined for specific definitions of object types.
Refer to the documentation of these for further explanation.

## Example

{jinja{ codebox(
  title="objectType.xml",
  language="xml",
  filepath="package/pip/objectType.xml"
) }}
