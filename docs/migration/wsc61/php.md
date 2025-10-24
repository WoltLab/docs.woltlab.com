# Migrating from WoltLab Suite 6.1 - PHP

## User Activity Events

User activity events can optionally be provided with an image.
The `setImage()` method expects an object of type `wcf\system\file\processor\ImageData` as a parameter.

Classes that implement the interface `wcf\system\file\processor\IImageDataProvider` provide the `getImageData()` method,
that returns a suitable `ImageData` object.

### Example

```php
$object = new FooBarObject(1);
$event->setTitle(WCF::getLanguage()->getDynamicVariable('com.foo.bar', [
    // variables
]));
$event->setDescription(
    StringUtil::encodeHTML(
        StringUtil::truncate($object->getPlainTextMessage(), 500)
    ),
    true
);
$event->setLink($object->getLink());
$event->setImage(new ImageData('image_src', 800, 600));
```

## User Profile Header

The header in the user profile has been completely overhauled.
Main goal of this change is the overhaul of the visuals of the user profile header.
At the same time, the use of a view removes a lot of business logic from the templates and simplifies extensibility through a clear API.

### Stats

You can add your own counters to the user profile header via a dedicated event. The use of template listeners is no longer necessary.

Example:

```php
EventHandler::getInstance()->register(
    \wcf\event\user\profile\UserProfileStatItemCollecting::class,
    static function (\wcf\event\user\profile\UserProfileStatItemCollecting $event) {
        if ($event->user->exampleCounter) {
            $event->register(\wcf\system\view\user\profile\UserProfileStatItem::forLink(
                WCF::getLanguage()->get('phrase.exampleCounter'),
                StringUtil::formatNumeric($event->user->exampleCounter),
                LinkHandler::getInstance()->getControllerLink(
                    \example\page\ExampleListPage::class,
                    ['object' => $event->user]
                )
            ));
        }
    }
);
```

### Search Content Links

Additional links for searching for content can also be added via a dedicated event. 

Example:

```php
EventHandler::getInstance()->register(
    \wcf\event\user\profile\UserProfileHeaderSearchContentLinkCollecting::class,
    static function (\wcf\event\user\profile\UserProfileHeaderSearchContentLinkCollecting $event) {
        if ($event->user->exampleCounter) {
            $event->register(new \wcf\system\view\user\profile\UserProfileHeaderViewSearchContentLink(
                WCF::getLanguage()->get('phrase.exampleCounter'),
                LinkHandler::getInstance()->getControllerLink(
                    \example\page\ExampleListPage::class,
                    ['object' => $event->user]
                )
            ));
        }
    }
);
```

### Interactions

