# Migrating from WSC 5.4 - Deprecations and Removals

With version 5.5, we have deprecated certain components and removed several other components that have been deprecated for many years.



## Deprecations

### PHP

#### Classes

- `filebase\system\file\FileDataHandler` (use `filebase\system\cache\runtime\FileRuntimeCache`)
- `wcf\action\AbstractAjaxAction` (use PSR-7 responses, [WoltLab/WCF#4437](https://github.com/WoltLab/WCF/pull/4437))
- `wcf\page\AbstractSecurePage` ([WoltLab/WCF#4515](https://github.com/WoltLab/WCF/pull/4515))
- `wcf\system\io\FTP` (directly use the FTP extension)
- `wcf\util\PasswordUtil`

#### Methods

- `wcf\action\MessageQuoteAction::markForRemoval()` ([WoltLab/WCF#4452](https://github.com/WoltLab/WCF/pull/4452))
- `wcf\system\request\Request::isExecuted()` ([WoltLab/WCF#4485](https://github.com/WoltLab/WCF/pull/4485))
- `wcf\util\MathUtil::getRandomValue()` ([WoltLab/WCF#4280](https://github.com/WoltLab/WCF/pull/4280))
- `wcf\util\StringUtil::getHash()` ([WoltLab/WCF#4279](https://github.com/WoltLab/WCF/pull/4279))
- `wcf\util\StringUtil::startsWith()` ([WoltLab/WCF#4509](https://github.com/WoltLab/WCF/pull/4509))
- `wcf\util\StringUtil::endsWith()` ([WoltLab/WCF#4509](https://github.com/WoltLab/WCF/pull/4509))
- `wcf\util\StringUtil::split()` ([WoltLab/WCF#4513](https://github.com/WoltLab/WCF/pull/4513))
- `wcf\system\session\Session::getDeviceIcon()` ([WoltLab/WCF#4525](https://github.com/WoltLab/WCF/pull/4525))

#### Properties

- `wcf\acp\page\PackagePage::$compatibleVersions` ([WoltLab/WCF#4371](https://github.com/WoltLab/WCF/pull/4371))
- `wcf\system\io\GZipFile::$gzopen64` ([WoltLab/WCF#4381](https://github.com/WoltLab/WCF/pull/4381))

#### Functions

- The global `escapeString` helper ([WoltLab/WCF#4506](https://github.com/WoltLab/WCF/pull/4506))

#### Options

- `HTTP_SEND_X_FRAME_OPTIONS` ([WoltLab/WCF#4474](https://github.com/WoltLab/WCF/pull/4474))

### JavaScript

- `WCF.Message.Quote.Manager.markQuotesForRemoval()` ([WoltLab/WCF#4452](https://github.com/WoltLab/WCF/pull/4452))
- `WCF.Search.Message.KeywordList` ([WoltLab/WCF#4402](https://github.com/WoltLab/WCF/pull/4402))

### Database Tables

- `wcf1_package_compatibility` ([WoltLab/WCF#4371](https://github.com/WoltLab/WCF/pull/4371))
- `wcf1_package_update_compatibility` ([WoltLab/WCF#4385](https://github.com/WoltLab/WCF/pull/4385))
- `wcf1_package_update_optional` ([WoltLab/WCF#4432](https://github.com/WoltLab/WCF/pull/4432))

## Removals

### PHP

#### Classes

- `gallery\util\ExifUtil`
- `wbb\action\BoardQuickSearchAction`
- `wbb\data\thread\NewsList`
- `wbb\data\thread\News`
- `wcf\form\RecaptchaForm` ([WoltLab/WCF#4289](https://github.com/WoltLab/WCF/pull/4289))
- `wcf\system\cache\builder\TemplateListenerCacheBuilder` ([WoltLab/WCF#4297](https://github.com/WoltLab/WCF/pull/4297))
- `wcf\system\log\modification\ModificationLogHandler` ([WoltLab/WCF#4340](https://github.com/WoltLab/WCF/pull/4340))
- `wcf\system\recaptcha\RecaptchaHandlerV2` ([WoltLab/WCF#4289](https://github.com/WoltLab/WCF/pull/4289))
- `wcf\system\search\SearchKeywordManager` ([WoltLab/WCF#4313](https://github.com/WoltLab/WCF/pull/4313))
- The SCSS compiler’s `Leafo` class aliases ([WoltLab/WCF#4343](https://github.com/WoltLab/WCF/pull/4343), [Migration Guide from 5.2 to 5.3](../wsc52/libraries.md))

#### Methods

- `wbb\data\board\BoardCache::getLabelGroups()`
- `wbb\data\thread\ThreadAction::countReplies()`
- `wbb\data\thread\ThreadAction::validateCountReplies()`
- `wcf\acp\form\UserGroupOptionForm::verifyPermissions()` ([WoltLab/WCF#4312](https://github.com/WoltLab/WCF/pull/4312))
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
- `wcf\system\message\embedded\object\MessageEmbeddedObjectManager::parseTemporaryMessage()` ([WoltLab/WCF#4299](https://github.com/WoltLab/WCF/pull/4299))
- `wcf\system\package\PackageArchive::getPhpRequirements()` ([WoltLab/WCF#4311](https://github.com/WoltLab/WCF/pull/4311))
- `wcf\system\search\ISearchIndexManager::add()` (Removal from Interface, see [WoltLab/WCF#4508](https://github.com/WoltLab/WCF/pull/4508))
- `wcf\system\search\ISearchIndexManager::update()` (Removal from Interface, see [WoltLab/WCF#4508](https://github.com/WoltLab/WCF/pull/4508))

#### Properties

- `wcf\data\category\Category::$permissions` ([WoltLab/WCF#4303](https://github.com/WoltLab/WCF/pull/4303))

#### Constants

- `wcf\system\search\elasticsearch\ElasticsearchHandler::HEAD`
- `wcf\system\tagging\TagCloud::MAX_FONT_SIZE` ([WoltLab/WCF#4325](https://github.com/WoltLab/WCF/pull/4325))
- `wcf\system\tagging\TagCloud::MIN_FONT_SIZE` ([WoltLab/WCF#4325](https://github.com/WoltLab/WCF/pull/4325))

#### Options

- `MODULE_SYSTEM_RECAPTCHA` ([WoltLab/WCF#4305](https://github.com/WoltLab/WCF/pull/4305))
- `PROFILE_MAIL_USE_CAPTCHA` ([WoltLab/WCF#4399](https://github.com/WoltLab/WCF/pull/4399))
- The `may` value for `MAIL_SMTP_STARTTLS` ([WoltLab/WCF#4398](https://github.com/WoltLab/WCF/pull/4398))

#### Files

- `acp/dereferrer.php`


### JavaScript

- `Blog.Entry.QuoteHandler` (use `WoltLabSuite/Blog/Ui/Entry/Quote`)
- `Calendar.Event.QuoteHandler` (use `WoltLabSuite/Calendar/Ui/Event/Quote`)
- `WBB.Board.MarkAllAsRead` (use `WoltLabSuite/Forum/Ui/Board/MarkAllAsRead`)
- `WBB.Board.MarkAsRead` (use `WoltLabSuite/Forum/Ui/Board/MarkAsRead`)
- `WBB.Post.QuoteHandler` (use `WoltLabSuite/Forum/Ui/Post/Quote`)
- `WBB.Thread.LastPageHandler` (use `WoltLabSuite/Forum/Ui/Thread/LastPageHandler`)
- `WBB.Thread.MarkAsRead` (use `WoltLabSuite/Forum/Ui/Thread/MarkAsRead`)
- `WCF.ACP.Style.ImageUpload` ([WoltLab/WCF#4323](https://github.com/WoltLab/WCF/pull/4323))
- `WCF.ColorPicker` (see [migration guide for `WCF.ColorPicker`](javascript.md#wcfcolorpicker))
- `WCF.Conversation.Message.QuoteHandler` (use `WoltLabSuite/Core/Conversation/Ui/Message/Quote`, see [WoltLab/com.woltlab.wcf.conversation#155](https://github.com/WoltLab/com.woltlab.wcf.conversation/pull/155))
- `WCF.Like.js` ([WoltLab/WCF#4300](https://github.com/WoltLab/WCF/pull/4300))
- `WCF.Message.UserMention` ([WoltLab/WCF#4324](https://github.com/WoltLab/WCF/pull/4324))
- `WCF.UserPanel` ([WoltLab/WCF#4316](https://github.com/WoltLab/WCF/pull/4316))


### Phrases

- `wbb.search.boards.all`
- `wcf.global.form.error.greaterThan.javaScript` ([WoltLab/WCF#4306](https://github.com/WoltLab/WCF/pull/4306))
- `wcf.global.form.error.lessThan.javaScript` ([WoltLab/WCF#4306](https://github.com/WoltLab/WCF/pull/4306))
