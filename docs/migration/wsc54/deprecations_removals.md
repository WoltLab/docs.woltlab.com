# Migrating from WSC 5.4 - Deprecations and Removals

With version 5.5, we have deprecated certain components and removed several other components that have been deprecated for many years.



## Deprecations

### PHP

#### Classes

- `filebase\system\file\FileDataHandler` (use `filebase\system\cache\runtime\FileRuntimeCache` instead)
- `wcf\system\io\FTP` (directly use the FTP extension instead)

#### Methods

- `wcf\util\MathUtil::getRandomValue()` ([WoltLab/WCF#4280](https://github.com/WoltLab/WCF/pull/4280))
- `wcf\util\StringUtil::getHash()` ([WoltLab/WCF#4279](https://github.com/WoltLab/WCF/pull/4279))



## Removals

### PHP

#### Classes

- `gallery\util\ExifUtil`
- `wbb\action\BoardQuickSearchAction`
- `wbb\data\thread\News`
- `wbb\data\thread\NewsList`
- `wcf\form\RecaptchaForm` ([WoltLab/WCF#4289](https://github.com/WoltLab/WCF/pull/4289))
- `wcf\system\cache\builder\TemplateListenerCacheBuilder` ([WoltLab/WCF#4297](https://github.com/WoltLab/WCF/pull/4297))
- `wcf\system\log\modification\ModificationLogHandler` ([WoltLab/WCF#4340](https://github.com/WoltLab/WCF/pull/4340))
- `wcf\system\recaptcha\RecaptchaHandlerV2` ([WoltLab/WCF#4289](https://github.com/WoltLab/WCF/pull/4289))
- `wcf\util\PasswordUtil`
- The SCSS compiler’s `Leafo` class aliases ([WoltLab/WCF#4343](https://github.com/WoltLab/WCF/pull/4343), [Migration Guide from 5.2 to 5.3](../wsc52/libraries.md))

#### Methods

- `wbb\data\board\BoardCache::getLabelGroups()`
- `wbb\data\thread\ThreadAction::countReplies()`
- `wbb\data\thread\ThreadAction::validateCountReplies()`
- `wcf\acp\form\UserGroupOptionForm::verifyPermissions()` ([WoltLab/WCF#4312](https://github.com/WoltLab/WCF/pull/4312))
- `wcf\data\moderation\queue\ModerationQueueEditor::markAsDone()` ([WoltLab/WCF#4317](https://github.com/WoltLab/WCF/pull/4317))
- `wcf\data\user\avatar\DefaultAvatar::canCrop()` ([WoltLab/WCF#4310](https://github.com/WoltLab/WCF/pull/4310))
- `wcf\data\user\avatar\DefaultAvatar::getCropImageTag()` ([WoltLab/WCF#4310](https://github.com/WoltLab/WCF/pull/4310))
- `wcf\data\user\avatar\UserAvatar::canCrop()` ([WoltLab/WCF#4310](https://github.com/WoltLab/WCF/pull/4310))
- `wcf\data\user\avatar\UserAvatar::getCropImageTag()` ([WoltLab/WCF#4310](https://github.com/WoltLab/WCF/pull/4310))
- `wcf\data\user\User::getSocialNetworkPrivacySettings()` ([WoltLab/WCF#4308](https://github.com/WoltLab/WCF/pull/4308))
- `wcf\data\user\UserAction::getSocialNetworkPrivacySettings()` ([WoltLab/WCF#4308](https://github.com/WoltLab/WCF/pull/4308))
- `wcf\data\user\UserAction::saveSocialNetworkPrivacySettings()` ([WoltLab/WCF#4308](https://github.com/WoltLab/WCF/pull/4308))
- `wcf\data\user\UserAction::validateGetSocialNetworkPrivacySettings()` ([WoltLab/WCF#4308](https://github.com/WoltLab/WCF/pull/4308))
- `wcf\data\user\UserAction::validateSaveSocialNetworkPrivacySettings()` ([WoltLab/WCF#4308](https://github.com/WoltLab/WCF/pull/4308))
- `wcf\system\package\PackageArchive::getPhpRequirements` ([WoltLab/WCF#4311](https://github.com/WoltLab/WCF/pull/4311))
- `wcf\data\tag\TagCloudTag::getSize()` ([WoltLab/WCF#4325](https://github.com/WoltLab/WCF/pull/4325))
- `wcf\data\tag\TagCloudTag::setSize()` ([WoltLab/WCF#4325](https://github.com/WoltLab/WCF/pull/4325))
- `wcf\system\bbcode\BBCodeParser::validateBBCodes()` ([WoltLab/WCF#4319](https://github.com/WoltLab/WCF/pull/4319))
- `wcf\system\bbcode\BBCodeHandler::setAllowedBBCodes()` ([WoltLab/WCF#4319](https://github.com/WoltLab/WCF/pull/4319))
- `wcf\system\breadcrumb\Breadcrumbs::add()` ([WoltLab/WCF#4298](https://github.com/WoltLab/WCF/pull/4298))
- `wcf\system\breadcrumb\Breadcrumbs::remove()` ([WoltLab/WCF#4298](https://github.com/WoltLab/WCF/pull/4298))
- `wcf\system\breadcrumb\Breadcrumbs::replace()` ([WoltLab/WCF#4298](https://github.com/WoltLab/WCF/pull/4298))
- `wcf\system\message\embedded\object\MessageEmbeddedObjectManager::parseTemporaryMessage()` ([WoltLab/WCF#4299](https://github.com/WoltLab/WCF/pull/4299))
- `wcf\system\search\SearchKeywordManager` ([WoltLab/WCF#4313](https://github.com/WoltLab/WCF/pull/4313))

#### Properties

- `wcf\data\category\Category::$permissions` ([WoltLab/WCF#4303](https://github.com/WoltLab/WCF/pull/4303))

#### Constants

- `wcf\system\tagging\TagCloud::MAX_FONT_SIZE` ([WoltLab/WCF#4325](https://github.com/WoltLab/WCF/pull/4325))
- `wcf\system\tagging\TagCloud::MIN_FONT_SIZE` ([WoltLab/WCF#4325](https://github.com/WoltLab/WCF/pull/4325))

#### Options

- `MODULE_SYSTEM_RECAPTCHA` ([WoltLab/WCF#4305](https://github.com/WoltLab/WCF/pull/4305))

#### Files

- `acp/dereferrer.php`


### JavaScript

- `WBB.Board.MarkAsRead` (use `WoltLabSuite/Forum/Ui/Board/MarkAsRead` instead)
- `WCF.ACP.Style.ImageUpload` ([WoltLab/WCF#4323](https://github.com/WoltLab/WCF/pull/4323))
- `WCF.Like.js` ([WoltLab/WCF#4300](https://github.com/WoltLab/WCF/pull/4300))
- `WCF.Message.UserMention` ([WoltLab/WCF#4324](https://github.com/WoltLab/WCF/pull/4324))
- `WCF.UserPanel` ([WoltLab/WCF#4316](https://github.com/WoltLab/WCF/pull/4316))


### Phrases

- `wbb.search.boards.all`
- `wcf.global.form.error.lessThan.javaScript` ([WoltLab/WCF#4306](https://github.com/WoltLab/WCF/pull/4306))
- `wcf.global.form.error.greaterThan.javaScript` ([WoltLab/WCF#4306](https://github.com/WoltLab/WCF/pull/4306))
