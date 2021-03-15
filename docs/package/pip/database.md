# Database Package Installation Plugin

!!! info "Available since WoltLab Suite 5.4."

Update the database layout using [the PHP API](../database-php-api.md).

!!! warning "You must install the PHP script through the [file package installation plugin](file.md)."

!!! warning "The installation will attempt to delete the script after successful execution."

## Attributes

### `application`

The `application` attribute must have the same value as the `application` attribute of the `file` package installation plugin instruction so that the correct file in the intended application directory is executed.
For further information about the `application` attribute, refer to its documentation on the [acpTemplate package installation plugin page](acp-template.md#application).


## Expected value

The `database`-PIP expects a relative path to a `.php` file that returns an array of `DatabaseTable` objects.

### Naming convention

The PHP script is deployed by using the [file package installation plugin](file.md).
To prevent it from colliding with other install script (remember: You cannot overwrite files created by another plugin), we highly recommend to make use of these naming conventions:

- Installation: `acp/database/install_<package>_<version>.php` (example: `acp/database/install_com.woltlab.wbb_5.4.0.php`)
- Update: `acp/database/update_<package>_<targetVersion>.php` (example: `acp/database/update_com.woltlab.wbb_5.4.1.php`)

`<targetVersion>` equals the version number of the current package being installed.
If you're updating from `1.0.0` to `1.0.1`, `<targetVersion>` should read `1.0.1`.

If you run multiple update scripts, you can append additional information in the filename.


## Execution environment

The script is included using `include()` within [DatabasePackageInstallationPlugin::updateDatabase()](https://github.com/WoltLab/WCF/blob/148da7ceaf3a80bfc91447635b0299089ddf7015/wcfsetup/install/files/lib/system/package/plugin/DatabasePackageInstallationPlugin.class.php#L69).
