# Migrating from WoltLab Suite 5.5 - Deprecations and Removals

With version 6.0, we have deprecated certain components and removed several other components that have been deprecated for many years.

## Deprecations

### PHP

#### Classes

- `wcf\action\AbstractDialogAction` ([WoltLab/WCF#4947](https://github.com/WoltLab/WCF/pull/4947))
- `wcf\SensitiveArgument` ([WoltLab/WCF#4802](https://github.com/WoltLab/WCF/pull/4802))
- `wcf\system\cli\command\IArgumentedCLICommand` ([WoltLab/WCF#5185](https://github.com/WoltLab/WCF/pull/5185))
- `wcf\system\template\plugin\DateModifierTemplatePlugin` ([WoltLab/WCF#5459](https://github.com/WoltLab/WCF/pull/5459))
- `wcf\system\template\plugin\TimeModifierTemplatePlugin` ([WoltLab/WCF#5459](https://github.com/WoltLab/WCF/pull/5459))
- `wcf\system\template\plugin\PlainTimeModifierTemplatePlugin` ([WoltLab/WCF#5459](https://github.com/WoltLab/WCF/pull/5459))
- `wcf\util\CronjobUtil` ([WoltLab/WCF#4923](https://github.com/WoltLab/WCF/pull/4923))

#### Methods

- `wcf\data\cronjob\CronjobAction::executeCronjobs()` ([WoltLab/WCF#5171](https://github.com/WoltLab/WCF/pull/5171))
- `wcf\data\package\update\server\PackageUpdateServer::attemptSecureConnection()` ([WoltLab/WCF#4790](https://github.com/WoltLab/WCF/pull/4790))
- `wcf\data\package\update\server\PackageUpdateServer::isValidServerURL()` ([WoltLab/WCF#4790](https://github.com/WoltLab/WCF/pull/4790))
- `wcf\data\page\Page::setAsLandingPage()` ([WoltLab/WCF#4842](https://github.com/WoltLab/WCF/pull/4842))
- `wcf\data\style\Style::getRelativeFavicon()` ([WoltLab/WCF#5201](https://github.com/WoltLab/WCF/pull/5201))
- `wcf\system\cli\command\CLICommandHandler::getCommands()` ([WoltLab/WCF#5185](https://github.com/WoltLab/WCF/pull/5185))
- `wcf\system\io\RemoteFile::disableSSL()` ([WoltLab/WCF#4790](https://github.com/WoltLab/WCF/pull/4790))
- `wcf\system\io\RemoteFile::supportsSSL()` ([WoltLab/WCF#4790](https://github.com/WoltLab/WCF/pull/4790))
- `wcf\system\request\RequestHandler::inRescueMode()` ([WoltLab/WCF#4831](https://github.com/WoltLab/WCF/pull/4831))
- `wcf\system\session\SessionHandler::getLanguageIDs()` ([WoltLab/WCF#4839](https://github.com/WoltLab/WCF/pull/4839))
- `wcf\system\user\multifactor\webauthn\Challenge::getOptionsAsJson()`
- `wcf\system\WCF::getActivePath()` ([WoltLab/WCF#4827](https://github.com/WoltLab/WCF/pull/4827))
- `wcf\system\WCF::getFavicon()` ([WoltLab/WCF#4785](https://github.com/WoltLab/WCF/pull/4785))
- `wcf\system\WCF::useDesktopNotifications()` ([WoltLab/WCF#4785](https://github.com/WoltLab/WCF/pull/4785))
- `wcf\util\CryptoUtil::validateSignedString()` ([WoltLab/WCF#5083](https://github.com/WoltLab/WCF/pull/5083))
- `wcf\util\Diff::__construct()` ([WoltLab/WCF#4918](https://github.com/WoltLab/WCF/pull/4918))
- `wcf\util\Diff::__toString()` ([WoltLab/WCF#4918](https://github.com/WoltLab/WCF/pull/4918))
- `wcf\util\Diff::getLCS()` ([WoltLab/WCF#4918](https://github.com/WoltLab/WCF/pull/4918))
- `wcf\util\Diff::getRawDiff()` ([WoltLab/WCF#4918](https://github.com/WoltLab/WCF/pull/4918))
- `wcf\util\Diff::getUnixDiff()` ([WoltLab/WCF#4918](https://github.com/WoltLab/WCF/pull/4918))
- `wcf\util\StringUtil::convertEncoding()` ([WoltLab/WCF#4800](https://github.com/WoltLab/WCF/pull/4800))

#### Properties

#### Constants

- `wcf\system\condition\UserAvatarCondition::GRAVATAR` ([WoltLab/WCF#4929](https://github.com/WoltLab/WCF/pull/4929))

#### Functions

#### Options

### JavaScript

- `WCF.Comment` ([WoltLab/WCF#5210](https://github.com/WoltLab/WCF/pull/5210))
- `WCF.Location` ([WoltLab/WCF#4972](https://github.com/WoltLab/WCF/pull/4972))
- `WCF.Message.Share.Content` ([WoltLab/WCF/commit/624b9db73daf8030aa1c3e49d4ffc785760a283f](https://github.com/WoltLab/WCF/commit/624b9db73daf8030aa1c3e49d4ffc785760a283f))
- `WCF.User.ObjectWatch.Subscribe` ([WoltLab/WCF#4962](https://github.com/WoltLab/WCF/pull/4962))
- `WCF.User.List` ([WoltLab/WCF#5039](https://github.com/WoltLab/WCF/pull/5039))
- `WoltLabSuite/Core/Controller/Map/Route/Planner` ([WoltLab/WCF#4972](https://github.com/WoltLab/WCF/pull/4972))
- `WoltLabSuite/Core/NumberUtil` ([WoltLab/WCF#5071](https://github.com/WoltLab/WCF/pull/5071))
- `WoltLabSuite/Core/Ui/User/List` ([WoltLab/WCF#5039](https://github.com/WoltLab/WCF/pull/5039))

### Database Tables

### Templates

- `__commentJavaScript` ([WoltLab/WCF#5210](https://github.com/WoltLab/WCF/pull/5210))
- `commentListAddComment` ([WoltLab/WCF#5210](https://github.com/WoltLab/WCF/pull/5210))

#### Template Modifiers

#### Template Events

### Miscellaneous

## Removals

### PHP

#### Classes

- `calendar\data\CALENDARDatabaseObject`
- `calendar\system\image\EventDataHandler`
- `gallery\data\GalleryDatabaseObject`
- `gallery\system\image\ImageDataHandler`
- `wbb\system\user\object\watch\BoardUserObjectWatch` and the corresponding object type
- `wbb\system\user\object\watch\ThreadUserObjectWatch` and the corresponding object type
- `wcf\acp\form\ApplicationEditForm` ([WoltLab/WCF#4785](https://github.com/WoltLab/WCF/pull/4785))
- `wcf\action\GravatarDownloadAction` ([WoltLab/WCF#4929](https://github.com/WoltLab/WCF/pull/4929))
- `wcf\data\user\avatar\Gravatar` ([WoltLab/WCF#4929](https://github.com/WoltLab/WCF/pull/4929))
- `wcf\system\bbcode\highlighter\*Highlighter` ([WoltLab/WCF#4926](https://github.com/WoltLab/WCF/pull/4926))
- `wcf\system\bbcode\highlighter\Highlighter` ([WoltLab/WCF#4926](https://github.com/WoltLab/WCF/pull/4926))
- `wcf\system\cache\source\MemcachedCacheSource` ([WoltLab/WCF#4928](https://github.com/WoltLab/WCF/pull/4928))
- `wcf\system\cli\command\CLICommandNameCompleter` ([WoltLab/WCF#5185](https://github.com/WoltLab/WCF/pull/5185))
- `wcf\system\cli\command\CommandsCLICommand` ([WoltLab/WCF#5185](https://github.com/WoltLab/WCF/pull/5185))
- `wcf\system\cli\command\CronjobCLICommand` ([WoltLab/WCF#5171](https://github.com/WoltLab/WCF/pull/5171))
- `wcf\system\cli\command\HelpCLICommand` ([WoltLab/WCF#5185](https://github.com/WoltLab/WCF/pull/5185))
- `wcf\system\cli\command\PackageCLICommand` ([WoltLab/WCF#4946](https://github.com/WoltLab/WCF/pull/4946))
- `wcf\system\cli\DatabaseCLICommandHistory` ([WoltLab/WCF#5058](https://github.com/WoltLab/WCF/pull/5058))
- `wcf\system\database\table\column/\UnsupportedDefaultValue` ([WoltLab/WCF#5012](https://github.com/WoltLab/WCF/pull/5012))
- `wcf\system\exception\ILoggingAwareException` (and associated functionality) ([WoltLab/WCF#5086](https://github.com/WoltLab/WCF/pull/5086))
- `wcf\system\mail\Mail` ([WoltLab/WCF#4941](https://github.com/WoltLab/WCF/pull/4941))
- `wcf\system\option\DesktopNotificationApplicationSelectOptionType` ([WoltLab/WCF#4785](https://github.com/WoltLab/WCF/pull/4785))
- `wcf\system\search\elasticsearch\ElasticsearchException`

#### Methods

- The `$forceHTTP` parameter of `wcf\data\package\update\server\PackageUpdateServer::getListURL()` ([WoltLab/WCF#4790](https://github.com/WoltLab/WCF/pull/4790))
- The `$forceHTTP` parameter of `wcf\system\package\PackageUpdateDispatcher::getPackageUpdateXML()` ([WoltLab/WCF#4790](https://github.com/WoltLab/WCF/pull/4790))
- `wcf\data\bbcode\BBCodeCache::getHighlighters()` ([WoltLab/WCF#4926](https://github.com/WoltLab/WCF/pull/4926))
- `wcf\data\conversation\ConversationAction::getMixedConversationList()` ([WoltLab/com.woltlab.wcf.conversation#176](https://github.com/WoltLab/com.woltlab.wcf.conversation/pull/176))
- `wcf\data\moderation\queue\ModerationQueueAction::getOutstandingQueues()` ([WoltLab/WCF#4944](https://github.com/WoltLab/WCF/pull/4944))
- `wcf\data\package\installation\queue\PackageInstallationQueueAction::prepareQueue()` ([WoltLab/WCF#4997](https://github.com/WoltLab/WCF/pull/4997))
- `wcf\data\user\avatar\UserAvatarAction::enforceDimensions()` ([WoltLab/WCF#5007](https://github.com/WoltLab/WCF/pull/5007))
- `wcf\data\user\avatar\UserAvatarAction::fetchRemoteAvatar()` ([WoltLab/WCF#5007](https://github.com/WoltLab/WCF/pull/5007))
- `wcf\data\user\notification\UserNotificationAction::getOustandingNotifications()` ([WoltLab/WCF#4944](https://github.com/WoltLab/WCF/pull/4944))
- `wcf\data\user\UserRegistrationAction::validatePassword()` ([WoltLab/WCF#5244](https://github.com/WoltLab/WCF/pull/5244))
- `wcf\system\bbcode\BBCodeParser::getRemoveLinks()` ([WoltLab/WCF#4986](https://github.com/WoltLab/WCF/pull/4986))
- `wcf\system\bbcode\HtmlBBCodeParser::setRemoveLinks()` ([WoltLab/WCF#4986](https://github.com/WoltLab/WCF/pull/4986))
- `wcf\system\html\output\node\AbstractHtmlOutputNode::setRemoveLinks()` ([WoltLab/WCF#4986](https://github.com/WoltLab/WCF/pull/4986))
- `wcf\system\package\PackageArchive::downloadArchive()` ([WoltLab/WCF#5006](https://github.com/WoltLab/WCF/pull/5006))
- `wcf\system\package\PackageArchive::filterUpdateInstructions()` ([WoltLab/WCF#5129](https://github.com/WoltLab/WCF/pull/5129))
- `wcf\system\package\PackageArchive::getAllExistingRequirements()` ([WoltLab/WCF#5125](https://github.com/WoltLab/WCF/pull/5125))
- `wcf\system\package\PackageArchive::getInstructions()` ([WoltLab/WCF#5120](https://github.com/WoltLab/WCF/pull/5120))
- `wcf\system\package\PackageArchive::getUpdateInstructions()` ([WoltLab/WCF#5129](https://github.com/WoltLab/WCF/pull/5129))
- `wcf\system\package\PackageArchive::isValidInstall()` ([WoltLab/WCF#5125](https://github.com/WoltLab/WCF/pull/5125))
- `wcf\system\package\PackageArchive::isValidUpdate()` ([WoltLab/WCF#5126](https://github.com/WoltLab/WCF/pull/5126))
- `wcf\system\package\PackageArchive::setPackage()` ([WoltLab/WCF#5120](https://github.com/WoltLab/WCF/pull/5120))
- `wcf\system\package\PackageArchive::unzipPackageArchive()` ([WoltLab/WCF#4949](https://github.com/WoltLab/WCF/pull/4949))
- `wcf\system\package\PackageInstallationDispatcher::checkPackageInstallationQueue()` ([WoltLab/WCF#4947](https://github.com/WoltLab/WCF/pull/4947))
- `wcf\system\package\PackageInstallationDispatcher::completeSetup()` ([WoltLab/WCF#4947](https://github.com/WoltLab/WCF/pull/4947))
- `wcf\system\package\PackageInstallationDispatcher::convertShorthandByteValue()` ([WoltLab/WCF#4949](https://github.com/WoltLab/WCF/pull/4949))
- `wcf\system\package\PackageInstallationDispatcher::functionExists()` ([WoltLab/WCF#4949](https://github.com/WoltLab/WCF/pull/4949))
- `wcf\system\package\PackageInstallationDispatcher::openQueue()` ([WoltLab/WCF#4947](https://github.com/WoltLab/WCF/pull/4947))
- `wcf\system\package\PackageInstallationDispatcher::validatePHPRequirements()` ([WoltLab/WCF#4949](https://github.com/WoltLab/WCF/pull/4949))
- `wcf\system\package\PackageInstallationNodeBuilder::insertNode()` ([WoltLab/WCF#4997](https://github.com/WoltLab/WCF/pull/4997))
- `wcf\system\package\PackageUpdateDispatcher::prepareInstallation()` ([WoltLab/WCF#4997](https://github.com/WoltLab/WCF/pull/4997))
- `wcf\system\request\Request::execute()` ([WoltLab/WCF#4820](https://github.com/WoltLab/WCF/pull/4820))
- `wcf\system\request\Request::getPageType()` ([WoltLab/WCF#4822](https://github.com/WoltLab/WCF/pull/4822))
- `wcf\system\request\Request::getPageType()` ([WoltLab/WCF#4822](https://github.com/WoltLab/WCF/pull/4822))
- `wcf\system\request\Request::isExecuted()`
- `wcf\system\request\Request::setIsLandingPage()`
- `wcf\system\request\RouteHandler::getDefaultController()` ([WoltLab/WCF#4832](https://github.com/WoltLab/WCF/pull/4832))
- `wcf\system\request\RouteHandler::loadDefaultControllers()` ([WoltLab/WCF#4832](https://github.com/WoltLab/WCF/pull/4832))
- `wcf\system\search\AbstractSearchEngine::getFulltextMinimumWordLength()` ([WoltLab/WCF#4933](https://github.com/WoltLab/WCF/pull/4933))
- `wcf\system\search\AbstractSearchEngine::parseSearchQuery()` ([WoltLab/WCF#4933](https://github.com/WoltLab/WCF/pull/4933))
- `wcf\system\search\elasticsearch\ElasticsearchSearchEngine::getFulltextMinimumWordLength()`
- `wcf\system\search\elasticsearch\ElasticsearchHandler::query()`
- `wcf\system\search\SearchIndexManager::add()` ([WoltLab/WCF#4925](https://github.com/WoltLab/WCF/pull/4925))
- `wcf\system\search\SearchIndexManager::update()` ([WoltLab/WCF#4925](https://github.com/WoltLab/WCF/pull/4925))
- `wcf\system\session\SessionHandler::getStyleID()` ([WoltLab/WCF#4837](https://github.com/WoltLab/WCF/pull/4837))
- `wcf\system\session\SessionHandler::setStyleID()` ([WoltLab/WCF#4837](https://github.com/WoltLab/WCF/pull/4837))
- `wcf\system\CLIWCF::checkForUpdates()` ([WoltLab/WCF#5058](https://github.com/WoltLab/WCF/pull/5058))
- `wcf\system\WCFACP::checkMasterPassword()` ([WoltLab/WCF#4977](https://github.com/WoltLab/WCF/pull/4977))
- `wcf\system\WCFACP::getFrontendMenu()` ([WoltLab/WCF#4812](https://github.com/WoltLab/WCF/pull/4812))
- `wcf\system\WCFACP::initPackage()` ([WoltLab/WCF#4794](https://github.com/WoltLab/WCF/pull/4794))
- `wcf\util\CryptoUtil::randomBytes()` ([WoltLab/WCF#4924](https://github.com/WoltLab/WCF/pull/4924))
- `wcf\util\CryptoUtil::randomInt()` ([WoltLab/WCF#4924](https://github.com/WoltLab/WCF/pull/4924))
- `wcf\util\CryptoUtil::secureCompare()` ([WoltLab/WCF#4924](https://github.com/WoltLab/WCF/pull/4924))
- `wcf\util\FileUtil::downloadFileFromHttp()` ([WoltLab/WCF#4942](https://github.com/WoltLab/WCF/pull/4942))
- `wcf\util\PasswordUtil::secureCompare()` ([WoltLab/WCF#4924](https://github.com/WoltLab/WCF/pull/4924))
- `wcf\util\PasswordUtil::secureRandomNumber()` ([WoltLab/WCF#4924](https://github.com/WoltLab/WCF/pull/4924))
- `wcf\util\StringUtil::encodeJSON()` ([WoltLab/WCF#5073](https://github.com/WoltLab/WCF/pull/5073))
- `wcf\util\StyleUtil::updateStyleFile()` ([WoltLab/WCF#4977](https://github.com/WoltLab/WCF/pull/4977))
- `wcf\util\UserRegistrationUtil::isSecurePassword()` ([WoltLab/WCF#4977](https://github.com/WoltLab/WCF/pull/4977))

#### Properties

- `wcf\acp\form\PageAddForm::$isLandingPage` ([WoltLab/WCF#4842](https://github.com/WoltLab/WCF/pull/4842))
- `wcf\system\appliation\ApplicationHandler::$isMultiDomain` ([WoltLab/WCF#4785](https://github.com/WoltLab/WCF/pull/4785))
- `wcf\system\package\PackageArchive::$package` ([WoltLab/WCF#5129](https://github.com/WoltLab/WCF/pull/5129))
- `wcf\system\request\RequestHandler::$inRescueMode` ([WoltLab/WCF#4831](https://github.com/WoltLab/WCF/pull/4831))
- `wcf\system\request\RouteHandler::$defaultControllers` ([WoltLab/WCF#4832](https://github.com/WoltLab/WCF/pull/4832))
- `wcf\system\template\TemplateScriptingCompiler::$disabledPHPFunctions` ([WoltLab/WCF#4788](https://github.com/WoltLab/WCF/pull/4788))
- `wcf\system\template\TemplateScriptingCompiler::$enterpriseFunctions` ([WoltLab/WCF#4788](https://github.com/WoltLab/WCF/pull/4788))
- `wcf\system\WCF::$forceLogout` ([WoltLab/WCF#4799](https://github.com/WoltLab/WCF/pull/4799))

#### Events

- `beforeArgumentParsing@wcf\system\CLIWCF` ([WoltLab/WCF#5058](https://github.com/WoltLab/WCF/pull/5058))
- `afterArgumentParsing@wcf\system\CLIWCF` ([WoltLab/WCF#5058](https://github.com/WoltLab/WCF/pull/5058))

#### Constants

- `wcf\system\search\elasticsearch\ElasticsearchHandler::DELETE`
- `wcf\system\search\elasticsearch\ElasticsearchHandler::GET`
- `wcf\system\search\elasticsearch\ElasticsearchHandler::POST`
- `wcf\system\search\elasticsearch\ElasticsearchHandler::PUT`
- `PACKAGE_NAME` ([WoltLab/WCF#5006](https://github.com/WoltLab/WCF/pull/5006))
- `PACKAGE_VERSION` ([WoltLab/WCF#5006](https://github.com/WoltLab/WCF/pull/5006))
- `SECURITY_TOKEN_INPUT_TAG` ([WoltLab/WCF#4934](https://github.com/WoltLab/WCF/pull/4934))
- `SECURITY_TOKEN` ([WoltLab/WCF#4934](https://github.com/WoltLab/WCF/pull/4934))
- `WSC_API_VERSION` ([WoltLab/WCF#4943](https://github.com/WoltLab/WCF/pull/4943))

#### Functions

- The global `escapeString` helper ([WoltLab/WCF#5085](https://github.com/WoltLab/WCF/pull/5085))

#### Options

- `CACHE_SOURCE_MEMCACHED_HOST` ([WoltLab/WCF#4928](https://github.com/WoltLab/WCF/pull/4928))
- `DESKTOP_NOTIFICATION_PACKAGE_ID` ([WoltLab/WCF#4785](https://github.com/WoltLab/WCF/pull/4785))
- `GRAVATAR_DEFAULT_TYPE` ([WoltLab/WCF#4929](https://github.com/WoltLab/WCF/pull/4929))
- `HTTP_SEND_X_FRAME_OPTIONS` ([WoltLab/WCF#4786](https://github.com/WoltLab/WCF/pull/4786))
- `MODULE_GRAVATAR` ([WoltLab/WCF#4929](https://github.com/WoltLab/WCF/pull/4929))

#### Files

- The `scss.inc.php` compatibility include. ([WoltLab/WCF#4932](https://github.com/WoltLab/WCF/pull/4932))
- The `config.inc.php` in app directories. ([WoltLab/WCF#5006](https://github.com/WoltLab/WCF/pull/5006))

### JavaScript

- `Blog.Blog.Archive`
- `Blog.Category.MarkAllAsRead`
- `Blog.Entry.Delete`
- `Blog.Entry.Preview`
- `Blog.Entry.QuoteHandler`
- `Calendar.Category.MarkAllAsRead` ([WoltLab/com.woltlab.calendar#169](https://github.com/WoltLab/com.woltlab.calendar/pull/169))
- `Calendar.Event.Coordinates`
- `Calendar.Event.Date.FullDay` ([WoltLab/com.woltlab.calendar#171](https://github.com/WoltLab/com.woltlab.calendar/pull/171))
- `Calendar.Event.Date.Participation.RemoveParticipant`
- `Calendar.Event.Preview`
- `Calendar.Event.QuoteHandler`
- `Calendar.Event.Share`
- `Calendar.Event.TabMenu`
- `Calendar.Event.Thread.ShowParticipants`
- `Calendar.Map`
- `Calendar.UI.Calendar`
- `Calendar/Ui/Event/Date/Cancel.js`
- `Calendar.Export.iCal`
- `Filebase.Category.MarkAllAsRead`
- `Filebase.File.MarkAsRead`
- `Filebase.File.Preview`
- `Filebase.File.Share`
- `flexibleArea.js` ([WoltLab/WCF#4945](https://github.com/WoltLab/WCF/pull/4945))
- `Gallery.Album.Share`
- `Gallery.Category.MarkAllAsRead`
- `Gallery.Image.Delete`
- `Gallery.Image.Share`
- `Gallery.Map.LargeMap`
- `Gallery.Map.InfoWindowImageListDialog`
- `jQuery.browser.smartphone` ([WoltLab/WCF#4945](https://github.com/WoltLab/WCF/pull/4945))
- `Prism.wscSplitIntoLines` ([WoltLab/WCF#4940](https://github.com/WoltLab/WCF/pull/4940))
- `SID_ARG_2ND` ([WoltLab/WCF#4998](https://github.com/WoltLab/WCF/pull/4998))
- `WBB.Board.Collapsible`
- `WBB.Board.IgnoreBoards`
- `WBB.Post.IPAddressHandler`
- `WBB.Post.Preview`
- `WBB.Post.QuoteHandler`
- `WBB.Thread.LastPageHandler`
- `WBB.Thread.SimilarThreads`
- `WBB.Thread.UpdateHandler.Thread`
- `WBB.Thread.WatchedThreadList`
- `WCF.Action.Scroll` ([WoltLab/WCF#4945](https://github.com/WoltLab/WCF/pull/4945))
- `WCF.Comment` ([WoltLab/WCF@00d46429](https://github.com/WoltLab/WCF/commit/00d464295c42a8e1d775fa1b5010d5eb644840aa))
- `WCF.Conversation.MarkAllAsRead`
- `WCF.Conversation.MarkAsRead`
- `WCF.Conversation.Message.QuoteHandler`
- `WCF.Conversation.Preview`
- `WCF.Conversation.RemoveParticipant`
- `WCF.Date.Picker` ([WoltLab/WCF#4945](https://github.com/WoltLab/WCF/pull/4945))
- `WCF.Date.Util` ([WoltLab/WCF#4945](https://github.com/WoltLab/WCF/pull/4945))
- `WCF.Dropdown.Interactive.Handler` ([WoltLab/WCF#4944](https://github.com/WoltLab/WCF/pull/4944))
- `WCF.Dropdown.Interactive.Instance` ([WoltLab/WCF#4944](https://github.com/WoltLab/WCF/pull/4944))
- `WCF.Message.Share.Page` ([WoltLab/WCF#4945](https://github.com/WoltLab/WCF/pull/4945))
- `WCF.Message.Smilies` ([WoltLab/WCF#4945](https://github.com/WoltLab/WCF/pull/4945))
- `WCF.ModeratedUserGroup.AddMembers`
- `WCF.Moderation.Queue.MarkAllAsRead`
- `WCF.Moderation.Queue.MarkAsRead`
- `WCF.Poll` ([WoltLab/WCF#5465](https://github.com/WoltLab/WCF/pull/5465))
- `WCF.Search.Message.KeywordList` ([WoltLab/WCF#4945](https://github.com/WoltLab/WCF/pull/4945))
- `WCF.System.FlexibleMenu` ([WoltLab/WCF#4945](https://github.com/WoltLab/WCF/pull/4945))
- `WCF.System.Fullscreen` ([WoltLab/WCF#4945](https://github.com/WoltLab/WCF/pull/4945))
- `WCF.System.PageNavigation` ([WoltLab/WCF#4945](https://github.com/WoltLab/WCF/pull/4945))
- `WCF.Template` ([WoltLab/WCF#5070](https://github.com/WoltLab/WCF/pull/5070))
- `WCF.ToggleOptions` ([WoltLab/WCF#4945](https://github.com/WoltLab/WCF/pull/4945))
- `WCF.User.Panel.Abstract` ([WoltLab/WCF#4944](https://github.com/WoltLab/WCF/pull/4944))
- `WCF.User.Registration.Validation.Password` ([WoltLab/WCF#5244](https://github.com/WoltLab/WCF/pull/5244))
- `window.shuffle()` ([WoltLab/WCF#4945](https://github.com/WoltLab/WCF/pull/4945))
- `WSC_API_VERSION` ([WoltLab/WCF#4943](https://github.com/WoltLab/WCF/pull/4943))
- `WCF.Infraction.Warning.ShowDetails`
- `WoltLabSuite/Core/Ui/Comment/Add` ([WoltLab/WCF#5210](https://github.com/WoltLab/WCF/pull/5210))
- `WoltLabSuite/Core/Ui/Comment/Edit` ([WoltLab/WCF#5210](https://github.com/WoltLab/WCF/pull/5210))
- `WoltLabSuite/Core/Ui/Response/Comment/Add` ([WoltLab/WCF#5210](https://github.com/WoltLab/WCF/pull/5210))
- `WoltLabSuite/Core/Ui/Response/Comment/Edit` ([WoltLab/WCF#5210](https://github.com/WoltLab/WCF/pull/5210))

### Database

- `wcf1_cli_history` ([WoltLab/WCF#5058](https://github.com/WoltLab/WCF/pull/5058))
- `wcf1_package_compatibility` ([WoltLab/WCF#4992](https://github.com/WoltLab/WCF/pull/4992))
- `wcf1_package_update_compatibility` ([WoltLab/WCF#5005](https://github.com/WoltLab/WCF/pull/5005))
- `wcf1_package_update_optional` ([WoltLab/WCF#5005](https://github.com/WoltLab/WCF/pull/5005))
- `wcf1_user_notification_to_user` ([WoltLab/WCF#5005](https://github.com/WoltLab/WCF/pull/5005))
- `wcf1_user.enableGravatar` ([WoltLab/WCF#4929](https://github.com/WoltLab/WCF/pull/4929))
- `wcf1_user.gravatarFileExtension` ([WoltLab/WCF#4929](https://github.com/WoltLab/WCF/pull/4929))

### Templates

#### Templates

- `conversationListUserPanel` ([WoltLab/com.woltlab.wcf.conversation#176](https://github.com/WoltLab/com.woltlab.wcf.conversation/pull/176))
- `moderationQueueList` ([WoltLab/WCF#4944](https://github.com/WoltLab/WCF/pull/4944))
- `notificationListUserPanel` ([WoltLab/WCF#4944](https://github.com/WoltLab/WCF/pull/4944))

#### Template Plugins

- `{fetch}` ([WoltLab/WCF#4892](https://github.com/WoltLab/WCF/pull/4892))
- `|encodeJSON` ([WoltLab/WCF#5073](https://github.com/WoltLab/WCF/pull/5073))

#### Template Events

- `headInclude::javascriptInclude` ([WoltLab/WCF#4801](https://github.com/WoltLab/WCF/pull/4801))
- `headInclude::javascriptInit` ([WoltLab/WCF#4801](https://github.com/WoltLab/WCF/pull/4801))
- `headInclude::javascriptLanguageImport` ([WoltLab/WCF#4801](https://github.com/WoltLab/WCF/pull/4801))

#### Template Variables

- `$__sessionKeepAlive` ([WoltLab/WCF#5055](https://github.com/WoltLab/WCF/pull/5055))
- `$__wcfVersion` ([WoltLab/WCF#4927](https://github.com/WoltLab/WCF/pull/4927))
- `$tpl.cookie` ([WoltLab/WCF@7cfd5578ede22e](https://github.com/WoltLab/WCF/commit/7cfd5578ede22e798b770262c0cdf1e9dfe25d36))
- `$tpl.env` ([WoltLab/WCF@7cfd5578ede22e](https://github.com/WoltLab/WCF/commit/7cfd5578ede22e798b770262c0cdf1e9dfe25d36))
- `$tpl.get` ([WoltLab/WCF@7cfd5578ede22e](https://github.com/WoltLab/WCF/commit/7cfd5578ede22e798b770262c0cdf1e9dfe25d36))
- `$tpl.now` ([WoltLab/WCF@7cfd5578ede22e](https://github.com/WoltLab/WCF/commit/7cfd5578ede22e798b770262c0cdf1e9dfe25d36))
- `$tpl.post` ([WoltLab/WCF@7cfd5578ede22e](https://github.com/WoltLab/WCF/commit/7cfd5578ede22e798b770262c0cdf1e9dfe25d36))
- `$tpl.server` ([WoltLab/WCF@7cfd5578ede22e](https://github.com/WoltLab/WCF/commit/7cfd5578ede22e798b770262c0cdf1e9dfe25d36))

### Miscellaneous

- The use of a non-`1` `WCF_N` in WCFSetup. ([WoltLab/WCF#4791](https://github.com/WoltLab/WCF/pull/4791))
- The global option to set a specific style with a request parameter (`$_REQUEST['styleID']`) is removed. ([WoltLab/WCF#4533](https://github.com/WoltLab/WCF/pull/4533))
- Using non-standard ports for package servers is no longer supported. ([WoltLab/WCF#4790](https://github.com/WoltLab/WCF/pull/4790))
- Using the insecure `http://` scheme for package servers is no longer supported. The use of `https://` is enforced. ([WoltLab/WCF#4790](https://github.com/WoltLab/WCF/pull/4790))
- The support for AMP pages was removed.  ([WoltLab/WCF#5425](https://github.com/WoltLab/WCF/pull/5425))
