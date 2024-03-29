# General Data Protection Regulation (GDPR)

## Introduction

The General Data Protection Regulation (GDPR) of the European Union enters into
force on May 25, 2018. It comes with a set of restrictions when handling users'
personal data as well as to provide an interface to export this data on demand.

If you're looking for a guide on the implications of the GDPR and what you will
need or consider to do, please read the article [Implementation of the GDPR](https://www.woltlab.com/article/106-implementation-of-the-gdpr/)
on woltlab.com.

## Including Data in the Export

The `wcf\acp\action\UserExportGdprAction` already includes WoltLab Suite Core itself as well as all official apps, but you'll need to include any personal data stored for your plugin or app by yourself.

The event `export` is fired before any data is sent out, but after any Core data
has been dumped to the `$data` property.

### Example code

{jinja{ codebox(
  title="files/lib/system/event/listener/MyUserExportGdprActionListener.class.php",
  language="php",
  filepath="php/gdpr/MyUserExportGdprActionListener.class.php"
) }}

### `$data`

Contains the entire data that will be included in the exported JSON file, some
fields may already exist (such as `'com.woltlab.wcf'`) and while you may add or
edit any fields within, you should restrict yourself to only append data from
your plugin or app.

### `$exportUserProperties`

Only a whitelist of  columns in `wcfN_user` is exported by default, if your plugin
or app adds one or more columns to this table that do hold personal data, then
you will have to append it to this array. The listed properties will always be
included regardless of their content.

### `$exportUserPropertiesIfNotEmpty`

Only a whitelist of  columns in `wcfN_user` is exported by default, if your plugin
or app adds one or more columns to this table that do hold personal data, then
you will have to append it to this array. Empty values will not be added to the
output.

### `$exportUserOptionSettings`

Any user option that exists within a `settings.*` category is automatically
excluded from the export, with the notable exception of the `timezone` option.
You can opt-in to include your setting by appending to this array, if it contains
any personal data. The listed settings are always included regardless of their
content.

### `$exportUserOptionSettingsIfNotEmpty`

Any user option that exists within a `settings.*` category is automatically
excluded from the export, with the notable exception of the `timezone` option.
You can opt-in to include your setting by appending to this array, if it contains
any personal data.

### `$ipAddresses`

List of database table names per package identifier that contain ip addresses.
The contained ip addresses will be exported when the ip logging module is enabled.

It expects the database table to use the column names `ipAddress`, `time` and
`userID`. If your table does not match this pattern for whatever reason, you'll
need to manually probe for `LOG_IP_ADDRESS` and then call `exportIpAddresses()`
to retrieve the list. Afterwards you are responsible to append these ip addresses
to the `$data` array to have it exported.

### `$skipUserOptions`

All user options are included in the export by default, unless they start with
`can*` or `admin*`, or are blacklisted using this array. You should append any
of your plugin's or app's user option that should not be exported, for example
because it does not contain personal data, such as internal data.
