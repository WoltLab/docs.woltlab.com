# Migrating from WoltLab Suite 6.1 - Deprecations and Removals

With version 6.2, we have deprecated certain components and removed several other components that have been deprecated for many years.

## Deprecations

### PHP

#### Methods

- `wcf\util\DateUtil::format()` ([WoltLab/WCF#6042](https://github.com/WoltLab/WCF/pull/6042/))
- `wcf\data\smiley\category\SmileyCategoryAction::getSmilies()` ([WoltLab/WCF#6115](https://github.com/WoltLab/WCF/pull/6115/))
- `wcf\data\smiley\category\SmileyCategoryAction::validateGetSmilies()` ([WoltLab/WCF#6115](https://github.com/WoltLab/WCF/pull/6115/))

#### Variables

- `wcf\form\AbstractFormBuilderForm::$objectEditLinkApplication` ([WoltLab/WCF#6110](https://github.com/WoltLab/WCF/pull/6110))

### JavaScript

- `WCF.ACP.Package.Server.Installation`
- `WCF.Action.SimpleProxy`
- `WCF.Browser`
- `WCF.Category.NestedList`
- `WCF.Collapsible.Simple`
- `WCF.Collapsible.Remote`
- `WCF.Collapsible.SimpleRemote`
- `WCF.Dictionary`
- `WCF.DOMNodeRemovedHandler`
- `WCF.EditableItemList`
- `WCF.Effect.Scroll`
- `WCF.Message.DefaultPreview` ([WoltLab/WCF#6114](https://github.com/WoltLab/WCF/pull/6114))
- `WCF.Message.EditHistory` ([WoltLab/WCF#6113](https://github.com/WoltLab/WCF/pull/6113))
- `WCF.Message.FormGuard`
- `WCF.Message.I18nPreview` ([WoltLab/WCF#6114](https://github.com/WoltLab/WCF/pull/6114))
- `WCF.Message.Multilingualism`
- `WCF.Message.Preview` ([WoltLab/WCF#6114](https://github.com/WoltLab/WCF/pull/6114))
- `WCF.Message.SmileyCategories` ([WoltLab/WCF#6115](https://github.com/WoltLab/WCF/pull/6115/))
- `WCF.Message.Submit`
- `WCF.Moderation.Management` ([WoltLab/WCF#6116](https://github.com/WoltLab/WCF/pull/6116/))
- `WCF.Moderation.Activation.Management` ([WoltLab/WCF#6116](https://github.com/WoltLab/WCF/pull/6116/))
- `WCF.Moderation.Report.Management` ([WoltLab/WCF#6116](https://github.com/WoltLab/WCF/pull/6116/))
- `WCF.Notification.List` ([WoltLab/WCF#6120](https://github.com/WoltLab/WCF/pull/6120/))
- `WCF.PageVisibilityHandler`
- `WCF.System.DisableScrolling`
- `WCF.System.DisableZoom`
- `WCF.System.ObjectStore`
- `WCF.System.PushNotification`
- `WCF.System.Worker`
- `WCF.TabMenu`
- `WCF.User.Profile.ActivityPointList` ([WoltLab/WCF#6119](https://github.com/WoltLab/WCF/pull/6119))
- `WCF.User.SignaturePreview` ([WoltLab/WCF#6114](https://github.com/WoltLab/WCF/pull/6114))
- `ui.wcfSlideshow` (jQuery Widget)
- `wcfTabs` (jQuery Widget)
- `datepicker` (jQuery Widget)
- `wcf.messageTabMenu` (jQuery Widget) ([WoltLab/WCF#6115](https://github.com/WoltLab/WCF/pull/6115/))

## Removals

### PHP

#### Methods

- `wcf\data\cronjob\log\CronjobLogAction::clearAll()` ([WoltLab/WCF#6077](https://github.com/WoltLab/WCF/pull/6077))
- `wcf\util\CLIUtil::formatTime()` ([WoltLab/WCF#6042](https://github.com/WoltLab/WCF/pull/6042/))
- `wcf\util\CLIUtil::formatDate()` ([WoltLab/WCF#6042](https://github.com/WoltLab/WCF/pull/6042/))

#### Properties

- `wcf\system\option\user\DateUserOptionOutput::$dateFormat` ([WoltLab/WCF#6042](https://github.com/WoltLab/WCF/pull/6042/))

### JavaScript

- `WCF.ACP.Cronjob.LogList` ([WoltLab/WCF#6077](https://github.com/WoltLab/WCF/pull/6077))
- `WCF.Moderation.Queue.MarkAsRead`
- `WCF.Moderation.Queue.MarkAllAsRead`
