# Migrating from WSC 5.4 - Deprecations and Removals

With version 5.5, we have deprecated certain components and removed several other components that have been deprecated for many years.



## Deprecations

### PHP

#### Classes

- `filebase\system\file\FileDataHandler` (use `filebase\system\cache\runtime\FileRuntimeCache`)
- `wcf\action\AbstractAjaxAction` (use PSR-7 responses, [WoltLab/WCF#4437](https://github.com/WoltLab/WCF/pull/4437))
- `wcf\data\IExtendedMessageQuickReplyAction` ([WoltLab/WCF#4575](https://github.com/WoltLab/WCF/pull/4575))
- `wcf\form\SearchForm` (see [WoltLab/WCF#4605](https://github.com/WoltLab/WCF/pull/4605))
- `wcf\page\AbstractSecurePage` ([WoltLab/WCF#4515](https://github.com/WoltLab/WCF/pull/4515))
- `wcf\page\SearchResultPage` (see [WoltLab/WCF#4605](https://github.com/WoltLab/WCF/pull/4605))
- `wcf\system\database\table\column\TUnsupportedDefaultValue` (do not implement `IDefaultValueDatabaseTableColumn`, see [WoltLab/WCF#4733](https://github.com/WoltLab/WCF/pull/4733))
- `wcf\system\exception\ILoggingAwareException` ([WoltLab/WCF#4547](https://github.com/WoltLab/WCF/pull/4547))
- `wcf\system\io\FTP` (directly use the FTP extension)
- `wcf\system\search\AbstractSearchableObjectType` (use `AbstractSearchProvider` instead, see [WoltLab/WCF#4605](https://github.com/WoltLab/WCF/pull/4605))
- `wcf\system\search\elasticsearch\ElasticsearchException`
- `wcf\system\search\ISearchableObjectType` (use `ISearchProvider` instead, see [WoltLab/WCF#4605](https://github.com/WoltLab/WCF/pull/4605))
- `wcf\util\PasswordUtil`

#### Methods

- `wcf\action\MessageQuoteAction::markForRemoval()` ([WoltLab/WCF#4452](https://github.com/WoltLab/WCF/pull/4452))
- `wcf\data\user\avatar\UserAvatarAction::fetchRemoteAvatar()` ([WoltLab/WCF#4744](https://github.com/WoltLab/WCF/pull/4744))
- `wcf\data\user\notification\UserNotificationAction::getOutstandingNotifications()` ([WoltLab/WCF#4603](https://github.com/WoltLab/WCF/pull/4603))
- `wcf\data\moderation\queue\ModerationQueueAction::getOutstandingQueues()` ([WoltLab/WCF#4603](https://github.com/WoltLab/WCF/pull/4603))
- `wcf\system\message\QuickReplyManager::setTmpHash()` ([WoltLab/WCF#4575](https://github.com/WoltLab/WCF/pull/4575))
- `wcf\system\request\Request::isExecuted()` ([WoltLab/WCF#4485](https://github.com/WoltLab/WCF/pull/4485))
- `wcf\system\search\elasticsearch\ElasticsearchHandler::query()`
- `wcf\system\session\Session::getDeviceIcon()` ([WoltLab/WCF#4525](https://github.com/WoltLab/WCF/pull/4525))
- `wcf\system\WCF::getAnchor()` ([WoltLab/WCF#4580](https://github.com/WoltLab/WCF/pull/4580))
- `wcf\util\MathUtil::getRandomValue()` ([WoltLab/WCF#4280](https://github.com/WoltLab/WCF/pull/4280))
- `wcf\util\StringUtil::encodeJSON()` ([WoltLab/WCF#4645](https://github.com/WoltLab/WCF/pull/4645))
- `wcf\util\StringUtil::endsWith()` ([WoltLab/WCF#4509](https://github.com/WoltLab/WCF/pull/4509))
- `wcf\util\StringUtil::getHash()` ([WoltLab/WCF#4279](https://github.com/WoltLab/WCF/pull/4279))
- `wcf\util\StringUtil::split()` ([WoltLab/WCF#4513](https://github.com/WoltLab/WCF/pull/4513))
- `wcf\util\StringUtil::startsWith()` ([WoltLab/WCF#4509](https://github.com/WoltLab/WCF/pull/4509))
- `wcf\util\UserUtil::isAvailableEmail()` ([WoltLab/WCF#4602](https://github.com/WoltLab/WCF/pull/4602))
- `wcf\util\UserUtil::isAvailableUsername()` ([WoltLab/WCF#4602](https://github.com/WoltLab/WCF/pull/4602))

#### Properties

- `wcf\acp\page\PackagePage::$compatibleVersions` ([WoltLab/WCF#4371](https://github.com/WoltLab/WCF/pull/4371))
- `wcf\system\io\GZipFile::$gzopen64` ([WoltLab/WCF#4381](https://github.com/WoltLab/WCF/pull/4381))

#### Constants

- `wcf\system\search\elasticsearch\ElasticsearchHandler::DELETE`
- `wcf\system\search\elasticsearch\ElasticsearchHandler::GET`
- `wcf\system\search\elasticsearch\ElasticsearchHandler::POST`
- `wcf\system\search\elasticsearch\ElasticsearchHandler::PUT`
- `wcf\system\visitTracker\VisitTracker::DEFAULT_LIFETIME` ([WoltLab/WCF#4757](https://github.com/WoltLab/WCF/pull/4757))

#### Functions

- The global `escapeString` helper ([WoltLab/WCF#4506](https://github.com/WoltLab/WCF/pull/4506))

#### Options

- `HTTP_SEND_X_FRAME_OPTIONS` ([WoltLab/WCF#4474](https://github.com/WoltLab/WCF/pull/4474))
- `ELASTICSEARCH_ALLOW_LEADING_WILDCARD`
- `WBB_MODULE_IGNORE_BOARDS` (The option will be always on with WoltLab Suite Forum 5.5 and will be removed with a future version.)

### JavaScript

- `WCF.Message.Quote.Manager.markQuotesForRemoval()` ([WoltLab/WCF#4452](https://github.com/WoltLab/WCF/pull/4452))
- `WCF.Search.Message.KeywordList` ([WoltLab/WCF#4402](https://github.com/WoltLab/WCF/pull/4402))
- `SECURITY_TOKEN` (use `Core.getXsrfToken()`, [WoltLab/WCF#4523](https://github.com/WoltLab/WCF/pull/4523))
- `WCF.Dropdown.Interactive.Handler` ([WoltLab/WCF#4603](https://github.com/WoltLab/WCF/pull/4603))
- `WCF.Dropdown.Interactive.Instance` ([WoltLab/WCF#4603](https://github.com/WoltLab/WCF/pull/4603))
- `WCF.User.Panel.Abstract` ([WoltLab/WCF#4603](https://github.com/WoltLab/WCF/pull/4603))

### Database Tables

- `wcf1_package_compatibility` ([WoltLab/WCF#4371](https://github.com/WoltLab/WCF/pull/4371))
- `wcf1_package_update_compatibility` ([WoltLab/WCF#4385](https://github.com/WoltLab/WCF/pull/4385))
- `wcf1_package_update_optional` ([WoltLab/WCF#4432](https://github.com/WoltLab/WCF/pull/4432))

### Templates

#### Template Modifiers

- `|encodeJSON` ([WoltLab/WCF#4645](https://github.com/WoltLab/WCF/pull/4645))

#### Template Events

- `pageNavbarTop::navigationIcons`
- `search::queryOptions`
- `search::authorOptions`
- `search::periodOptions`
- `search::displayOptions`
- `search::generalFields`

### Miscellaneous

- The global option to set a specific style with a request parameter (`$_REQUEST['styleID']`) is deprecated ([WoltLab/WCF@0c0111e946](https://github.com/WoltLab/WCF/commit/0c0111e9466e951d867f43869f040ea4aa27c738))

## Removals

### PHP

#### Classes

- `gallery\util\ExifUtil`
- `wbb\action\BoardQuickSearchAction`
- `wbb\data\thread\NewsList`
- `wbb\data\thread\News`
- `wcf\action\PollAction` ([WoltLab/WCF#4662](https://github.com/WoltLab/WCF/pull/4662))
- `wcf\form\RecaptchaForm` ([WoltLab/WCF#4289](https://github.com/WoltLab/WCF/pull/4289))
- `wcf\system\background\job\ElasticSearchIndexBackgroundJob`
- `wcf\system\cache\builder\TemplateListenerCacheBuilder` ([WoltLab/WCF#4297](https://github.com/WoltLab/WCF/pull/4297))
- `wcf\system\log\modification\ModificationLogHandler` ([WoltLab/WCF#4340](https://github.com/WoltLab/WCF/pull/4340))
- `wcf\system\recaptcha\RecaptchaHandlerV2` ([WoltLab/WCF#4289](https://github.com/WoltLab/WCF/pull/4289))
- `wcf\system\search\SearchKeywordManager` ([WoltLab/WCF#4313](https://github.com/WoltLab/WCF/pull/4313))
- The SCSS compiler’s `Leafo` class aliases ([WoltLab/WCF#4343](https://github.com/WoltLab/WCF/pull/4343), [Migration Guide from 5.2 to 5.3](../wsc52/libraries.md))

#### Methods

- `wbb\data\board\BoardCache::getLabelGroups()`
- `wbb\data\post\PostAction::jumpToExtended()` (this method always threw a `BadMethodCallException`)
- `wbb\data\thread\ThreadAction::countReplies()`
- `wbb\data\thread\ThreadAction::validateCountReplies()`
- `wcf\acp\form\UserGroupOptionForm::verifyPermissions()` ([WoltLab/WCF#4312](https://github.com/WoltLab/WCF/pull/4312))
- `wcf\data\conversation\message\ConversationMessageAction::jumpToExtended()` ([WoltLab/com.woltlab.wcf.conversation#162](https://github.com/WoltLab/com.woltlab.wcf.conversation/pull/162))
- `wcf\data\moderation\queue\ModerationQueueEditor::markAsDone()` ([WoltLab/WCF#4317](https://github.com/WoltLab/WCF/pull/4317))
- `wcf\data\tag\TagCloudTag::getSize()` ([WoltLab/WCF#4325](https://github.com/WoltLab/WCF/pull/4325))
- `wcf\data\tag\TagCloudTag::setSize()` ([WoltLab/WCF#4325](https://github.com/WoltLab/WCF/pull/4325))
- `wcf\data\user\User::getSocialNetworkPrivacySettings()` ([WoltLab/WCF#4308](https://github.com/WoltLab/WCF/pull/4308))
- `wcf\data\user\UserAction::getSocialNetworkPrivacySettings()` ([WoltLab/WCF#4308](https://github.com/WoltLab/WCF/pull/4308))
- `wcf\data\user\UserAction::saveSocialNetworkPrivacySettings()` ([WoltLab/WCF#4308](https://github.com/WoltLab/WCF/pull/4308))
- `wcf\data\user\UserAction::validateGetSocialNetworkPrivacySettings()` ([WoltLab/WCF#4308](https://github.com/WoltLab/WCF/pull/4308))
- `wcf\data\user\UserAction::validateSaveSocialNetworkPrivacySettings()` ([WoltLab/WCF#4308](https://github.com/WoltLab/WCF/pull/4308))
- `wcf\data\user\avatar\DefaultAvatar::canCrop()` ([WoltLab/WCF#4310](https://github.com/WoltLab/WCF/pull/4310))
- `wcf\data\user\avatar\DefaultAvatar::getCropImageTag()` ([WoltLab/WCF#4310](https://github.com/WoltLab/WCF/pull/4310))
- `wcf\data\user\avatar\UserAvatar::canCrop()` ([WoltLab/WCF#4310](https://github.com/WoltLab/WCF/pull/4310))
- `wcf\data\user\avatar\UserAvatar::getCropImageTag()` ([WoltLab/WCF#4310](https://github.com/WoltLab/WCF/pull/4310))
- `wcf\system\bbcode\BBCodeHandler::setAllowedBBCodes()` ([WoltLab/WCF#4319](https://github.com/WoltLab/WCF/pull/4319))
- `wcf\system\bbcode\BBCodeParser::validateBBCodes()` ([WoltLab/WCF#4319](https://github.com/WoltLab/WCF/pull/4319))
- `wcf\system\breadcrumb\Breadcrumbs::add()` ([WoltLab/WCF#4298](https://github.com/WoltLab/WCF/pull/4298))
- `wcf\system\breadcrumb\Breadcrumbs::remove()` ([WoltLab/WCF#4298](https://github.com/WoltLab/WCF/pull/4298))
- `wcf\system\breadcrumb\Breadcrumbs::replace()` ([WoltLab/WCF#4298](https://github.com/WoltLab/WCF/pull/4298))
- `wcf\system\form\builder\IFormNode::create()` (Removal from Interface, see [WoltLab/WCF#4468](https://github.com/WoltLab/WCF/pull/4468))
- `wcf\system\form\builder\IFormNode::validateAttribute()` (Removal from Interface, see [WoltLab/WCF#4468](https://github.com/WoltLab/WCF/pull/4468))
- `wcf\system\form\builder\IFormNode::validateClass()` (Removal from Interface, see [WoltLab/WCF#4468](https://github.com/WoltLab/WCF/pull/4468))
- `wcf\system\form\builder\IFormNode::validateId()` (Removal from Interface, see [WoltLab/WCF#4468](https://github.com/WoltLab/WCF/pull/4468))
- `wcf\system\form\builder\field\IAttributeFormField::validateFieldAttribute()` (Removal from Interface, see [WoltLab/WCF#4468](https://github.com/WoltLab/WCF/pull/4468))
- `wcf\system\form\builder\field\dependency\IFormFieldDependency::create()` (Removal from Interface, see [WoltLab/WCF#4468](https://github.com/WoltLab/WCF/pull/4468))
- `wcf\system\form\builder\field\validation\IFormFieldValidator::validateId()` (Removal from Interface, see [WoltLab/WCF#4468](https://github.com/WoltLab/WCF/pull/4468))
- `wcf\system\message\embedded\object\MessageEmbeddedObjectManager::parseTemporaryMessage()` ([WoltLab/WCF#4299](https://github.com/WoltLab/WCF/pull/4299))
- `wcf\system\package\PackageArchive::getPhpRequirements()` ([WoltLab/WCF#4311](https://github.com/WoltLab/WCF/pull/4311))
- `wcf\system\search\ISearchIndexManager::add()` (Removal from Interface, see [WoltLab/WCF#4508](https://github.com/WoltLab/WCF/pull/4508))
- `wcf\system\search\ISearchIndexManager::update()` (Removal from Interface, see [WoltLab/WCF#4508](https://github.com/WoltLab/WCF/pull/4508))
- `wcf\system\search\elasticsearch\ElasticsearchHandler::_add()`
- `wcf\system\search\elasticsearch\ElasticsearchHandler::_delete()`
- `wcf\system\search\elasticsearch\ElasticsearchHandler::add()`
- `wcf\system\search\elasticsearch\ElasticsearchHandler::bulkAdd()`
- `wcf\system\search\elasticsearch\ElasticsearchHandler::bulkDelete()`
- `wcf\system\search\elasticsearch\ElasticsearchHandler::delete()`
- `wcf\system\search\elasticsearch\ElasticsearchHandler::update()`
- `wcf\system\search\elasticsearch\ElasticsearchSearchIndexManager::add()`
- `wcf\system\search\elasticsearch\ElasticsearchSearchIndexManager::update()`
- `wcf\system\search\elasticsearch\ElasticsearchSearchEngine::parseSearchQuery()`

#### Properties

- `wcf\data\category\Category::$permissions` ([WoltLab/WCF#4303](https://github.com/WoltLab/WCF/pull/4303))
- `wcf\system\search\elasticsearch\ElasticsearchSearchIndexManager::$bulkTypeName`

#### Constants

- `wcf\system\search\elasticsearch\ElasticsearchHandler::HEAD`
- `wcf\system\tagging\TagCloud::MAX_FONT_SIZE` ([WoltLab/WCF#4325](https://github.com/WoltLab/WCF/pull/4325))
- `wcf\system\tagging\TagCloud::MIN_FONT_SIZE` ([WoltLab/WCF#4325](https://github.com/WoltLab/WCF/pull/4325))

#### Options

- `ENABLE_CENSORSHIP` (always call `Censorship::test()`, see [WoltLab/WCF#4567](https://github.com/WoltLab/WCF/pull/4567))
- `MODULE_SYSTEM_RECAPTCHA` ([WoltLab/WCF#4305](https://github.com/WoltLab/WCF/pull/4305))
- `PROFILE_MAIL_USE_CAPTCHA` ([WoltLab/WCF#4399](https://github.com/WoltLab/WCF/pull/4399))
- The `may` value for `MAIL_SMTP_STARTTLS` ([WoltLab/WCF#4398](https://github.com/WoltLab/WCF/pull/4398))
- `SEARCH_USE_CAPTCHA` (see [WoltLab/WCF#4605](https://github.com/WoltLab/WCF/pull/4605))

#### Files

- `acp/dereferrer.php`


### JavaScript

- `Blog.Entry.QuoteHandler` (use `WoltLabSuite/Blog/Ui/Entry/Quote`)
- `Calendar.Event.QuoteHandler` (use `WoltLabSuite/Calendar/Ui/Event/Quote`)
- `WBB.Board.IgnoreBoards` (use `WoltLabSuite/Forum/Ui/Board/Ignore`)
- `WBB.Board.MarkAllAsRead` (use `WoltLabSuite/Forum/Ui/Board/MarkAllAsRead`)
- `WBB.Board.MarkAsRead` (use `WoltLabSuite/Forum/Ui/Board/MarkAsRead`)
- `WBB.Post.QuoteHandler` (use `WoltLabSuite/Forum/Ui/Post/Quote`)
- `WBB.Thread.LastPageHandler` (use `WoltLabSuite/Forum/Ui/Thread/LastPageHandler`)
- `WBB.Thread.MarkAsRead` (use `WoltLabSuite/Forum/Ui/Thread/MarkAsRead`)
- `WBB.Thread.SimilarThreads` (use `WoltLabSuite/Forum/Ui/Thread/SimilarThreads`)
- `WBB.Thread.WatchedThreadList` (use `WoltLabSuite/Forum/Controller/Thread/WatchedList`)
- `WCF.ACP.Style.ImageUpload` ([WoltLab/WCF#4323](https://github.com/WoltLab/WCF/pull/4323))
- `WCF.ColorPicker` (see [migration guide for `WCF.ColorPicker`](javascript.md#wcfcolorpicker))
- `WCF.Conversation.Message.QuoteHandler` (use `WoltLabSuite/Core/Conversation/Ui/Message/Quote`, see [WoltLab/com.woltlab.wcf.conversation#155](https://github.com/WoltLab/com.woltlab.wcf.conversation/pull/155))
- `WCF.Like.js` ([WoltLab/WCF#4300](https://github.com/WoltLab/WCF/pull/4300))
- `WCF.Message.UserMention` ([WoltLab/WCF#4324](https://github.com/WoltLab/WCF/pull/4324))
- `WCF.Poll.Manager` ([WoltLab/WCF#4662](https://github.com/WoltLab/WCF/pull/4662))
- `WCF.UserPanel` ([WoltLab/WCF#4316](https://github.com/WoltLab/WCF/pull/4316))
- `WCF.User.Panel.Moderation` ([WoltLab/WCF#4603](https://github.com/WoltLab/WCF/pull/4603))
- `WCF.User.Panel.Notification` ([WoltLab/WCF#4603](https://github.com/WoltLab/WCF/pull/4603))
- `WCF.User.Panel.UserMenu` ([WoltLab/WCF#4603](https://github.com/WoltLab/WCF/pull/4603))


### Phrases

- `wbb.search.boards.all`
- `wcf.global.form.error.greaterThan.javaScript` ([WoltLab/WCF#4306](https://github.com/WoltLab/WCF/pull/4306))
- `wcf.global.form.error.lessThan.javaScript` ([WoltLab/WCF#4306](https://github.com/WoltLab/WCF/pull/4306))
- `wcf.search.type.keywords`
- `wcf.acp.option.search_use_captcha`
- `wcf.search.query.description`
- `wcf.search.results.change`
- `wcf.search.results.description`
- `wcf.search.general`
- `wcf.search.query`
- `wcf.search.error.noMatches`
- `wcf.search.error.user.noMatches`


### Templates

#### Templates

- `searchResult`

#### Template Events

- `search::tabMenuTabs`
- `search::sections`
- `tagSearch::tabMenuTabs`
- `tagSearch::sections`

### Miscellaneous

- Object specific VisitTracker lifetimes ([WoltLab/WCF#4757](https://github.com/WoltLab/WCF/pull/4757))
