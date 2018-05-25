---
title: General Data Protection Regulation (GDPR)
sidebar: sidebar
permalink: php_gdpr.html
folder: php
---

## Introduction

The General Data Protection Regulation (GDPR) of the European Union enters into
force on May 25, 2018. It comes with a set of restrictions when handling users'
personal data as well as to provide an interface to export this data on demand.

If you're looking for a guide on the implications of the GDPR and what you will
need or consider to do, please read the article [Implementation of the GDPR](https://www.woltlab.com/article/106-implementation-of-the-gdpr/)
on woltlab.com.

## Including Data in the Export

The `wcf\acp\action\UserExportGdprAction` introduced with WoltLab Suite 3.1.3
already includes the Core itself as well as all official apps, but you'll need to
include any personal data stored for your plugin or app by yourself.

The event `export` is fired before any data is sent out, but after any Core data
has been dumped to the `$data` property.

### Example code

```php
<?php
namespace wcf\system\event\listener;
use wcf\acp\action\UserExportGdprAction;
use wcf\data\user\UserProfile;

class MyUserExportGdprActionListener implements IParameterizedEventListener {
  public function execute(/** @var UserExportGdprAction $eventObj */$eventObj, $className, $eventName, array &$parameters) {
    /** @var UserProfile $user */
    $user = $eventObj->user;

    $eventObj->data['my.fancy.plugin'] = [
      'superPersonalData' => "This text is super personal and should be included in the output",
      'weirdIpAddresses' => $eventObj->exportIpAddresses('app'.WCF_N.'_non_standard_column_names_for_ip_addresses', 'ipAddressColumnName', 'timeColumnName', 'userIDColumnName')
    ];
    $eventObj->exportUserProperties[] = 'shouldAlwaysExportThisField';
    $eventObj->exportUserPropertiesIfNotEmpty[] = 'myFancyField';
    $eventObj->exportUserOptionSettings[] = 'thisSettingIsAlwaysExported';
    $eventObj->exportUserOptionSettingsIfNotEmpty[] = 'someSettingContainingPersonalData';
    $eventObj->ipAddresses['my.fancy.plugin'] = ['wcf'.WCF_N.'_my_fancy_table', 'wcf'.WCF_N.'_i_also_store_ipaddresses_here'];
    $eventObj->skipUserOptions[] = 'thisLooksLikePersonalDataButItIsNot';
    $eventObj->skipUserOptions[] = 'thisIsAlsoNotPersonalDataPleaseIgnoreIt';
  }
}
```

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

{% include links.html %}
