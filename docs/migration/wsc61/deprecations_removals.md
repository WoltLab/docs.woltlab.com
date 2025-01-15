# Migrating from WoltLab Suite 6.1 - Deprecations and Removals

With version 6.2, we have deprecated certain components and removed several other components that have been deprecated for many years.

## Deprecations

### PHP

#### Classes

- `wcf\data\IImageViewerAction` ([WoltLab/WCF#6035](https://github.com/WoltLab/WCF/pull/6035/))
- `wcf\data\IPopoverAction` ([WoltLab/WCF#6154](https://github.com/WoltLab/WCF/pull/6154/))
- `wcf\data\user\cover\photo\IWebpUserCoverPhoto` ([WoltLab/WCF#6127](https://github.com/WoltLab/WCF/pull/6127/))

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
- `WCF.Category.FlexibleCategoryList` ([WoltLab/WCF#6128](https://github.com/WoltLab/WCF/pull/6128))
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
- `WCF.Option.Handler` ([WoltLab/WCF#6125](https://github.com/WoltLab/WCF/pull/6125/))
- `WCF.PageVisibilityHandler`
- `WCF.Sortable.List` ([WoltLab/WCF#6124](https://github.com/WoltLab/WCF/pull/6124))
- `WCF.System.DisableScrolling`
- `WCF.System.DisableZoom`
- `WCF.System.ObjectStore`
- `WCF.System.PushNotification`
- `WCF.System.Worker`
- `WCF.TabMenu`
- `WCF.User.Profile.ActivityPointList` ([WoltLab/WCF#6119](https://github.com/WoltLab/WCF/pull/6119))
- `WCF.User.Profile.TabMenu` ([WoltLab/WCF#6123](https://github.com/WoltLab/WCF/pull/6123))
- `WCF.User.SignaturePreview` ([WoltLab/WCF#6114](https://github.com/WoltLab/WCF/pull/6114))
- `ui.wcfSlideshow` (jQuery Widget)
- `wcfTabs` (jQuery Widget)
- `datepicker` (jQuery Widget)
- `wcf.messageTabMenu` (jQuery Widget) ([WoltLab/WCF#6115](https://github.com/WoltLab/WCF/pull/6115/))
- `WoltLabSuite/Core/Ui/User/Profile/Menu/Item/Abstract` ([WoltLab/WCF#6126](https://github.com/WoltLab/WCF/pull/6126/))
- `WoltLabSuite/Core/Ui/User/Profile/Menu/Item/Follow` ([WoltLab/WCF#6126](https://github.com/WoltLab/WCF/pull/6126/))
- `WoltLabSuite/Core/Ui/User/Profile/Menu/Item/Ignore` ([WoltLab/WCF#6126](https://github.com/WoltLab/WCF/pull/6126/))

## Removals

### PHP

#### Classes

- `wcf\system\upload\UserCoverPhotoUploadFileSaveStrategy` ([WoltLab/WCF#6127](https://github.com/WoltLab/WCF/pull/6127/))
- `wcf\system\upload\UserCoverPhotoUploadFileValidationStrategy` ([WoltLab/WCF#6127](https://github.com/WoltLab/WCF/pull/6127/))

#### Methods

- `wcf\data\cronjob\log\CronjobLogAction::clearAll()` ([WoltLab/WCF#6077](https://github.com/WoltLab/WCF/pull/6077))
- `wcf\util\CLIUtil::formatTime()` ([WoltLab/WCF#6042](https://github.com/WoltLab/WCF/pull/6042/))
- `wcf\util\CLIUtil::formatDate()` ([WoltLab/WCF#6042](https://github.com/WoltLab/WCF/pull/6042/))
- `wbb\data\post\PostAction::validateGetPopover()`
- `wbb\data\post\PostAction::getPopover()`
- `wbb\data\post\PostAction::validateGetPostPreview()`
- `wbb\data\post\PostAction::getPostPreview()`
- `wbb\data\thread\ThreadAction::validateGetPopover()`
- `wbb\data\thread\ThreadAction::getPopover()`
- `wbb\data\thread\ThreadAction::validateGetPostPreview()`
- `wbb\data\thread\ThreadAction::getPostPreview()`
- `blog\data\entry\EntryAction::validateGetPopover()`
- `blog\data\entry\EntryAction::getPopover()`
- `blog\data\entry\EntryAction::validateGetEntryPreview()`
- `blog\data\entry\EntryAction::getEntryPreview()`
- `filebase\data\file\FileAction::validateGetPopover()`
- `filebase\data\file\FileAction::getPopover()`
- `filebase\data\file\FileAction::validateGetFilePreview()`
- `filebase\data\file\FileAction::getFilePreview()`
- `calendar\data\event\date\EventDateAction::validateGetPopover()`
- `calendar\data\event\date\EventDateAction::getPopover()`
- `calendar\data\event\date\EventDateAction::validateGetEventPreview()`
- `calendar\data\event\date\EventDateAction::getEventPreview()`
- `wcf\data\conversation\ConversationAction::validateGetPopover()`
- `wcf\data\conversation\ConversationAction::getPopover()`
- `wcf\data\conversation\ConversationAction::validateGetMessagePreview()`
- `wcf\data\conversation\ConversationAction::getMessagePreview()`
- `wcf\data\user\UserProfileAction::validateUploadCoverPhoto()` ([WoltLab/WCF#6127](https://github.com/WoltLab/WCF/pull/6127/))
- `wcf\data\user\UserProfileAction::uploadCoverPhoto()` ([WoltLab/WCF#6127](https://github.com/WoltLab/WCF/pull/6127/))
- `wcf\data\user\UserProfileAction::validateDeleteCoverPhoto()` ([WoltLab/WCF#6127](https://github.com/WoltLab/WCF/pull/6127/))
- `wcf\data\user\UserProfileAction::deleteCoverPhoto()` ([WoltLab/WCF#6127](https://github.com/WoltLab/WCF/pull/6127/))

#### Properties

- `wcf\system\option\user\DateUserOptionOutput::$dateFormat` ([WoltLab/WCF#6042](https://github.com/WoltLab/WCF/pull/6042/))
- `wcf\data\user\User::$coverPhotoHash` ([WoltLab/WCF#6127](https://github.com/WoltLab/WCF/pull/6127/))
- `wcf\data\user\User::$coverPhotoExtension` ([WoltLab/WCF#6127](https://github.com/WoltLab/WCF/pull/6127/))
- `wcf\data\user\User::$coverPhotoHasWebP` ([WoltLab/WCF#6127](https://github.com/WoltLab/WCF/pull/6127/))

### JavaScript

- `WCF.ImageViewer` ([WoltLab/WCF#6035](https://github.com/WoltLab/WCF/pull/6035/))
- `WCF.ACP.Cronjob.LogList` ([WoltLab/WCF#6077](https://github.com/WoltLab/WCF/pull/6077))
- `WCF.Moderation.Queue.MarkAsRead`
- `WCF.Moderation.Queue.MarkAllAsRead`
- `WCF.ACP.Language` ([WoltLab/WCF#6129](https://github.com/WoltLab/WCF/pull/6129))
- `WCF.ACP.Language.ItemList` ([WoltLab/WCF#6129](https://github.com/WoltLab/WCF/pull/6129))
- `WCF.ACP.Tag` ([WoltLab/WCF#6130](https://github.com/WoltLab/WCF/pull/6130))
- `WCF.ACP.Tag.SetAsSynonymsHandler` ([WoltLab/WCF#6130](https://github.com/WoltLab/WCF/pull/6130))
- `WCF.ACP.User.Group` ([WoltLab/WCF#6131](https://github.com/WoltLab/WCF/pull/6131))
- `WCF.ACP.User.Group.Copy` ([WoltLab/WCF#6131](https://github.com/WoltLab/WCF/pull/6131))
- `WCF.ACP.User` ([WoltLab/WCF#6136](https://github.com/WoltLab/WCF/pull/6136))
- `WCF.ACP.User.BanHandler` ([WoltLab/WCF#6136](https://github.com/WoltLab/WCF/pull/6136))
- `WCF.ACP.User.EnableHandler` ([WoltLab/WCF#6136](https://github.com/WoltLab/WCF/pull/6136))
- `WCF.ACP.User.SendNewPasswordHandler` ([WoltLab/WCF#6136](https://github.com/WoltLab/WCF/pull/6136))
- `WoltLabSuite/Core/Ui/User/CoverPhoto/Delete` ([WoltLab/WCF#6127](https://github.com/WoltLab/WCF/pull/6127/))
- `WoltLabSuite/Core/Ui/User/CoverPhoto/Upload` ([WoltLab/WCF#6127](https://github.com/WoltLab/WCF/pull/6127/))
