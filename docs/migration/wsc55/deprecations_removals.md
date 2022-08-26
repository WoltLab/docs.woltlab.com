# Migrating from WoltLab Suite 5.5 - Deprecations and Removals

With version 6.0, we have deprecated certain components and removed several other components that have been deprecated for many years.



## Deprecations

### PHP

#### Classes

- `wcf\SensitiveArgument` ([WoltLab/WCF#4802](https://github.com/WoltLab/WCF/pull/4802))
- `wcf\util\CronjobUtil` ([WoltLab/WCF#4923](https://github.com/WoltLab/WCF/pull/4923))

#### Methods

- `wcf\data\package\update\server\PackageUpdateServer::attemptSecureConnection()` ([WoltLab/WCF#4790](https://github.com/WoltLab/WCF/pull/4790))
- `wcf\data\package\update\server\PackageUpdateServer::isValidServerURL()` ([WoltLab/WCF#4790](https://github.com/WoltLab/WCF/pull/4790))
- `wcf\data\page\Page::setAsLandingPage()` ([WoltLab/WCF#4842](https://github.com/WoltLab/WCF/pull/4842))
- `wcf\system\io\RemoteFile::disableSSL()` ([WoltLab/WCF#4790](https://github.com/WoltLab/WCF/pull/4790))
- `wcf\system\io\RemoteFile::supportsSSL()` ([WoltLab/WCF#4790](https://github.com/WoltLab/WCF/pull/4790))
- `wcf\system\request\RequestHandler::inRescueMode()` ([WoltLab/WCF#4831](https://github.com/WoltLab/WCF/pull/4831))
- `wcf\system\session\SessionHandler::getLanguageIDs()` ([WoltLab/WCF#4839](https://github.com/WoltLab/WCF/pull/4839))
- `wcf\system\WCF::getActivePath()` ([WoltLab/WCF#4827](https://github.com/WoltLab/WCF/pull/4827))
- `wcf\system\WCF::getFavicon()` ([WoltLab/WCF#4785](https://github.com/WoltLab/WCF/pull/4785))
- `wcf\system\WCF::useDesktopNotifications()` ([WoltLab/WCF#4785](https://github.com/WoltLab/WCF/pull/4785))
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

- `WCF.User.ObjectWatch.Subscribe` ([WoltLab/WCF#4962](https://github.com/WoltLab/WCF/pull/4962))

### Database Tables

### Templates

#### Template Modifiers

#### Template Events

### Miscellaneous

## Removals

### PHP

#### Classes

- `wcf\acp\form\ApplicationEditForm` ([WoltLab/WCF#4785](https://github.com/WoltLab/WCF/pull/4785))
- `wcf\action\GravatarDownloadAction` ([WoltLab/WCF#4929](https://github.com/WoltLab/WCF/pull/4929))
- `wcf\data\user\avatar\Gravatar` ([WoltLab/WCF#4929](https://github.com/WoltLab/WCF/pull/4929))
- `wcf\system\bbcode\highlighter\*Highlighter` ([WoltLab/WCF#4926](https://github.com/WoltLab/WCF/pull/4926))
- `wcf\system\bbcode\highlighter\Highlighter` ([WoltLab/WCF#4926](https://github.com/WoltLab/WCF/pull/4926))
- `wcf\system\cache\source\MemcachedCacheSource` ([WoltLab/WCF#4928](https://github.com/WoltLab/WCF/pull/4928))
- `wcf\system\cli\command\PackageCLICommand` ([WoltLab/WCF#4946](https://github.com/WoltLab/WCF/pull/4946))
- `wcf\system\mail\Mail` ([WoltLab/WCF#4941](https://github.com/WoltLab/WCF/pull/4941))
- `wcf\system\option\DesktopNotificationApplicationSelectOptionType` ([WoltLab/WCF#4785](https://github.com/WoltLab/WCF/pull/4785))
- `wcf\system\search\elasticsearch\ElasticsearchException`

#### Methods

- The `$forceHTTP` parameter of `wcf\data\package\update\server\PackageUpdateServer::getListURL()` ([WoltLab/WCF#4790](https://github.com/WoltLab/WCF/pull/4790))
- The `$forceHTTP` parameter of `wcf\system\package\PackageUpdateDispatcher::getPackageUpdateXML()` ([WoltLab/WCF#4790](https://github.com/WoltLab/WCF/pull/4790))
- `wcf\data\bbcode\BBCodeCache::getHighlighters()` ([WoltLab/WCF#4926](https://github.com/WoltLab/WCF/pull/4926))
- `wcf\data\conversation\ConversationAction::getMixedConversationList()` ([WoltLab/com.woltlab.wcf.conversation#176](https://github.com/WoltLab/com.woltlab.wcf.conversation/pull/176))
- `wcf\data\moderation\queue\ModerationQueueAction::getOutstandingQueues()` ([WoltLab/WCF#4944](https://github.com/WoltLab/WCF/pull/4944))
- `wcf\data\user\notification\UserNotificationAction::getOustandingNotifications()` ([WoltLab/WCF#4944](https://github.com/WoltLab/WCF/pull/4944))
- `wcf\system\request\Request::execute()` ([WoltLab/WCF#4820](https://github.com/WoltLab/WCF/pull/4820))
- `wcf\system\request\Request::getPageType()` ([WoltLab/WCF#4822](https://github.com/WoltLab/WCF/pull/4822))
- `wcf\system\request\Request::getPageType()` ([WoltLab/WCF#4822](https://github.com/WoltLab/WCF/pull/4822))
- `wcf\system\request\Request::isExecuted()`
- `wcf\system\request\Request::setIsLandingPage()`
- `wcf\system\request\RouteHandler::getDefaultController()` ([WoltLab/WCF#4832](https://github.com/WoltLab/WCF/pull/4832))
- `wcf\system\request\RouteHandler::loadDefaultControllers()` ([WoltLab/WCF#4832](https://github.com/WoltLab/WCF/pull/4832))
- `wcf\system\search\AbstractSearchEngine::getFulltextMinimumWordLength()` ([WoltLab/WCF#4933](https://github.com/WoltLab/WCF/pull/4933))
- `wcf\system\search\AbstractSearchEngine::parseSearchQuery()` ([WoltLab/WCF#4933](https://github.com/WoltLab/WCF/pull/4933))
- `wcf\system\search\elasticsearch\ElasticsearchHandler::query()`
- `wcf\system\search\SearchIndexManager::add()` ([WoltLab/WCF#4925](https://github.com/WoltLab/WCF/pull/4925))
- `wcf\system\search\SearchIndexManager::update()` ([WoltLab/WCF#4925](https://github.com/WoltLab/WCF/pull/4925))
- `wcf\system\session\SessionHandler::getStyleID()` ([WoltLab/WCF#4837](https://github.com/WoltLab/WCF/pull/4837))
- `wcf\system\session\SessionHandler::setStyleID()` ([WoltLab/WCF#4837](https://github.com/WoltLab/WCF/pull/4837))
- `wcf\system\WCFACP::getFrontendMenu()` ([WoltLab/WCF#4812](https://github.com/WoltLab/WCF/pull/4812))
- `wcf\system\WCFACP::initPackage()` ([WoltLab/WCF#4794](https://github.com/WoltLab/WCF/pull/4794))
- `wcf\util\CryptoUtil::randomBytes()` ([WoltLab/WCF#4924](https://github.com/WoltLab/WCF/pull/4924))
- `wcf\util\CryptoUtil::randomInt()` ([WoltLab/WCF#4924](https://github.com/WoltLab/WCF/pull/4924))
- `wcf\util\CryptoUtil::secureCompare()` ([WoltLab/WCF#4924](https://github.com/WoltLab/WCF/pull/4924))
- `wcf\util\FileUtil::downloadFileFromHttp()` ([WoltLab/WCF#4942](https://github.com/WoltLab/WCF/pull/4942))
- `wcf\util\PasswordUtil::secureCompare()` ([WoltLab/WCF#4924](https://github.com/WoltLab/WCF/pull/4924))
- `wcf\util\PasswordUtil::secureRandomNumber()` ([WoltLab/WCF#4924](https://github.com/WoltLab/WCF/pull/4924))

#### Properties

- `wcf\acp\form\PageAddForm::$isLandingPage` ([WoltLab/WCF#4842](https://github.com/WoltLab/WCF/pull/4842))
- `wcf\system\appliation\ApplicationHandler::$isMultiDomain` ([WoltLab/WCF#4785](https://github.com/WoltLab/WCF/pull/4785))
- `wcf\system\request\RequestHandler::$inRescueMode` ([WoltLab/WCF#4831](https://github.com/WoltLab/WCF/pull/4831))
- `wcf\system\request\RouteHandler::$defaultControllers` ([WoltLab/WCF#4832](https://github.com/WoltLab/WCF/pull/4832))
- `wcf\system\template\TemplateScriptingCompiler::$disabledPHPFunctions` ([WoltLab/WCF#4788](https://github.com/WoltLab/WCF/pull/4788))
- `wcf\system\template\TemplateScriptingCompiler::$enterpriseFunctions` ([WoltLab/WCF#4788](https://github.com/WoltLab/WCF/pull/4788))
- `wcf\system\WCF::$forceLogout` ([WoltLab/WCF#4799](https://github.com/WoltLab/WCF/pull/4799))

#### Constants

- `wcf\system\search\elasticsearch\ElasticsearchHandler::DELETE`
- `wcf\system\search\elasticsearch\ElasticsearchHandler::GET`
- `wcf\system\search\elasticsearch\ElasticsearchHandler::POST`
- `wcf\system\search\elasticsearch\ElasticsearchHandler::PUT`
- `SECURITY_TOKEN` ([WoltLab/WCF#4934](https://github.com/WoltLab/WCF/pull/4934))
- `SECURITY_TOKEN_INPUT_TAG` ([WoltLab/WCF#4934](https://github.com/WoltLab/WCF/pull/4934))

#### Options

- `CACHE_SOURCE_MEMCACHED_HOST` ([WoltLab/WCF#4928](https://github.com/WoltLab/WCF/pull/4928))
- `DESKTOP_NOTIFICATION_PACKAGE_ID` ([WoltLab/WCF#4785](https://github.com/WoltLab/WCF/pull/4785))
- `HTTP_SEND_X_FRAME_OPTIONS` ([WoltLab/WCF#4786](https://github.com/WoltLab/WCF/pull/4786))
- `MODULE_GRAVATAR` ([WoltLab/WCF#4929](https://github.com/WoltLab/WCF/pull/4929))
- `GRAVATAR_DEFAULT_TYPE` ([WoltLab/WCF#4929](https://github.com/WoltLab/WCF/pull/4929))

#### Files

- The `scss.inc.php` compatibility include. ([WoltLab/WCF#4932](https://github.com/WoltLab/WCF/pull/4932))

### JavaScript

- `flexibleArea.js` ([WoltLab/WCF#4945](https://github.com/WoltLab/WCF/pull/4945))
- `jQuery.browser.smartphone` ([WoltLab/WCF#4945](https://github.com/WoltLab/WCF/pull/4945))
- `Prism.wscSplitIntoLines` ([WoltLab/WCF#4940](https://github.com/WoltLab/WCF/pull/4940))
- `WCF.Action.Scroll` ([WoltLab/WCF#4945](https://github.com/WoltLab/WCF/pull/4945))
- `WCF.Date.Picker` ([WoltLab/WCF#4945](https://github.com/WoltLab/WCF/pull/4945))
- `WCF.Date.Util` ([WoltLab/WCF#4945](https://github.com/WoltLab/WCF/pull/4945))
- `WCF.Dropdown.Interactive.Handler` ([WoltLab/WCF#4944](https://github.com/WoltLab/WCF/pull/4944))
- `WCF.Dropdown.Interactive.Instance` ([WoltLab/WCF#4944](https://github.com/WoltLab/WCF/pull/4944))
- `WCF.Message.Share.Page` ([WoltLab/WCF#4945](https://github.com/WoltLab/WCF/pull/4945))
- `WCF.Message.Smilies` ([WoltLab/WCF#4945](https://github.com/WoltLab/WCF/pull/4945))
- `WCF.Search.Message.KeywordList` ([WoltLab/WCF#4945](https://github.com/WoltLab/WCF/pull/4945))
- `WCF.System.FlexibleMenu` ([WoltLab/WCF#4945](https://github.com/WoltLab/WCF/pull/4945))
- `WCF.System.Fullscreen` ([WoltLab/WCF#4945](https://github.com/WoltLab/WCF/pull/4945))
- `WCF.System.PageNavigation` ([WoltLab/WCF#4945](https://github.com/WoltLab/WCF/pull/4945))
- `WCF.ToggleOptions` ([WoltLab/WCF#4945](https://github.com/WoltLab/WCF/pull/4945))
- `WCF.User.Panel.Abstract` ([WoltLab/WCF#4944](https://github.com/WoltLab/WCF/pull/4944))
- `window.shuffle()` ([WoltLab/WCF#4945](https://github.com/WoltLab/WCF/pull/4945))
- `WCF.Moderation.Queue.MarkAsRead`
- `WCF.Moderation.Queue.MarkAllAsRead`
- `Calendar/Ui/Event/Date/Cancel.js`
- `Calendar.Category.MarkAllAsRead` ([WoltLab/com.woltlab.calendar#169](https://github.com/WoltLab/com.woltlab.calendar/pull/169))
- `Calendar.Event.Preview`
- `Calendar.Event.Share`
- `Calendar.Event.Date.Participation.RemoveParticipant`
- `Calendar.Event.QuoteHandler`
- `Calendar.UI.Calendar`
- `Calendar.Event.Date.FullDay` ([WoltLab/com.woltlab.calendar#171](https://github.com/WoltLab/com.woltlab.calendar/pull/171))
- `Calendar.Event.TabMenu`
- `Blog.Entry.Delete`
- `Blog.Entry.Preview`
- `Blog.Entry.QuoteHandler`
- `Blog.Category.MarkAllAsRead`
- `Blog.Blog.Archive`
- `Filebase.File.Share`
- `Filebase.File.Preview`
- `Filebase.Category.MarkAllAsRead`
- `Filebase.File.MarkAsRead`
- `Gallery.Image.Delete`
- `WBB.Board.IgnoreBoards`
- `WBB.Post.QuoteHandler`
- `WBB.Post.IPAddressHandler`
- `WBB.Post.Preview`
- `WBB.Thread.WatchedThreadList`
- `WBB.Thread.SimilarThreads`
- `WBB.Thread.LastPageHandler`
- `WBB.Thread.UpdateHandler.Thread`
- `WCF.Conversation.RemoveParticipant`
- `WCF.Conversation.Preview`
- `WCF.Conversation.Message.QuoteHandler`
- `WCF.Conversation.MarkAsRead`
- `WCF.Conversation.MarkAllAsRead`
- `WCF.ModeratedUserGroup.AddMembers`

### Database

- `wcf1_user.enableGravatar` ([WoltLab/WCF#4929](https://github.com/WoltLab/WCF/pull/4929))
- `wcf1_user.gravatarFileExtension` ([WoltLab/WCF#4929](https://github.com/WoltLab/WCF/pull/4929))

### Templates

#### Templates

- `conversationListUserPanel` ([WoltLab/com.woltlab.wcf.conversation#176](https://github.com/WoltLab/com.woltlab.wcf.conversation/pull/176))
- `moderationQueueList` ([WoltLab/WCF#4944](https://github.com/WoltLab/WCF/pull/4944))
- `notificationListUserPanel` ([WoltLab/WCF#4944](https://github.com/WoltLab/WCF/pull/4944))

#### Template Plugins

- `{fetch}` ([WoltLab/WCF#4892](https://github.com/WoltLab/WCF/pull/4892))

#### Template Events

- `headInclude::javascriptLanguageImport` ([WoltLab/WCF#4801](https://github.com/WoltLab/WCF/pull/4801))
- `headInclude::javascriptInclude` ([WoltLab/WCF#4801](https://github.com/WoltLab/WCF/pull/4801))
- `headInclude::javascriptInit` ([WoltLab/WCF#4801](https://github.com/WoltLab/WCF/pull/4801))

#### Template Variables

- `$__wcfVersion` ([WoltLab/WCF#4927](https://github.com/WoltLab/WCF/pull/4927))

### Miscellaneous

- The use of a non-`1` `WCF_N` in WCFSetup. ([WoltLab/WCF#4791](https://github.com/WoltLab/WCF/pull/4791))
- The global option to set a specific style with a request parameter (`$_REQUEST['styleID']`) is removed. ([WoltLab/WCF#4533](https://github.com/WoltLab/WCF/pull/4533))
- Using non-standard ports for package servers is no longer supported. ([WoltLab/WCF#4790](https://github.com/WoltLab/WCF/pull/4790))
- Using the insecure `http://` scheme for package servers is no longer supported. The use of `https://` is enforced. ([WoltLab/WCF#4790](https://github.com/WoltLab/WCF/pull/4790))