Two different interaction menus are available in the user profile header.
Corresponding events are available for both menus to add custom menu items.
An example of this can be found in the [interaction documentation](../../php/api/interactions.md#events).

| Type                        | Class Name                   | Event Class Name                      |
|-----------------------------|------------------------------|---------------------------------------|
| General interactions.       | `UserProfileInteractions`    | `UserProfileInteractionCollecting`    |
| Administrative interactions | `UserManagementInteractions` | `UserManagementInteractionCollecting` |


## Deleted Content Provider

A new interface [`IDeletedContentListViewProvider`](https://github.com/WoltLab/WCF/blob/6.2/wcfsetup/install/files/lib/system/moderation/IDeletedContentListViewProvider.class.php) for displaying deleted content in the moderator panel based on a [list view](../../php/api/list_views.md) has been added.

An abstract implementation of the interface is also available: [`AbstractDeletedContentListViewProvider`](https://github.com/WoltLab/WCF/blob/6.2/wcfsetup/install/files/lib/system/moderation/AbstractDeletedContentListViewProvider.class.php)

Example:

```php
class DeletedExampleProvider extends AbstractDeletedContentListViewProvider
{
    #[\Override]
    public function getListView(): AbstractListView
    {
        return new DeletedExampleListView();
    }
}
```

## Taggable Content

A new interface [`ITaggedListViewProvider`](https://github.com/WoltLab/WCF/blob/6.2/wcfsetup/install/files/lib/system/tagging/ITaggedListViewProvider.class.php) for displaying tagged content based on a [list view](../../php/api/list_views.md) has been added.

An abstract implementation of the interface is also available: [`AbstractTaggedListViewProvider`](https://github.com/WoltLab/WCF/blob/6.2/wcfsetup/install/files/lib/system/tagging/AbstractTaggedListViewProvider.class.php)

Example:

```php
class TaggableExample extends AbstractTaggedListViewProvider
{
    #[\Override]
    public function getListView(array $tagIDs): AbstractListView
    {
        return new TaggedExampleListView($tagIDs);
    }
}
```

## Grid Views

The following pages have been migrated to grid views.
Plugins that have been hooked into these pages via event or template listeners must be adapted accordingly.
An example how to add additional columns to an existing grid view can be found in the [grid view documentation](../../php/api/grid_views.md#events).

- `calendar\acp\page\EventImportListPage`
- `filebase\acp\page\LicenseListPage`
- `wbb\acp\page\RssFeedListPage`
- `wcf\acp\page\ACPSessionLogListPage`
- `wcf\acp\page\ACPSessionLogPage`
- `wcf\acp\page\AdListPage`
- `wcf\acp\page\ArticleListPage`
- `wcf\acp\page\AttachmentListPage`
- `wcf\acp\page\BBCodeListPage`
- `wcf\acp\page\BBCodeMediaProviderListPage`
- `wcf\acp\page\BoxListPage`
- `wcf\acp\page\CaptchaQuestionListPage`
- `wcf\acp\page\CronjobListPage`
- `wcf\acp\page\CronjobLogListPage`
- `wcf\acp\page\EmailLogListPage`
- `wcf\acp\page\ExceptionLogViewPage`
- `wcf\acp\page\LabelGroupListPage`
- `wcf\acp\page\LabelListPage`
- `wcf\acp\page\LanguageItemListPage`
- `wcf\acp\page\LanguageListPage`
- `wcf\acp\page\MenuListPage`
- `wcf\acp\page\ModificationLogListPage`
- `wcf\acp\page\NoticeListPage`
- `wcf\acp\page\PackageListPage`
- `wcf\acp\page\PackageUpdateServerListPage`
- `wcf\acp\page\PageListPage`
- `wcf\acp\page\PaidSubscriptionListPage`
- `wcf\acp\page\PaidSubscriptionTransactionLogListPage`
- `wcf\acp\page\PaidSubscriptionUserListPage`
- `wcf\acp\page\ReactionTypeListPage`
- `wcf\acp\page\SmileyListPage`
- `wcf\acp\page\StyleListPage`
- `wcf\acp\page\TagListPage`
- `wcf\acp\page\TemplateGroupListPage`
- `wcf\acp\page\TemplateListPage`
- `wcf\acp\page\TrophyListPage`
- `wcf\acp\page\UserAuthenticationFailureListPage`
- `wcf\acp\page\UserGroupAssignmentListPage`
- `wcf\acp\page\UserGroupListPage`
- `wcf\acp\page\UserOptionListPage`
- `wcf\acp\page\UserRankListPage`
- `wcf\acp\page\UserTrophyListPage`
- `wcf\acp\page\SuspensionListPage`
- `wcf\acp\page\UserSuspensionListPage`
- `wcf\acp\page\UserWarningListPage`
- `wcf\acp\page\WarningListPage`
- `wcf\page\ConversationLabelListPage`
- `wcf\page\ModerationListPage`

## List Views

The following pages have been migrated to list views.
Plugins that have been hooked into these pages via event or template listeners must be adapted accordingly.
An example how to add additional sort fields can be found in the [list view documentation](../../php/api/list_views.md#events).

- `blog\page\BlogEntryListPage`
- `blog\page\BlogListPage`
- `blog\page\CategoryEntryListPage`
- `blog\page\EntryListPage`
- `blog\page\MyBlogListPage`
- `blog\page\MyEntryListPage`
- `blog\page\UnreadEntryListPage`
- `blog\page\UserEntryListPage`
- `blog\page\WatchedEntryListPage`
- `wcf\page\ArticleListPage`
- `wcf\page\CategoryArticleListPage`
- `wcf\page\ConversationListPage`
- `wcf\page\MembersListPage`
- `wcf\page\UnreadArticleListPage`
- `wcf\page\WatchedArticleListPage`

## Forms

The following forms have been migrated to FormBuilder forms.
Plugins that have been hooked into these forms via event or template listeners must be adapted accordingly.

- `blog\acp\form\CategoryAddForm`
- `blog\acp\form\CategoryEditForm`
- `blog\form\BlogAddForm`
- `blog\form\BlogEditForm`
- `blog\form\EntryAddForm`
- `blog\form\EntryEditForm`
- `calendar\acp\form\CategoryAddForm`
- `calendar\acp\form\CategoryEditForm`
- `calendar\acp\form\EventImportAddForm`
- `calendar\acp\form\EventImportEditForm`
- `calendar\acp\form\EventOptionAddForm`
- `calendar\acp\form\EventOptionEditForm`
- `calendar\form\EventAddForm`
- `calendar\form\EventEditForm`
- `filebase\acp\form\CategoryAddForm`
- `filebase\acp\form\CategoryEditForm`
- `filebase\acp\form\FileOptionAddForm`
- `filebase\acp\form\FileOptionEditForm`
- `filebase\acp\form\LicenseAddForm`
- `filebase\acp\form\LicenseEditForm`
- `filebase\form\FileAddForm`
- `filebase\form\FileEditForm`
- `gallery\acp\form\CategoryAddForm`
- `gallery\acp\form\CategoryEditForm`
- `wbb\acp\form\RssFeedAddForm`
- `wbb\acp\form\RssFeedEditForm`
- `wcf\acp\form\BBCodeMediaProviderAddForm`
- `wcf\acp\form\BBCodeMediaProviderEditForm`
- `wcf\acp\form\CaptchaQuestionAddForm`
- `wcf\acp\form\CaptchaQuestionEditForm`
- `wcf\acp\form\ContactOptionAddForm`
- `wcf\acp\form\ContactOptionEditForm`
- `wcf\acp\form\ContactRecipientAddForm`
- `wcf\acp\form\ContactRecipientEditForm`
- `wcf\acp\form\CronjobAddForm`
- `wcf\acp\form\CronjobEditForm`
- `wcf\acp\form\LabelAddForm`
- `wcf\acp\form\LabelEditForm`
- `wcf\acp\form\LoginForm`
- `wcf\acp\form\MenuAddForm`
- `wcf\acp\form\MenuEditForm`
- `wcf\acp\form\MenuItemAddForm`
- `wcf\acp\form\MenuItemEditForm`
- `wcf\acp\form\PackageUpdateServerAddForm`
- `wcf\acp\form\PackageUpdateServerEditForm`
- `wcf\acp\form\SitemapEditForm`
- `wcf\acp\form\SuspensionAddForm`
- `wcf\acp\form\SuspensionEditForm`
- `wcf\acp\form\TagAddForm`
- `wcf\acp\form\TagEditForm`
- `wcf\acp\form\TemplateGroupAddForm`
- `wcf\acp\form\TemplateGroupEditForm`
- `wcf\acp\form\UserOptionAddForm`
- `wcf\acp\form\UserOptionEditForm`
- `wcf\acp\form\UserRankAddForm`
- `wcf\acp\form\UserRankEditForm`
- `wcf\acp\form\WarningAddForm`
- `wcf\acp\form\WarningEditForm`
- `wcf\form\ContactForm`
- `wcf\form\ConversationAddForm`
- `wcf\form\ConversationDraftEditForm`
- `wcf\form\LoginForm`
