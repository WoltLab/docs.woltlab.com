# ACL Option Package Installation Plugin

Add customizable permissions for individual objects.

## Option Components

Each acl option is described as an `<option>` element with the mandatory attribute `name`.

### `<categoryname>`

<span class="label label-info">Optional</span>

The name of the acl option category to which the option belongs.

### `<objecttype>`

The name of the acl object type (of the object type definition `com.woltlab.wcf.acl`).


## Category Components

Each acl option category is described as an `<category>` element with the mandatory attribute `name`  that should follow the naming pattern `<permissionName>` or `<permissionType>.<permissionName>`, with `<permissionType>` generally having `user` or `mod` as value.

### `<objecttype>`

The name of the acl object type (of the object type definition `com.woltlab.wcf.acl`).


## Example

{jinja{ codebox(
  title="aclOption.xml",
  language="xml",
  filepath="package/pip/aclOption.xml"
) }}
