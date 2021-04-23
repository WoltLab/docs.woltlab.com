# Object Type Definition Package Installation Plugin

Registers an object type definition.
An object type definition is a blueprint for a certain behaviour that is particularized by [objectTypes](object-type.md).
As an example: Tags can be attached to different types of content (such as forum posts or gallery images).
The bulk of the work is implemented in a generalized fashion, with all the tags stored in a single database table.
Certain things, such as permission checking, need to be particularized for the specific type of content, though.
Thus tags (or rather “taggable content”) are registered as an object type definition.
Posts are then registered as an object type, implementing the “taggable content” behaviour.

Other types of object type definitions include attachments, likes, polls, subscriptions, or even the category system.

## Components

Each item is described as a `<definition>` element with the mandatory child `<name>` that should follow the naming pattern `<packageIdentifier>.<definition>`, e.g. `com.woltlab.wcf.example`.

### `<interfacename>`

<span class="label label-info">Optional</span>

The name of the PHP interface [objectTypes](object-type.md) have to implement.

## Example

{jinja{ codebox(
    "xml",
    "package/pip/objectTypeDefinition.xml",
    "objectTypeDefinition.xml"
) }}
