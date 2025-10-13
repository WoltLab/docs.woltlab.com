# Migrating from WoltLab Suite 6.1 - Deprecations and Removals

With version 6.2, we have deprecated certain components and removed several other components that have been deprecated for many years.

## Deprecations

### PHP

#### Classes

- `wbb\system\board\command\CollapseBoard`
- `wbb\system\board\command\ExpandBoard`
- `wbb\system\board\command\UpdateLastPosts`
- `wbb\system\search\command\ThreadRebuildSearchIndex`
- `wcf\acp\form\AbstractAcpForm`
- `wcf\acp\form\AbstractOptionListForm`
- `wcf\acp\form\UserOptionListForm`
- `wcf\data\IImageViewerAction` ([WoltLab/WCF#6035](https://github.com/WoltLab/WCF/pull/6035/))
- `wcf\data\IMessageQuoteAction` ([WoltLab/WCF#6163](https://github.com/WoltLab/WCF/pull/6163/))
- `wcf\data\IPopoverAction` ([WoltLab/WCF#6154](https://github.com/WoltLab/WCF/pull/6154/))
- `wcf\data\article\TaggedArticleList` ([WoltLab/WCF#6291](https://github.com/WoltLab/WCF/pull/6291))
- `wcf\data\conversation\message\SimplifiedViewableConversationMessageList` ([WoltLab/com.woltlab.wcf.conversation#219](https://github.com/WoltLab/com.woltlab.wcf.conversation/pull/219))
- `wcf\data\conversation\message\ViewableConversationMessageList` ([WoltLab/com.woltlab.wcf.conversation#219](https://github.com/WoltLab/com.woltlab.wcf.conversation/pull/219))
- `wcf\data\conversation\message\ViewableConversationMessage` ([WoltLab/com.woltlab.wcf.conversation#219](https://github.com/WoltLab/com.woltlab.wcf.conversation/pull/219))
- `wcf\data\user\avatar\UserAvatarAction` ([WoltLab/WCF#6051](https://github.com/WoltLab/WCF/pull/6051))
- `wcf\data\user\avatar\UserAvatarEditor` ([WoltLab/WCF#6051](https://github.com/WoltLab/WCF/pull/6051))
- `wcf\data\user\avatar\UserAvatarList` ([WoltLab/WCF#6051](https://github.com/WoltLab/WCF/pull/6051))
- `wcf\data\user\avatar\UserAvatar` ([WoltLab/WCF#6051](https://github.com/WoltLab/WCF/pull/6051))
- `wcf\data\user\cover\photo\IWebpUserCoverPhoto` ([WoltLab/WCF#6127](https://github.com/WoltLab/WCF/pull/6127/))
- `wcf\form\AbstractCaptchaForm`
- `wcf\form\MessageForm`
- `wcf\page\CombinedTaggedPage` ([WoltLab/WCF#6291](https://github.com/WoltLab/WCF/pull/6291))
- `wcf\page\TaggedPage` ([WoltLab/WCF#6291](https://github.com/WoltLab/WCF/pull/6291))
- `wcf\system\acp\dashboard\command\ConfigureBoxes`
- `wcf\system\box\command\CreateBoxCondition`
- `wcf\system\box\command\CreateBoxToPageAssignments`
- `wcf\system\cache\command\ClearCache`
- `wcf\system\comment\command\CreateComment`
- `wcf\system\comment\command\DeleteComments`
- `wcf\system\comment\command\PublishComment`
- `wcf\system\comment\command\UpdateComment`
- `wcf\system\comment\response\command\CreateResponse`
- `wcf\system\comment\response\command\DeleteResponses`
- `wcf\system\comment\response\command\PublishResponse`
- `wcf\system\comment\response\command\UpdateResponse`
- `wcf\system\file\command\ReplaceFileSource`
- `wcf\system\form\builder\field\MultipleSelectFormField`
- `wcf\system\language\preload\command\CachePreloadPhrases`
- `wcf\system\language\preload\command\ResetPreloadCache`
- `wcf\system\message\quote\AbstractMessageQuoteHandler` ([WoltLab/WCF#6163](https://github.com/WoltLab/WCF/pull/6163/))
- `wcf\system\message\quote\IMessageQuoteHandler` ([WoltLab/WCF#6163](https://github.com/WoltLab/WCF/pull/6163/))
- `wcf\system\message\quote\QuotedMessage` ([WoltLab/WCF#6163](https://github.com/WoltLab/WCF/pull/6163/))
- `wcf\system\moderation\AbstractDeletedContentProvider` ([WoltLab/WCF#6285](https://github.com/WoltLab/WCF/pull/6285))
- `wcf\system\moderation\IDeletedContentProvider` ([WoltLab/WCF#6285](https://github.com/WoltLab/WCF/pull/6285))
- `wcf\system\package\command\RebuildBootstrapper`
- `wcf\system\style\command\AddDarkMode`
- `wcf\system\style\command\CreateManifest`
- `wcf\system\tagging\AbstractCombinedTaggable` ([WoltLab/WCF#6291](https://github.com/WoltLab/WCF/pull/6291))
- `wcf\system\tagging\AbstractTaggable` ([WoltLab/WCF#6291](https://github.com/WoltLab/WCF/pull/6291))
- `wcf\system\tagging\ICombinedTaggable` ([WoltLab/WCF#6291](https://github.com/WoltLab/WCF/pull/6291))
- `wcf\system\tagging\ITaggable` ([WoltLab/WCF#6291](https://github.com/WoltLab/WCF/pull/6291))
- `wcf\system\tagging\ITagged` ([WoltLab/WCF#6291](https://github.com/WoltLab/WCF/pull/6291))
- `wcf\system\user\command\CreateRegistrationNotification`
- `wcf\system\user\command\Follow`
- `wcf\system\user\command\SetColorScheme`
- `wcf\system\user\command\Unfollow`
- `wcf\data\custom\option\CustomOption` ([WoltLab/WCF#6277](https://github.com/WoltLab/WCF/pull/6277))
- `wcf\data\custom\option\CustomOptionAction` ([WoltLab/WCF#6277](https://github.com/WoltLab/WCF/pull/6277))
- `wcf\data\custom\option\CustomOptionEditor` ([WoltLab/WCF#6277](https://github.com/WoltLab/WCF/pull/6277))
- `wcf\data\custom\option\CustomOptionList` ([WoltLab/WCF#6277](https://github.com/WoltLab/WCF/pull/6277))
- `wcf\acp\form\AbstractCustomOptionForm` ([WoltLab/WCF#6277](https://github.com/WoltLab/WCF/pull/6277))
- `wcf\system\option\CustomOptionHandler` ([WoltLab/WCF#6277](https://github.com/WoltLab/WCF/pull/6277))

#### Methods

- `wcf\data\ad\AdAction::toggle()` ([WoltLab/WCF#6424](https://github.com/WoltLab/WCF/pull/6424/))
- `wcf\data\bbcode\media\provider\BBCodeMediaProviderAction::toggle()` ([WoltLab/WCF#6424](https://github.com/WoltLab/WCF/pull/6424/))
- `wcf\data\box\BoxAction::toggle()` ([WoltLab/WCF#6424](https://github.com/WoltLab/WCF/pull/6424/))
- `wcf\data\captcha\question\CaptchaQuestionAction::toggle()` ([WoltLab/WCF#6424](https://github.com/WoltLab/WCF/pull/6424/))
- `wcf\data\contact\option\ContactOptionAction::toggle()` ([WoltLab/WCF#6424](https://github.com/WoltLab/WCF/pull/6424/))
- `wcf\data\contact\recipient\ContactRecipientAction::toggle()` ([WoltLab/WCF#6424](https://github.com/WoltLab/WCF/pull/6424/))
- `wcf\data\conversation\Conversation::getUserConversation()` ([WoltLab/com.woltlab.wcf.conversation#219](https://github.com/WoltLab/com.woltlab.wcf.conversation/pull/219))
- `wcf\data\conversation\Conversation::getUserConversations()` ([WoltLab/com.woltlab.wcf.conversation#219](https://github.com/WoltLab/com.woltlab.wcf.conversation/pull/219))
- `wcf\data\conversation\Conversation::hasOtherParticipants()` ([WoltLab/com.woltlab.wcf.conversation#219](https://github.com/WoltLab/com.woltlab.wcf.conversation/pull/219))
- `wcf\data\conversation\Conversation::validateGroupParticipants()` ([WoltLab/com.woltlab.wcf.conversation#219](https://github.com/WoltLab/com.woltlab.wcf.conversation/pull/219))
- `wcf\data\conversation\Conversation::validateParticipant()` ([WoltLab/com.woltlab.wcf.conversation#219](https://github.com/WoltLab/com.woltlab.wcf.conversation/pull/219))
- `wcf\data\conversation\Conversation::validateParticipants()` ([WoltLab/com.woltlab.wcf.conversation#219](https://github.com/WoltLab/com.woltlab.wcf.conversation/pull/219))
- `wcf\data\conversation\ConversationAction::markAllAsRead()`
- `wcf\data\conversation\ConversationAction::markAsRead()`
- `wcf\data\conversation\ConversationAction::validateMarkAllAsRead()`
- `wcf\data\conversation\ConversationAction::validateMarkAsRead()`
- `wcf\data\cronjob\CronjobAction::toggle()` ([WoltLab/WCF#6424](https://github.com/WoltLab/WCF/pull/6424/))
- `wcf\data\language\LanguageAction::toggle()` ([WoltLab/WCF#6424](https://github.com/WoltLab/WCF/pull/6424/))
- `wcf\data\notice\NoticeAction::toggle()` ([WoltLab/WCF#6424](https://github.com/WoltLab/WCF/pull/6424/))
- `wcf\data\package\update\server\PackageUpdateServerAction::toggle()` ([WoltLab/WCF#6424](https://github.com/WoltLab/WCF/pull/6424/))
- `wcf\data\page\PageAction::toggle()` ([WoltLab/WCF#6424](https://github.com/WoltLab/WCF/pull/6424/))
- `wcf\data\paid\subscription\PaidSubscriptionAction::toggle()` ([WoltLab/WCF#6424](https://github.com/WoltLab/WCF/pull/6424/))
- `wcf\data\reaction\type\ReactionTypeAction::toggle()` ([WoltLab/WCF#6424](https://github.com/WoltLab/WCF/pull/6424/))
- `wcf\data\smiley\category\SmileyCategoryAction::getSmilies()` ([WoltLab/WCF#6115](https://github.com/WoltLab/WCF/pull/6115/))
- `wcf\data\smiley\category\SmileyCategoryAction::validateGetSmilies()` ([WoltLab/WCF#6115](https://github.com/WoltLab/WCF/pull/6115/))
- `wcf\data\style\StyleAction::toggle()` ([WoltLab/WCF#6424](https://github.com/WoltLab/WCF/pull/6424/))
- `wcf\data\trophy\TrophyAction::toggle()` ([WoltLab/WCF#6424](https://github.com/WoltLab/WCF/pull/6424/))
- `wcf\data\unfurl\url\UnfurlUrlAction::findOrCreate()` ([WoltLab/WCF#6408](https://github.com/WoltLab/WCF/pull/6408))
- `wcf\data\user\group\UserGroupAction::copy()`
- `wcf\data\user\group\UserGroupAction::validateCopy()`
- `wcf\data\user\group\assignment\UserGroupAssignmentAction::toggle()` ([WoltLab/WCF#6424](https://github.com/WoltLab/WCF/pull/6424/))
- `wcf\system\form\builder\container\wysiwyg\WysiwygFormContainer::quoteData()` ([WoltLab/WCF#6163](https://github.com/WoltLab/WCF/pull/6163/))
- `wcf\system\form\builder\field\wysiwyg\WysiwygFormField::getQuoteData()` ([WoltLab/WCF#6163](https://github.com/WoltLab/WCF/pull/6163/))
- `wcf\system\form\builder\field\wysiwyg\WysiwygFormField::quoteData()` ([WoltLab/WCF#6163](https://github.com/WoltLab/WCF/pull/6163/))
- `wcf\system\message\quote\MessageQuoteManager::addQuote()` ([WoltLab/WCF#6163](https://github.com/WoltLab/WCF/pull/6163/))
- `wcf\system\message\quote\MessageQuoteManager::assignVariables()` ([WoltLab/WCF#6163](https://github.com/WoltLab/WCF/pull/6163/))
- `wcf\system\message\quote\MessageQuoteManager::countQuotes()` ([WoltLab/WCF#6163](https://github.com/WoltLab/WCF/pull/6163/))
- `wcf\system\message\quote\MessageQuoteManager::getObjectID()` ([WoltLab/WCF#6163](https://github.com/WoltLab/WCF/pull/6163/))
- `wcf\system\message\quote\MessageQuoteManager::getQuote()` ([WoltLab/WCF#6163](https://github.com/WoltLab/WCF/pull/6163/))
- `wcf\system\message\quote\MessageQuoteManager::getQuoteID()` ([WoltLab/WCF#6163](https://github.com/WoltLab/WCF/pull/6163/))
- `wcf\system\message\quote\MessageQuoteManager::getQuoteMessageID()` ([WoltLab/WCF#6163](https://github.com/WoltLab/WCF/pull/6163/))
- `wcf\system\message\quote\MessageQuoteManager::getQuotes()` ([WoltLab/WCF#6163](https://github.com/WoltLab/WCF/pull/6163/))
- `wcf\system\message\quote\MessageQuoteManager::getQuotesByObjectIDs()` ([WoltLab/WCF#6163](https://github.com/WoltLab/WCF/pull/6163/))
- `wcf\system\message\quote\MessageQuoteManager::getQuotesByParentObjectID()` ([WoltLab/WCF#6163](https://github.com/WoltLab/WCF/pull/6163/))
- `wcf\system\message\quote\MessageQuoteManager::initObjects()` ([WoltLab/WCF#6163](https://github.com/WoltLab/WCF/pull/6163/))
- `wcf\system\message\quote\MessageQuoteManager::isFullQuote()` ([WoltLab/WCF#6163](https://github.com/WoltLab/WCF/pull/6163/))
- `wcf\system\message\quote\MessageQuoteManager::readParameters()` ([WoltLab/WCF#6163](https://github.com/WoltLab/WCF/pull/6163/))
- `wcf\system\message\quote\MessageQuoteManager::removeMarkedQuotes()` ([WoltLab/WCF#6163](https://github.com/WoltLab/WCF/pull/6163/))
- `wcf\system\message\quote\MessageQuoteManager::removeQuote()` ([WoltLab/WCF#6163](https://github.com/WoltLab/WCF/pull/6163/))
- `wcf\system\message\quote\MessageQuoteManager::removeQuote()` ([WoltLab/WCF#6163](https://github.com/WoltLab/WCF/pull/6163/))
- `wcf\system\message\quote\MessageQuoteManager::renderQuote()` ([WoltLab/WCF#6163](https://github.com/WoltLab/WCF/pull/6163/))
- `wcf\util\DateUtil::format()` ([WoltLab/WCF#6042](https://github.com/WoltLab/WCF/pull/6042/))

#### Variables

- `wcf\form\AbstractFormBuilderForm::$objectEditLinkApplication` ([WoltLab/WCF#6110](https://github.com/WoltLab/WCF/pull/6110))

### JavaScript

- `WCF.ACP.Package.Server.Installation`
- `WCF.Action.SimpleProxy`
- `WCF.Browser`
- `WCF.Category.FlexibleCategoryList` ([WoltLab/WCF#6128](https://github.com/WoltLab/WCF/pull/6128))
- `WCF.Category.NestedList`
- `WCF.Collapsible.Remote`
- `WCF.Collapsible.SimpleRemote`
- `WCF.Collapsible.Simple`
- `WCF.DOMNodeRemovedHandler`
- `WCF.Dictionary`
- `WCF.EditableItemList`
- `WCF.Effect.Scroll`
- `WCF.Message.DefaultPreview` ([WoltLab/WCF#6114](https://github.com/WoltLab/WCF/pull/6114))
- `WCF.Message.EditHistory` ([WoltLab/WCF#6113](https://github.com/WoltLab/WCF/pull/6113))
- `WCF.Message.FormGuard`
- `WCF.Message.I18nPreview` ([WoltLab/WCF#6114](https://github.com/WoltLab/WCF/pull/6114))
- `WCF.Message.Multilingualism`
- `WCF.Message.Preview` ([WoltLab/WCF#6114](https://github.com/WoltLab/WCF/pull/6114))
- `WCF.Message.Quote.Manager` ([WoltLab/WCF#6163](https://github.com/WoltLab/WCF/pull/6163/))
- `WCF.Message.SmileyCategories` ([WoltLab/WCF#6115](https://github.com/WoltLab/WCF/pull/6115/))
- `WCF.Message.Submit`
- `WCF.Moderation.Activation.Management` ([WoltLab/WCF#6116](https://github.com/WoltLab/WCF/pull/6116/))
- `WCF.Moderation.Management` ([WoltLab/WCF#6116](https://github.com/WoltLab/WCF/pull/6116/))
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
- `WoltLabSuite/Core/Acp/Ui/Article/InlineEditor` ([WoltLab/WCF#6373](https://github.com/WoltLab/WCF/pull/6373))
- `WoltLabSuite/Core/Ui/Message/Quote` ([WoltLab/WCF#6163](https://github.com/WoltLab/WCF/pull/6163/))
- `WoltLabSuite/Core/Ui/Notification` ([WoltLab/WCF/commit/bc4ed044790cea4c73f9691866a9b0f031b87440](https://github.com/WoltLab/WCF/commit/bc4ed044790cea4c73f9691866a9b0f031b87440))
- `WoltLabSuite/Core/Ui/User/Profile/Menu/Item/Abstract` ([WoltLab/WCF#6126](https://github.com/WoltLab/WCF/pull/6126/))
- `WoltLabSuite/Core/Ui/User/Profile/Menu/Item/Follow` ([WoltLab/WCF#6126](https://github.com/WoltLab/WCF/pull/6126/))
- `WoltLabSuite/Core/Ui/User/Profile/Menu/Item/Ignore` ([WoltLab/WCF#6126](https://github.com/WoltLab/WCF/pull/6126/))
- `datepicker` (jQuery Widget)
- `ui.wcfSlideshow` (jQuery Widget)
- `wcf.messageTabMenu` (jQuery Widget) ([WoltLab/WCF#6115](https://github.com/WoltLab/WCF/pull/6115/))
- `wcfTabs` (jQuery Widget)

## Removals

### PHP

#### Classes

- `blog\system\message\quote\EntryMessageQuoteHandler`
- `calendar\system\message\quote\EventMessageQuoteHandler`
- `wbb\system\message\quote\PostMessageQuoteHandler`
- `wcf\action\MessageQuoteAction` ([WoltLab/WCF#6163](https://github.com/WoltLab/WCF/pull/6163/))
- `wcf\data\conversation\ViewableConversation` ([WoltLab/com.woltlab.wcf.conversation#219](https://github.com/WoltLab/com.woltlab.wcf.conversation/pull/219))
- `wcf\form\AvatarEditForm` ([WoltLab/WCF#6051](https://github.com/WoltLab/WCF/pull/6051))
- `wcf\system\clipboard\action\ArticleClipboardAction`
- `wcf\system\clipboard\action\TagClipboardAction`
- `wcf\system\upload\AvatarUploadFileSaveStrategy` ([WoltLab/WCF#6051](https://github.com/WoltLab/WCF/pull/6051))
- `wcf\system\upload\AvatarUploadFileValidationStrategy` ([WoltLab/WCF#6051](https://github.com/WoltLab/WCF/pull/6051))
- `wcf\system\upload\UserCoverPhotoUploadFileSaveStrategy` ([WoltLab/WCF#6127](https://github.com/WoltLab/WCF/pull/6127/))
- `wcf\system\upload\UserCoverPhotoUploadFileValidationStrategy` ([WoltLab/WCF#6127](https://github.com/WoltLab/WCF/pull/6127/))

#### Methods

- `blog\data\entry\EntryAction::getEntryPreview()`
- `blog\data\entry\EntryAction::getPopover()`
- `blog\data\entry\EntryAction::getRenderedQuotes()`
- `blog\data\entry\EntryAction::saveFullQuote()`
- `blog\data\entry\EntryAction::saveQuote()`
- `blog\data\entry\EntryAction::validateGetEntryPreview()`
- `blog\data\entry\EntryAction::validateGetPopover()`
- `blog\data\entry\EntryAction::validateGetRenderedQuotes()`
- `blog\data\entry\EntryAction::validateSaveFullQuote()`
- `blog\data\entry\EntryAction::validateSaveQuote()`
- `calendar\data\event\EventAction::getRenderedQuotes()`
- `calendar\data\event\EventAction::saveFullQuote()`
- `calendar\data\event\EventAction::saveQuote()`
- `calendar\data\event\EventAction::validateGetRenderedQuotes()`
- `calendar\data\event\EventAction::validateSaveFullQuote()`
- `calendar\data\event\EventAction::validateSaveQuote()`
- `calendar\data\event\date\EventDateAction::getEventPreview()`
- `calendar\data\event\date\EventDateAction::getPopover()`
- `calendar\data\event\date\EventDateAction::validateGetEventPreview()`
- `calendar\data\event\date\EventDateAction::validateGetPopover()`
- `filebase\data\file\FileAction::getFilePreview()`
- `filebase\data\file\FileAction::getPopover()`
- `filebase\data\file\FileAction::validateGetFilePreview()`
- `filebase\data\file\FileAction::validateGetPopover()`
- `wbb\data\post\PostAction::getPopover()`
- `wbb\data\post\PostAction::getPostPreview()`
- `wbb\data\post\PostAction::getRenderedQuotes()`
- `wbb\data\post\PostAction::saveFullQuote()`
- `wbb\data\post\PostAction::saveQuote()`
- `wbb\data\post\PostAction::validateGetPopover()`
- `wbb\data\post\PostAction::validateGetPostPreview()`
- `wbb\data\post\PostAction::validateGetRenderedQuotes()`
- `wbb\data\post\PostAction::validateSaveFullQuote()`
- `wbb\data\post\PostAction::validateSaveQuote()`
- `wbb\data\thread\ThreadAction::getPopover()`
- `wbb\data\thread\ThreadAction::getPostPreview()`
- `wbb\data\thread\ThreadAction::validateGetPopover()`
- `wbb\data\thread\ThreadAction::validateGetPostPreview()`
- `wcf\data\category\CategoryAction::toggleContainer()` ([WoltLab/WCF#6298](https://github.com/WoltLab/WCF/pull/6298))
- `wcf\data\conversation\Conversation::loadUserParticipation()` ([WoltLab/com.woltlab.wcf.conversation#219](https://github.com/WoltLab/com.woltlab.wcf.conversation/pull/219))
- `wcf\data\conversation\ConversationAction::getMessagePreview()`
- `wcf\data\conversation\ConversationAction::getPopover()`
- `wcf\data\conversation\ConversationAction::validateGetMessagePreview()`
- `wcf\data\conversation\ConversationAction::validateGetPopover()`
- `wcf\data\conversation\ConversationEditor::updateParticipantSummaries()` ([WoltLab/com.woltlab.wcf.conversation#219](https://github.com/WoltLab/com.woltlab.wcf.conversation/pull/219))
- `wcf\data\conversation\ConversationEditor::updateParticipantSummary()` ([WoltLab/com.woltlab.wcf.conversation#219](https://github.com/WoltLab/com.woltlab.wcf.conversation/pull/219))
- `wcf\data\cronjob\log\CronjobLogAction::clearAll()` ([WoltLab/WCF#6077](https://github.com/WoltLab/WCF/pull/6077))
- `wcf\data\user\UserProfileAction::deleteCoverPhoto()` ([WoltLab/WCF#6127](https://github.com/WoltLab/WCF/pull/6127/))
- `wcf\data\user\UserProfileAction::setAvatar()` ([WoltLab/WCF#6051](https://github.com/WoltLab/WCF/pull/6051))`
- `wcf\data\user\UserProfileAction::uploadCoverPhoto()` ([WoltLab/WCF#6127](https://github.com/WoltLab/WCF/pull/6127/))
- `wcf\data\user\UserProfileAction::validateDeleteCoverPhoto()` ([WoltLab/WCF#6127](https://github.com/WoltLab/WCF/pull/6127/))
- `wcf\data\user\UserProfileAction::validateUploadCoverPhoto()` ([WoltLab/WCF#6127](https://github.com/WoltLab/WCF/pull/6127/))
- `wcf\util\CLIUtil::formatDate()` ([WoltLab/WCF#6042](https://github.com/WoltLab/WCF/pull/6042/))
- `wcf\util\CLIUtil::formatTime()` ([WoltLab/WCF#6042](https://github.com/WoltLab/WCF/pull/6042/))

#### Properties

- `wcf\data\user\User::$coverPhotoExtension` ([WoltLab/WCF#6127](https://github.com/WoltLab/WCF/pull/6127/))
- `wcf\data\user\User::$coverPhotoHasWebP` ([WoltLab/WCF#6127](https://github.com/WoltLab/WCF/pull/6127/))
- `wcf\data\user\User::$coverPhotoHash` ([WoltLab/WCF#6127](https://github.com/WoltLab/WCF/pull/6127/))
- `wcf\system\form\builder\container\wysiwyg\WysiwygFormContainer::$quoteData` ([WoltLab/WCF#6163](https://github.com/WoltLab/WCF/pull/6163/))
- `wcf\system\form\builder\field\wysiwyg\WysiwygFormField::$quoteData` ([WoltLab/WCF#6163](https://github.com/WoltLab/WCF/pull/6163/))
- `wcf\system\message\quote\MessageQuoteManager::$objectIDs` ([WoltLab/WCF#6163](https://github.com/WoltLab/WCF/pull/6163/))
- `wcf\system\message\quote\MessageQuoteManager::$objectType` ([WoltLab/WCF#6163](https://github.com/WoltLab/WCF/pull/6163/))
- `wcf\system\message\quote\MessageQuoteManager::$objectTypes` ([WoltLab/WCF#6163](https://github.com/WoltLab/WCF/pull/6163/))
- `wcf\system\message\quote\MessageQuoteManager::$quoteData` ([WoltLab/WCF#6163](https://github.com/WoltLab/WCF/pull/6163/))
- `wcf\system\message\quote\MessageQuoteManager::$quoteMessageID` ([WoltLab/WCF#6163](https://github.com/WoltLab/WCF/pull/6163/))
- `wcf\system\message\quote\MessageQuoteManager::$quotes` ([WoltLab/WCF#6163](https://github.com/WoltLab/WCF/pull/6163/))
- `wcf\system\option\user\DateUserOptionOutput::$dateFormat` ([WoltLab/WCF#6042](https://github.com/WoltLab/WCF/pull/6042/))

### JavaScript

- `WCF.ACP.Category.Collapsible` ([WoltLab/WCF#6298](https://github.com/WoltLab/WCF/pull/6298))
- `WCF.ACP.Cronjob.LogList` ([WoltLab/WCF#6077](https://github.com/WoltLab/WCF/pull/6077))
- `WCF.ACP.Language.ItemList` ([WoltLab/WCF#6129](https://github.com/WoltLab/WCF/pull/6129))
- `WCF.ACP.Language` ([WoltLab/WCF#6129](https://github.com/WoltLab/WCF/pull/6129))
- `WCF.ACP.Style.CopyStyle` ([WoltLab/WCF#6231](https://github.com/WoltLab/WCF/pull/6231))
- `WCF.ACP.Style.List` ([WoltLab/WCF#6187](https://github.com/WoltLab/WCF/pull/6187))
- `WCF.ACP.Tag.SetAsSynonymsHandler` ([WoltLab/WCF#6130](https://github.com/WoltLab/WCF/pull/6130))
- `WCF.ACP.Tag` ([WoltLab/WCF#6130](https://github.com/WoltLab/WCF/pull/6130))
- `WCF.ACP.User.BanHandler` ([WoltLab/WCF#6136](https://github.com/WoltLab/WCF/pull/6136))
- `WCF.ACP.User.EnableHandler` ([WoltLab/WCF#6136](https://github.com/WoltLab/WCF/pull/6136))
- `WCF.ACP.User.Group.Copy` ([WoltLab/WCF#6131](https://github.com/WoltLab/WCF/pull/6131))
- `WCF.ACP.User.Group` ([WoltLab/WCF#6131](https://github.com/WoltLab/WCF/pull/6131))
- `WCF.ACP.User.SendNewPasswordHandler` ([WoltLab/WCF#6136](https://github.com/WoltLab/WCF/pull/6136))
- `WCF.ACP.User` ([WoltLab/WCF#6136](https://github.com/WoltLab/WCF/pull/6136))
- `WCF.ImageViewer` ([WoltLab/WCF#6035](https://github.com/WoltLab/WCF/pull/6035/))
- `WCF.Moderation.Queue.MarkAllAsRead`
- `WCF.Moderation.Queue.MarkAsRead`
- `WCF.User.Avatar.Upload` ([WoltLab/WCF#6051](https://github.com/WoltLab/WCF/pull/6051))
- `WCF.User.Avatar` ([WoltLab/WCF#6051](https://github.com/WoltLab/WCF/pull/6051))
- `WoltLabSuite/Blog/Ui/Entry/Quote`
- `WoltLabSuite/Calendar/Ui/Event/Quote`
- `WoltLabSuite/Core/Conversation/MarkAsRead` ([WoltLab/com.woltlab.wcf.conversation#225](https://github.com/WoltLab/com.woltlab.wcf.conversation/pull/225))
- `WoltLabSuite/Core/Ui/Style/DarkMode` ([WoltLab/WCF#6231](https://github.com/WoltLab/WCF/pull/6231))
- `WoltLabSuite/Core/Ui/User/CoverPhoto/Delete` ([WoltLab/WCF#6127](https://github.com/WoltLab/WCF/pull/6127/))
- `WoltLabSuite/Core/Ui/User/CoverPhoto/Upload` ([WoltLab/WCF#6127](https://github.com/WoltLab/WCF/pull/6127/))
- `WoltLabSuite/Forum/Ui/Post/Quote`
