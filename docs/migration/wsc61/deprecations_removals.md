# Migrating from WoltLab Suite 6.1 - Deprecations and Removals

With version 6.2, we have deprecated certain components and removed several other components that have been deprecated for many years.

## Deprecations

### PHP

#### Methods

- `wcf\util\DateUtil::format()` ([WoltLab/WCF#6042](https://github.com/WoltLab/WCF/pull/6042/))

## Removals

### PHP

#### Methods

- `wcf\util\CLIUtil::formatTime()` ([WoltLab/WCF#6042](https://github.com/WoltLab/WCF/pull/6042/))
- `wcf\util\CLIUtil::formatDate()` ([WoltLab/WCF#6042](https://github.com/WoltLab/WCF/pull/6042/))

#### Properties

- `wcf\system\option\user\DateUserOptionOutput::$dateFormat` ([WoltLab/WCF#6042](https://github.com/WoltLab/WCF/pull/6042/))

### JavaScript

- `WCF.ImageViewer` ([WoltLab/WCF#6035](https://github.com/WoltLab/WCF/pull/6035/))
