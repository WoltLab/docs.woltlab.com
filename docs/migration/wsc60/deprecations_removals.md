# Migrating from WoltLab Suite 6.0 - Deprecations and Removals

With version 6.1, we have deprecated certain components and removed several other components that have been deprecated for many years.

## Deprecations

### PHP

#### Classes

- `wcf\system\exception\ValidateActionException`
- `wcf\system\bbcode\HtmlBBCodeParser` ([WoltLab/WCF#5874](https://github.com/WoltLab/WCF/pull/5874/))

#### Methods

- `wcf\system\session\SessionHandler::resetSessions()` ([WoltLab/WCF#3767](https://github.com/WoltLab/WCF/pull/3767))
- `wcf\system\comment\manager\ICommentManager::canModerate()` ([WoltLab/WCF#5852](https://github.com/WoltLab/WCF/pull/5852/))

### JavaScript

- `WCF.User.Action.Follow` ([WoltLab/WCF#5747](https://github.com/WoltLab/WCF/pull/5747))
- `WCF.User.Registration.Validation` ([WoltLab/WCF#5716](https://github.com/WoltLab/WCF/pull/5716))
- `WCF.User.Registration.Validation.Username` ([WoltLab/WCF#5716](https://github.com/WoltLab/WCF/pull/5716))
- `WCF.User.Registration.Validation.EmailAddress` ([WoltLab/WCF#5716](https://github.com/WoltLab/WCF/pull/5716))
- `WCF.User.RecentActivityLoader` ([WoltLab/WCF#5804](https://github.com/WoltLab/WCF/pull/5804))
- `WoltLabSuite/Core/Ui/User/Activity/Recent` ([WoltLab/WCF#5804](https://github.com/WoltLab/WCF/pull/5804))

## Removals

### PHP

#### Classes

- `wcf\data\spider\Spider` ([WoltLab/WCF#5823](https://github.com/WoltLab/WCF/pull/5823))
- `wcf\data\spider\SpiderAction` ([WoltLab/WCF#5823](https://github.com/WoltLab/WCF/pull/5823))
- `wcf\data\spider\SpiderEditor` ([WoltLab/WCF#5823](https://github.com/WoltLab/WCF/pull/5823))
- `wcf\data\spider\SpiderList` ([WoltLab/WCF#5823](https://github.com/WoltLab/WCF/pull/5823))
- `wcf\system\cache\SpiderCacheBuilder` ([WoltLab/WCF#5823](https://github.com/WoltLab/WCF/pull/5823))
- `wcf\system\cronjob\RefreshSearchRobotsCronjob` ([WoltLab/WCF#5823](https://github.com/WoltLab/WCF/pull/5823))

### JavaScript

- `WCF.User.Registration.LostPassword`
- `WCF.Message.BBCode.CodeViewer`
- `WCF.ACL` ([WoltLab/WCF#5860](https://github.com/WoltLab/WCF/pull/5860))
