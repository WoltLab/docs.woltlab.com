# Script Package Installation Plugin

Execute arbitrary PHP code during installation, update and uninstallation of the package.

!!! warning "You must install the PHP script through the [file package installation plugin](file.md)."

!!! warning "The installation will attempt to delete the script after successful execution."

## Attributes

### `application`

The `application` attribute must have the same value as the `application` attribute of the `file` package installation plugin instruction so that the correct file in the intended application directory is executed.
For further information about the `application` attribute, refer to its documentation on the [acpTemplate package installation plugin page](acp-template.md#application).


## Expected value

The `script`-PIP expects a relative path to a `.php` file.

### Naming convention

The PHP script is deployed by using the [file package installation plugin](file.md).
To prevent it from colliding with other install script (remember: You cannot overwrite files created by another plugin), we highly recommend to make use of these naming conventions:

- Installation: `install_<package>.php` (example: `install_com.woltlab.wbb.php`)
- Update: `update_<package>_<targetVersion>.php` (example: `update_com.woltlab.wbb_5.0.0_pl_1.php`)

`<targetVersion>` equals the version number of the current package being installed.
If you're updating from `1.0.0` to `1.0.1`, `<targetVersion>` should read `1.0.1`.


## Execution environment

The script is included using `include()` within [ScriptPackageInstallationPlugin::run()](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/package/plugin/ScriptPackageInstallationPlugin.class.php#L69).
This grants you access to the class members, including `$this->installation`.

You can retrieve the package id of the current package through `$this->installation->getPackageID()`.
