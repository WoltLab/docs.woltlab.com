# Package Installation Plugins

Package Installation Plugins (PIPs) are interfaces to deploy and edit content as well as components.

!!! info "For XML-based PIPs: `<![CDATA[]]>` must be used for language items and page contents. In all other cases it may only be used when necessary."

## Built-In PIPs

| Name | Description |
|------|-------------|
| [aclOption](package_pip_acl-option.md) | Customizable permissions for individual objects |
| [acpMenu](package_pip_acp-menu.md) | Admin panel menu categories and items |
| [acpSearchProvider](package_pip_acp-search-provider.md) | Data provider for the admin panel search |
| [acpTemplate](package_pip_acp-template.md) | Admin panel templates |
| [bbcode](package_pip_bbcode.md) | BBCodes for rich message formatting |
| [box](package_pip_box.md) | Boxes that can be placed anywhere on a page |
| [clipboardAction](package_pip_clipboard_action.md) | Perform bulk operations on marked objects |
| [coreObject](package_pip_core-object.md) | Access Singletons from within the template |
| [cronjob](package_pip_cronjob.md) | Periodically execute code with customizable intervals |
| [eventListener](package_pip_event-listener.md) | Register listeners for the event system |
| [file](package_pip_file.md) | Deploy any type of files with the exception of templates |
| [language](package_pip_language.md) | Language items |
| [mediaProvider](package_pip_media-provider.md) | Detect and convert links to media providers |
| [menu](package_pip_menu.md) | Side-wide and custom per-page menus |
| [menuItem](package_pip_menu-item.md) | Menu items for menus created through the menu PIP |
| [objectType](package_pip_object-type.md) | Flexible type registry based on definitions |
| [objectTypeDefinition](package_pip_object-type-definition.md) | Groups objects and classes by functionality |
| [option](package_pip_option.md) | Side-wide configuration options |
| [page](package_pip_page.md) | Register page controllers and text-based pages |
| [pip](package_pip_pip.md) | Package Installation Plugins |
| [script](package_pip_script.md) | Execute arbitrary PHP code during installation, update and uninstallation |
| [smiley](package_pip_smiley.md) | Smileys |
| [sql](package_pip_sql.md) | Execute SQL instructions using a MySQL-flavored syntax (also see [database PHP API.md)(package_database-php-api.md)) |
| [style](package_pip_style.md) | Style |
| [template](package_pip_template.md) | Frontend templates |
| [templateListener](package_pip_template-listener.md) | Embed template code into templates without altering the original |
| [userGroupOption](package_pip_user-group-option.md) | Permissions for user groups |
| [userMenu](package_pip_user-menu.md) | User menu categories and items |
| [userNotificationEvent](package_pip_user-notification-event.md) | Events of the user notification system |
| [userOption](package_pip_user-option.md) | User settings |
| [userProfileMenu](package_pip_user-profile-menu.md) | User profile tabs |
