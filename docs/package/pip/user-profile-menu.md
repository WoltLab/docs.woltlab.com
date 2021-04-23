# User Profile Menu Package Installation Plugin

Registers new user profile tabs.

## Components

Each tab is described as an `<userprofilemenuitem>` element with the mandatory attribute `name`.

### `<classname>`

The name of the class providing the tab’s behaviour,
the class has to implement the `wcf\system\menu\user\profile\content\IUserProfileMenuContent` interface.

### `<showorder>`

<span class="label label-info">Optional</span>

Determines at which position of the tab list the tab is shown.

### `<options>`

<span class="label label-info">Optional</span>

The options element can contain a comma-separated list of options of which at least one needs to be enabled for the tab to be shown.

### `<permissions>`

<span class="label label-info">Optional</span>

The permissions element can contain a comma-separated list of permissions of which the active user needs to have at least one for the tab to be shown.

## Example

{jinja{ codebox(
    "xml",
    "package/pip/userProfileMenu.xml",
    "userProfileMenu.xml"
) }}
