# Migrating from WoltLab Suite 6.1 - Deprecations and Removals

With version 6.2, we have deprecated certain components and removed several other components that have been deprecated for many years.

## Deprecations

### PHP

#### Methods

- `wcf\util\DateUtil::format()` ([WoltLab/WCF#6042](https://github.com/WoltLab/WCF/pull/6042/))

### JavaScript

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
- `WCF.Message.FormGuard`
- `WCF.Message.Multilingualism`
- `WCF.Message.Submit`
- `WCF.PageVisibilityHandler`
- `WCF.System.DisableScrolling`
- `WCF.System.DisableZoom`
- `WCF.System.ObjectStore`
- `WCF.System.PushNotification`
- `WCF.System.Worker`
- `WCF.TabMenu`
- `ui.wcfSlideshow` (jQuery Widget)
- `wcfTabs` (jQuery Widget)
- `datepicker` (jQuery Widget)

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
