# Migrating from WoltLab Suite 5.5 - Deprecations and Removals

With version 5.6, we have deprecated certain components and removed several other components that have been deprecated for many years.



## Deprecations

### PHP

#### Classes

- `wcf\SensitiveArgument` ([WoltLab/WCF#4802](https://github.com/WoltLab/WCF/pull/4802))

#### Methods

- `wcf\data\package\update\server\PackageUpdateServer::attemptSecureConnection()` ([WoltLab/WCF#4790](https://github.com/WoltLab/WCF/pull/4790))
- `wcf\data\package\update\server\PackageUpdateServer::isValidServerURL()` ([WoltLab/WCF#4790](https://github.com/WoltLab/WCF/pull/4790))
- `wcf\system\io\RemoteFile::disableSSL()` ([WoltLab/WCF#4790](https://github.com/WoltLab/WCF/pull/4790))
- `wcf\system\io\RemoteFile::supportsSSL()` ([WoltLab/WCF#4790](https://github.com/WoltLab/WCF/pull/4790))
- `wcf\util\StringUtil::convertEncoding()` ([WoltLab/WCF#4800](https://github.com/WoltLab/WCF/pull/4800))
- `wcf\system\WCF::getFavicon()` ([WoltLab/WCF#4785](https://github.com/WoltLab/WCF/pull/4785))
- `wcf\system\WCF::useDesktopNotifications()` ([WoltLab/WCF#4785](https://github.com/WoltLab/WCF/pull/4785))
- `wcf\system\WCF::getActivePath()` ([WoltLab/WCF#4827](https://github.com/WoltLab/WCF/pull/4827))

#### Properties

#### Constants

#### Functions

#### Options

### JavaScript

### Database Tables

### Templates

#### Template Modifiers

#### Template Events

### Miscellaneous

## Removals

### PHP

#### Classes

- `wcf\system\option\DesktopNotificationApplicationSelectOptionType` ([WoltLab/WCF#4785](https://github.com/WoltLab/WCF/pull/4785))

#### Methods

- The `$forceHTTP` parameter of `wcf\data\package\update\server\PackageUpdateServer::getListURL()` ([WoltLab/WCF#4790](https://github.com/WoltLab/WCF/pull/4790))
- The `$forceHTTP` parameter of `wcf\system\package\PackageUpdateDispatcher::getPackageUpdateXML()` ([WoltLab/WCF#4790](https://github.com/WoltLab/WCF/pull/4790))
- `wcf\system\WCFACP::initPackage()` ([WoltLab/WCF#4794](https://github.com/WoltLab/WCF/pull/4794))
- `wcf\system\WCFACP::getFrontendMenu()` ([WoltLab/WCF#4812](https://github.com/WoltLab/WCF/pull/4812))
- `wcf\system\request\Request::execute()` ([WoltLab/WCF#4820](https://github.com/WoltLab/WCF/pull/4820))
- `wcf\system\request\Request::getPageType()` ([WoltLab/WCF#4822](https://github.com/WoltLab/WCF/pull/4822))
- `wcf\system\request\Request::getPageType()` ([WoltLab/WCF#4822](https://github.com/WoltLab/WCF/pull/4822))
- The `$dbNumber` parameter of `wcf\system\WCFSetup::getConflictedTables()` ([WoltLab/WCF#4791](https://github.com/WoltLab/WCF/pull/4791))

#### Properties

- `wcf\system\appliation\ApplicationHandler::$isMultiDomain` ([WoltLab/WCF#4785](https://github.com/WoltLab/WCF/pull/4785))
- `wcf\system\template\TemplateScriptingCompiler::$disabledPHPFunctions` ([WoltLab/WCF#4788](https://github.com/WoltLab/WCF/pull/4788))
- `wcf\system\template\TemplateScriptingCompiler::$enterpriseFunctions` ([WoltLab/WCF#4788](https://github.com/WoltLab/WCF/pull/4788))
- `wcf\system\WCF::$forceLogout` ([WoltLab/WCF#4799](https://github.com/WoltLab/WCF/pull/4799))

#### Constants

#### Options

- `HTTP_SEND_X_FRAME_OPTIONS` ([WoltLab/WCF#4786](https://github.com/WoltLab/WCF/pull/4786))
- `DESKTOP_NOTIFICATION_PACKAGE_ID` ([WoltLab/WCF#4785](https://github.com/WoltLab/WCF/pull/4785))

#### Files

### JavaScript

### Phrases

### Templates

#### Templates

#### Template Events

- `headInclude::javascriptLanguageImport` ([WoltLab/WCF#4801](https://github.com/WoltLab/WCF/pull/4801))
- `headInclude::javascriptInclude` ([WoltLab/WCF#4801](https://github.com/WoltLab/WCF/pull/4801))
- `headInclude::javascriptInit` ([WoltLab/WCF#4801](https://github.com/WoltLab/WCF/pull/4801))

### Miscellaneous

- The use of a non-`1` `WCF_N` in WCFSetup. ([WoltLab/WCF#4791](https://github.com/WoltLab/WCF/pull/4791))
- The global option to set a specific style with a request parameter (`$_REQUEST['styleID']`) is removed. ([WoltLab/WCF#4533](https://github.com/WoltLab/WCF/pull/4533))
- Using non-standard ports for package servers is no longer supported. ([WoltLab/WCF#4790](https://github.com/WoltLab/WCF/pull/4790))
- Using the insecure `http://` scheme for package servers is no longer supported. The use of `https://` is enforced. ([WoltLab/WCF#4790](https://github.com/WoltLab/WCF/pull/4790))
