# Event List

Events whose name is marked with an asterisk are called from a static method and thus do not provide any object, just the class name. 

## WoltLab Suite Core

| Class | Event Name |
|-------|------------|
| `wcf\acp\action\UserExportGdprAction` | `export` |
| `wcf\acp\form\StyleAddForm` | `setVariables` |
| `wcf\acp\form\UserSearchForm` | `search` |
| `wcf\action\AbstractAction` | `checkModules` |
| `wcf\action\AbstractAction` | `checkPermissions` |
| `wcf\action\AbstractAction` | `execute` |
| `wcf\action\AbstractAction` | `executed` |
| `wcf\action\AbstractAction` | `readParameters` |
| `wcf\data\attachment\AttachmentAction` | `generateThumbnail` |
| `wcf\data\session\SessionAction` | `keepAlive` |
| `wcf\data\session\SessionAction` | `poll` |
| `wcf\data\trophy\Trophy` | `renderTrophy` |
| `wcf\data\user\online\UserOnline` | `getBrowser` |
| `wcf\data\user\online\UserOnlineList` | `isVisible` |
| `wcf\data\user\online\UserOnlineList` | `isVisibleUser` |
| `wcf\data\user\trophy\UserTrophy` | `getReplacements` |
| `wcf\data\user\UserAction` | `beforeFindUsers` |
| `wcf\data\user\UserAction` | `rename` |
| `wcf\data\user\UserProfile` | `getAvatar` |
| `wcf\data\user\UserProfile` | `isAccessible` |
| `wcf\data\AbstractDatabaseObjectAction` | `finalizeAction` |
| `wcf\data\AbstractDatabaseObjectAction` | `initializeAction` |
| `wcf\data\AbstractDatabaseObjectAction` | `validateAction` |
| `wcf\data\DatabaseObjectList` | `init` |
| `wcf\form\AbstractForm` | `readFormParameters` |
| `wcf\form\AbstractForm` | `save` |
| `wcf\form\AbstractForm` | `saved` |
| `wcf\form\AbstractForm` | `submit` |
| `wcf\form\AbstractForm` | `validate` |
| `wcf\form\AbstractFormBuilderForm` | `createForm` |
| `wcf\form\AbstractFormBuilderForm` | `buildForm` |
| `wcf\form\AbstractModerationForm` | `prepareSave` |
| `wcf\page\AbstractPage` | `assignVariables` |
| `wcf\page\AbstractPage` | `checkModules` |
| `wcf\page\AbstractPage` | `checkPermissions` |
| `wcf\page\AbstractPage` | `readData` |
| `wcf\page\AbstractPage` | `readParameters` |
| `wcf\page\AbstractPage` | `show` |
| `wcf\page\MultipleLinkPage` | `beforeReadObjects` |
| `wcf\page\MultipleLinkPage` | `insteadOfReadObjects` |
| `wcf\page\MultipleLinkPage` | `afterInitObjectList` |
| `wcf\page\MultipleLinkPage` | `calculateNumberOfPages` |
| `wcf\page\MultipleLinkPage` | `countItems` |
| `wcf\page\SortablePage` | `validateSortField` |
| `wcf\page\SortablePage` | `validateSortOrder` |
| `wcf\system\bbcode\MessageParser` | `afterParsing` |
| `wcf\system\bbcode\MessageParser` | `beforeParsing` |
| `wcf\system\bbcode\SimpleMessageParser` | `afterParsing` |
| `wcf\system\bbcode\SimpleMessageParser` | `beforeParsing` |
| `wcf\system\box\BoxHandler` | `loadBoxes` |
| `wcf\system\box\AbstractBoxController` | `__construct` |
| `wcf\system\box\AbstractBoxController` | `afterLoadContent` |
| `wcf\system\box\AbstractBoxController` | `beforeLoadContent` |
| `wcf\system\box\AbstractDatabaseObjectListBoxController` | `afterLoadContent` |
| `wcf\system\box\AbstractDatabaseObjectListBoxController` | `beforeLoadContent` |
| `wcf\system\box\AbstractDatabaseObjectListBoxController` | `hasContent` |
| `wcf\system\box\AbstractDatabaseObjectListBoxController` | `readObjects` |
| `wcf\system\cronjob\AbstractCronjob` | `execute` |
| `wcf\system\email\Email` | `getJobs` |
| `wcf\system\form\builder\container\wysiwyg\WysiwygFormContainer` | `populate` |
| `wcf\system\html\input\filter\MessageHtmlInputFilter` | `setAttributeDefinitions` |
| `wcf\system\html\input\node\HtmlInputNodeProcessor` | `afterProcess` |
| `wcf\system\html\input\node\HtmlInputNodeProcessor` | `beforeEmbeddedProcess` |
| `wcf\system\html\input\node\HtmlInputNodeProcessor` | `beforeProcess` |
| `wcf\system\html\input\node\HtmlInputNodeProcessor` | `convertPlainLinks` |
| `wcf\system\html\input\node\HtmlInputNodeProcessor` | `getTextContent` |
| `wcf\system\html\input\node\HtmlInputNodeProcessor` | `parseEmbeddedContent` |
| `wcf\system\html\input\node\HtmlInputNodeWoltlabMetacodeMarker` | `filterGroups` |
| `wcf\system\html\output\node\HtmlOutputNodePre` | `selectHighlighter` |
| `wcf\system\html\output\node\HtmlOutputNodeProcessor` | `beforeProcess` |
| `wcf\system\image\adapter\ImagickImageAdapter` | `getResizeFilter` |
| `wcf\system\menu\user\profile\UserProfileMenu` | `init` |
| `wcf\system\menu\user\profile\UserProfileMenu` | `loadCache` |
| `wcf\system\menu\TreeMenu` | `init` |
| `wcf\system\menu\TreeMenu` | `loadCache` |
| `wcf\system\message\QuickReplyManager` | `addFullQuote` |
| `wcf\system\message\QuickReplyManager` | `allowedDataParameters` |
| `wcf\system\message\QuickReplyManager` | `beforeRenderQuote` |
| `wcf\system\message\QuickReplyManager` | `createMessage` |
| `wcf\system\message\QuickReplyManager` | `createdMessage` |
| `wcf\system\message\QuickReplyManager` | `getMessage` |
| `wcf\system\message\QuickReplyManager` | `validateParameters` |
| `wcf\system\message\quote\MessageQuoteManager` | `addFullQuote` |
| `wcf\system\message\quote\MessageQuoteManager` | `beforeRenderQuote` |
| `wcf\system\option\OptionHandler` | `afterReadCache` |
| `wcf\system\package\plugin\AbstractPackageInstallationPlugin` | `construct` |
| `wcf\system\package\plugin\AbstractPackageInstallationPlugin` | `hasUninstall` |
| `wcf\system\package\plugin\AbstractPackageInstallationPlugin` | `install` |
| `wcf\system\package\plugin\AbstractPackageInstallationPlugin` | `uninstall` |
| `wcf\system\package\plugin\AbstractPackageInstallationPlugin` | `update` |
| `wcf\system\package\plugin\ObjectTypePackageInstallationPlugin` | `addConditionFields` |
| `wcf\system\package\PackageInstallationDispatcher` | `postInstall` |
| `wcf\system\package\PackageUninstallationDispatcher` | `postUninstall` |
| `wcf\system\reaction\ReactionHandler` | `getDataAttributes` | 
| `wcf\system\request\RouteHandler` | `didInit` | 
| `wcf\system\session\ACPSessionFactory` | `afterInit` |
| `wcf\system\session\ACPSessionFactory` | `beforeInit` |
| `wcf\system\session\SessionHandler` | `afterChangeUser` |
| `wcf\system\session\SessionHandler` | `beforeChangeUser` |
| `wcf\system\style\StyleCompiler` | `compile` |
| `wcf\system\template\TemplateEngine` | `afterDisplay` |
| `wcf\system\template\TemplateEngine` | `beforeDisplay` |
| `wcf\system\upload\DefaultUploadFileSaveStrategy` | `generateThumbnails` |
| `wcf\system\upload\DefaultUploadFileSaveStrategy` | `save` |
| `wcf\system\user\authentication\UserAuthenticationFactory` | `init` |
| `wcf\system\user\notification\UserNotificationHandler` | `createdNotification` |
| `wcf\system\user\notification\UserNotificationHandler` | `fireEvent` |
| `wcf\system\user\notification\UserNotificationHandler` | `markAsConfirmed` |
| `wcf\system\user\notification\UserNotificationHandler` | `markAsConfirmedByIDs` |
| `wcf\system\user\notification\UserNotificationHandler` | `removeNotifications` |
| `wcf\system\user\notification\UserNotificationHandler` | `updateTriggerCount` |
| `wcf\system\user\UserBirthdayCache` | `loadMonth` |
| `wcf\system\worker\AbstractRebuildDataWorker` | `execute` |
| `wcf\system\CLIWCF` | `afterArgumentParsing` |
| `wcf\system\CLIWCF` | `beforeArgumentParsing` |
| `wcf\system\WCF` | `initialized` |
| `wcf\util\HeaderUtil` | `parseOutput`*|

## WoltLab Suite Core: Conversations

| Class | Event Name |
|-------|------------|
| `wcf\data\conversation\ConversationAction` | `addParticipants_validateParticipants` |
| `wcf\data\conversation\message\ConversationMessageAction` | `afterQuickReply` |

## WoltLab Suite Forum

| Class | Event Name |
|-------|------------|
| `wbb\data\board\BoardAction` | `cloneBoard` |
| `wbb\data\post\PostAction` | `quickReplyShouldMerge` |
| `wbb\system\thread\ThreadHandler` | `didInit` |
