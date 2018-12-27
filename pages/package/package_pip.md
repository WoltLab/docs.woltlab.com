---
title: Package Installation Plugins
sidebar: sidebar
permalink: package_pip.html
folder: package
---

Package Installation Plugins (PIPs) are interfaces to deploy and edit content as well as components.

{% include callout.html content="For XML-based PIPs: `<![CDATA[]]>` must be used for language items and page contents. In all other cases it may only be used when necessary." type="info" %}

## Built-In PIPs

| Name | Description |
|------|-------------|
| [aclOption][package_pip_acl-option] | Customizable permissions for individual objects |
| [acpMenu][package_pip_acp-menu] | Admin panel menu categories and items |
| [acpSearchProvider][package_pip_acp-search-provider] | Data provider for the admin panel search |
| [acpTemplate][package_pip_acp-template] | Admin panel templates |
| [bbcode][package_pip_bbcode] | BBCodes for rich message formatting |
| [box][package_pip_box] | Boxes that can be placed anywhere on a page |
| [clipboardAction][package_pip_clipboard_action] | Perform bulk operations on marked objects |
| [coreObject][package_pip_core-object] | Access Singletons from within the template |
| [cronjob][package_pip_cronjob] | Periodically execute code with customizable intervals |
| [eventListener][package_pip_event-listener] | Register listeners for the event system |
| [file][package_pip_file] | Deploy any type of files with the exception of templates |
| [language][package_pip_language] | Language items |
| [mediaProvider][package_pip_media-provider] | Detect and convert links to media providers |
| [menu][package_pip_menu] | Side-wide and custom per-page menus |
| [menuItem][package_pip_menu-item] | Menu items for menus created through the menu PIP |
| [objectType][package_pip_object-type] | Flexible type registry based on definitions |
| [objectTypeDefinition][package_pip_object-type-definition] | Groups objects and classes by functionality |
| [option][package_pip_option] | Side-wide configuration options |
| [page][package_pip_page] | Register page controllers and text-based pages |
| [pip][package_pip_pip] | Package Installation Plugins |
| [script][package_pip_script] | Execute arbitrary PHP code during installation, update and uninstallation |
| [smiley][package_pip_smiley] | Smileys |
| [sql][package_pip_sql] | Execute SQL instructions using a MySQL-flavored syntax |
| [style][package_pip_style] | Style |
| [template][package_pip_template] | Frontend templates |
| [templateListener][package_pip_template-listener] | Embed template code into templates without altering the original |
| [userGroupOption][package_pip_user-group-option] | Permissions for user groups |
| [userMenu][package_pip_user-menu] | User menu categories and items |
| [userNotificationEvent][package_pip_user-notification-event] | Events of the user notification system |
| [userOption][package_pip_user-option] | User settings |
| [userProfileMenu][package_pip_user-profile-menu] | User profile tabs |

{% include links.html %}
