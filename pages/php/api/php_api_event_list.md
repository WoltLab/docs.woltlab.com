---
title: Event List
sidebar: sidebar
permalink: php_api_event_list.html
folder: php
parent: php_api_events
---

Events whose name is marked with an asterisk are called from a static method and thus do not provide any object, just the class name. 

## WoltLab Suite Core

| Class | Event Name |
|-------|------------|
| `wcf\acp\form\StyleAddForm` | `setVariables` |
| `wcf\acp\form\UserSearchForm` | `search` |
| `wcf\action\AbstractAction` | `checkModules` |
| `wcf\action\AbstractAction` | `checkPermissions` |
| `wcf\action\AbstractAction` | `execute` |
| `wcf\action\AbstractAction` | `executed` |
| `wcf\action\AbstractAction` | `readParameters` |
| `wcf\data\attachment\AttachmentAction` | `generateThumbnail` |
| `wcf\data\session\SessionAction` | `keepAlive` |
| `wcf\data\user\UserAction` | `rename` |
| `wcf\data\AbstractDatabaseObjectAction` | `finalizeAction` |
| `wcf\data\AbstractDatabaseObjectAction` | `initializeAction` |
| `wcf\data\AbstractDatabaseObjectAction` | `validateAction` |
| `wcf\data\DatabaseObjectList` | `init` |
| `wcf\form\AbstractForm` | `readFormParameters` |
| `wcf\form\AbstractForm` | `save` |
| `wcf\form\AbstractForm` | `saved` |
| `wcf\form\AbstractForm` | `submit` |
| `wcf\form\AbstractForm` | `validate` |
| `wcf\form\AbstractModerationForm` | `prepareSave` |
| `wcf\page\AbstractPage` | `assignVariables` |
| `wcf\page\AbstractPage` | `checkModules` |
| `wcf\page\AbstractPage` | `checkPermissions` |
| `wcf\page\AbstractPage` | `readData` |
| `wcf\page\AbstractPage` | `readParameters` |
| `wcf\page\AbstractPage` | `show` |
| `wcf\page\MultipleLinkPage` | `beforeReadObjects` |
| `wcf\page\MultipleLinkPage` | `calculateNumberOfPages` |
| `wcf\page\MultipleLinkPage` | `countItems` |
| `wcf\page\SortablePage` | `validateSortField` |
| `wcf\page\SortablePage` | `validateSortOrder` |
| `wcf\system\bbcode\MessageParser` | `afterParsing` |
| `wcf\system\bbcode\MessageParser` | `beforeParsing` |
| `wcf\system\bbcode\SimpleMessageParser` | `afterParsing` |
| `wcf\system\bbcode\SimpleMessageParser` | `beforeParsing` |
| `wcf\system\box\AbstractBoxController` | `__construct` |
| `wcf\system\box\AbstractBoxController` | `afterLoadContent` |
| `wcf\system\box\AbstractBoxController` | `beforeLoadContent` |
| `wcf\system\box\AbstractDatabaseObjectListBoxController` | `readObjects` |
| `wcf\system\cronjob\AbstractCronjob` | `execute` |
| `wcf\system\email\Email` | `getJobs` |
| `wcf\system\html\input\filter\MessageHtmlInputFilter` | `setAttributeDefinitions` |
| `wcf\system\html\input\node\HtmlInputNodeProcessor` | `afterProcess` |
| `wcf\system\html\input\node\HtmlInputNodeProcessor` | `beforeEmbeddedProcess` |
| `wcf\system\html\input\node\HtmlInputNodeProcessor` | `beforeProcess` |
| `wcf\system\html\input\node\HtmlInputNodeProcessor` | `parseEmbeddedContent` |
| `wcf\system\html\input\node\HtmlInputNodeWoltlabMetacodeMarker` | `filterGroups` |
| `wcf\system\menu\TreeMenu` | `init` |
| `wcf\system\menu\TreeMenu` | `loadCache` |
| `wcf\system\message\QuickReplyManager` | `createMessage` |
| `wcf\system\message\QuickReplyManager` | `createdMessage` |
| `wcf\system\message\QuickReplyManager` | `getMessage` |
| `wcf\system\message\QuickReplyManager` | `validateParameters` |
| `wcf\system\option\OptionHandler` | `afterReadCache` |
| `wcf\system\package\plugin\AbstractPackageInstallationPlugin` | `construct` |
| `wcf\system\package\plugin\AbstractPackageInstallationPlugin` | `hasUninstall` |
| `wcf\system\package\plugin\AbstractPackageInstallationPlugin` | `install` |
| `wcf\system\package\plugin\AbstractPackageInstallationPlugin` | `uninstall` |
| `wcf\system\package\plugin\AbstractPackageInstallationPlugin` | `update` |
| `wcf\system\package\PackageInstallationDispatcher` | `postInstall` |
| `wcf\system\package\PackageUninstallationDispatcher` | `postUninstall` |
| `wcf\system\request\RouteHandler` | `didInit` | 
| `wcf\system\session\ACPSessionFactory` | `afterInit` |
| `wcf\system\session\ACPSessionFactory` | `beforeInit` |
| `wcf\system\session\SessionHandler` | `afterChangeUser` |
| `wcf\system\session\SessionHandler` | `beforeChangeUser` |
| `wcf\system\template\TemplateEngine` | `afterDisplay` |
| `wcf\system\template\TemplateEngine` | `beforeDisplay` |
| `wcf\system\user\authentication\UserAuthenticationFactory` | `init` |
| `wcf\system\user\notification\UserNotificationHandler` | `fireEvent` |
| `wcf\system\worker\AbstractRebuildDataWorker` | `execute` |
| `wcf\system\CLIWCF` | `afterArgumentParsing` |
| `wcf\system\CLIWCF` | `beforeArgumentParsing` |
| `wcf\system\WCF` | `initialized` |
| `wcf\util\HeaderUtil` | `parseOutput`*|

## WoltLab Suite Forum

| Class | Event Name |
|-------|------------|
| `wbb\system\thread\ThreadHandler` | `didInit` |
