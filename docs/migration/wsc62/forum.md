# Migrating from WoltLab Suite 6.2 - Forum

WoltLab Suite Forum 6.3 renders the posts of a thread, of a user, of the deleted content and of the moderation queue through [list views](../../php/api/list_views.md).
The legacy thread page with its clipboard, its inline editor and its jQuery based dialogs has been removed, together with `js/WBB.js`.

Posts are changed through commands and their [interactions](../../php/api/interactions.md) instead of `wbb\data\post\PostAction`, see [Database Object Builder](../../php/database-object-builder.md).
Editing a post and the quick reply are [form builder](../../php/api/form_builder/overview.md) forms now.

## Post List Views

| Page | Controller | List view |
|---|---|---|
| Thread | `wbb\page\ThreadPage` | `wbb\system\listView\user\ThreadPostListView` |
| Posts of a user | `wbb\page\UserPostListPage` | `wbb\system\listView\user\UserPostListView` |
| Deleted posts in the moderation | `wcf\page\DeletedContentListViewPage` | `wbb\system\listView\user\DeletedPostListView` |
| Reported and disabled posts in the moderation queue | `wcf\form\ModerationReportForm`, `wcf\form\ModerationActivationForm` | `wbb\system\listView\user\ModerationPostListView` |

All of them extend `wbb\system\listView\user\AbstractPostListView` and render their items with the template `postListItems`.
The list views fire the events `ThreadPostListViewInitialized`, `UserPostListViewInitialized`, `DeletedPostListViewInitialized` and `ModerationPostListViewInitialized` in `wbb\event\listView\user`.

`AbstractPostListView` offers methods that the list views override to change how the posts are shown, for example `isCollapsedDeletedPost()`, `isCollapsedIgnoredPost()`, `showDeleteNote()`, `showThreadTitle()`, `showAds()`, `canReplyToPosts()` and `createInteractionProvider()`.
The bulk interactions are available if the user has one of the moderator permissions in `wbb\data\board\Board::POST_BULK_INTERACTION_PERMISSIONS` (`Board::canUsePostBulkInteractions()`).
The number of posts per page is the user's `postsPerPage`, or the setting of the board for the posts of a thread.

`ThreadPostListView` marks the thread as read when it renders its items, which covers every request that loads posts of the thread.
It limits guests to the first `WBB_THREAD_GUEST_MAX_POSTS` posts of a thread, on the page of the thread as well as when new posts are loaded.

### Thread Page

`wbb\page\ThreadPage` extends `wcf\page\AbstractListViewPage` and creates the `ThreadPostListView` in `createListView()`.
Its public properties are `$threadID`, `$thread`, `$board`, `$post`, `$quickReplyForm`, `$tags` and `$similarThreads`.
The properties `$likeData`, `$similarThreadList`, `$modificationLogList`, `$bestAnswerOnCurrentPage`, `$officialPostMapping`, `$itemsPerPage`, `$sortOrder`, `$postID` and `$numberOfHiddenPosts` have been removed, as well as the template variables `defaultSmilies`, `hasMarkedItems`, `likeData`, `similarThreadList`, `modificationLogList`, `threadFormOptions`, `bestAnswerOnCurrentPage`, `officialPostMapping`, `attachmentHandler` and `tmpHash`.
The list view provides the data of the posts and the modification log entries between them, `ThreadPostListView::getNumberOfHiddenPosts()` and `ThreadPostListView::getFirstOfficialPostLink()` replace `$numberOfHiddenPosts` and `$officialPostMapping`.

`wbb\page\UserPostListPage` extends `AbstractListViewPage` as well and creates a `UserPostListView` for the user in `$user`.

### Deleted Posts and the Moderation Queue

`wbb\system\moderation\DeletedPostProvider` extends `wcf\system\moderation\AbstractDeletedContentListViewProvider` and returns a `DeletedPostListView` from `getListView()`.
It is registered through the event `wcf\event\moderation\DeletedContentProvidersCollecting` instead of the object type `com.woltlab.wbb.post` of the definition `com.woltlab.wcf.deletedContent`.

The moderation queue renders the post of a report or of an activation through `AbstractPostModerationQueueHandler::renderPost()`, which uses a `ModerationPostListView` instead of the template `moderationPost`.

## Loading the Data of Posts

`wbb\data\post\Post` extends `wcf\data\CollectionDatabaseObject` and loads its data on demand for all posts of a list at once, through `wbb\data\post\PostCollection`:

- `Post::getThread()` returns the thread of the post.
- `Post::getAttachments()` returns the attachments that are not embedded in the message.
  It no longer returns a `GroupedAttachmentList` and no longer takes the parameter `$ignoreCache`.
- `Post::loadEmbeddedObjects()` loads the embedded objects of the posts before their message is rendered.
- `Post::getLogEntry()` returns the modification log entry of the deletion of a post in the trash.
- `Post::getPoll()` returns the legacy poll of a post, it is deprecated together with legacy polls.
- `Post::getUserProfile()` and the reactions of the post are loaded the same way.

`wbb\data\post\ViewablePostList` no longer decorates its posts with `ViewablePost`, it returns `Post` objects.
`ViewablePostList`, `SimplifiedViewablePostList` and `ViewablePost` are deprecated, use `wbb\data\post\PostList` and `Post` instead.
The methods that enabled the loading of attachments, embedded objects, polls, threads and modification logs no longer have any effect, see [Deprecations and Removals](deprecations_removals.md#deprecations).

`wbb\data\post\ThreadPostList` and `wbb\data\post\DeletedPostList` extend `PostList` instead of `ViewablePostList`.
The constructor of `ThreadPostList` is unchanged, its posts are sorted by `time` and `postID`.
`wbb\data\post\SearchResultPostList` extends `PostList` and `wbb\data\post\SearchResultPost` decorates `Post`.
`wbb\data\thread\Thread::getBestAnswerPost()` returns a `Post`.

## Post Commands and Events

The Forum no longer runs `wbb\data\post\PostAction` when posts are changed through the interactions of a post list view or by the thread commands, event listeners on its actions are therefore not called for them.
Listen to the events fired by the commands instead:

| Operation | Command | Event |
|---|---|---|
| Publishing posts | `wbb\command\post\PublishPosts` | `wbb\event\post\PostsPublished` |
| Enabling posts | `wbb\command\post\EnablePosts` | `wbb\event\post\PostsEnabled` |
| Disabling posts | `wbb\command\post\DisablePosts` | `wbb\event\post\PostsDisabled` |
| Moving posts to the trash | `wbb\command\post\SoftDeletePosts` | `wbb\event\post\PostsSoftDeleted` |
| Restoring posts | `wbb\command\post\RestorePosts` | `wbb\event\post\PostsRestored` |
| Deleting posts | `wbb\command\post\DeletePosts` | `wbb\event\post\PostsDeleted` |
| Closing a post | `wbb\command\post\ClosePost` | `wbb\event\post\PostClosed` |
| Opening a post | `wbb\command\post\OpenPost` | `wbb\event\post\PostOpened` |
| Merging posts | `wbb\command\post\MergePosts` | `wbb\event\post\PostsMerged` |
| Moving posts to a thread | `wbb\command\post\MovePosts` | `wbb\event\post\PostsMoved` |
| Moving posts to a new thread | `wbb\command\post\MovePostsToNewThread` | `wbb\event\post\PostsMoved` |
| Copying posts to a thread | `wbb\command\post\CopyPosts` | `wbb\event\post\PostsCopied` |
| Marking a post as the best answer | `wbb\command\post\MarkPostAsBestAnswer` | `wbb\event\post\BestAnswerMarked` |
| Unmarking the best answer | `wbb\command\post\UnmarkPostAsBestAnswer` | `wbb\event\post\BestAnswerUnmarked` |

The commands that change the state of posts process several posts at once and skip posts that already have the requested state, their events only contain the posts that have been changed.
They update the threads of the posts, for example a thread is moved to the trash once all of its posts are in the trash, unless `$updateThreads` is `false`, and they write the modification log unless `$createModificationLogs` is `false`.
`EnablePosts` sets the time of the posts to the current time if `$updateTime` is `true`.

`PostsMoved` contains the posts with the id of their previous thread, `PostsMerged` contains the post that the other posts have been merged into and the merged posts, which no longer exist.
`CopyPosts` neither creates the target thread nor rebuilds it: `wbb\command\post\CreateThreadForPostCopy` creates an empty thread for the copies, and the thread has to be rebuilt once all posts have been copied.
The copies inherit the deleted and disabled state of the target thread.

The thread commands `DeleteThread`, `DisableThread`, `EnableThread`, `RestoreThread` and `SoftDeleteThread` use these commands for the posts of the thread and accept `$includePosts = false` to only change the thread.
`EnableThread` also accepts `$updateTime` to set the time of the thread and its posts to the current time.

The deprecated methods `enable()`, `disable()`, `trash()`, `restore()`, `delete()`, `deleteCompletely()`, `close()` and `open()` of `PostAction` remain available, use the commands instead.

## RPC Endpoints

| Endpoint | Description |
|---|---|
| `POST /forum/posts/{id}/close` | Closes the post through `ClosePost`. |
| `POST /forum/posts/{id}/open` | Opens the post through `OpenPost`. |
| `POST /forum/posts/{id}/enable` | Enables the post through `EnablePosts`. |
| `POST /forum/posts/{id}/disable` | Disables the post through `DisablePosts`. |
| `POST /forum/posts/{id}/soft-delete` | Moves the post to the trash through `SoftDeletePosts`, with an optional `reason`. |
| `POST /forum/posts/{id}/restore` | Restores the post through `RestorePosts`. |
| `DELETE /forum/posts/{id}` | Deletes a post in the trash through `DeletePosts`. |
| `POST /forum/posts/{id}/mark-as-best-answer` | Marks the post as the best answer of its thread. |
| `POST /forum/posts/{id}/unmark-as-best-answer` | Unmarks the post as the best answer of its thread. |
| `POST /forum/posts/copy` | Copies the posts in `postIDs` to the thread `threadID` in chunks of 20 posts, the next request passes the `lastPostID` of the previous response until it reports `completed`. |
| `GET /forum/posts/{id}/ip-log` | Returns the IP addresses of the author of the post. |
| `GET /forum/threads/{id}/new-posts` | Returns the rendered posts of the page `pageNo` of the thread, which is used to load new posts. |

## Interactions

The interactions of a post are provided by `wbb\system\interaction\user\PostInteractions`, the interactions of a post in a thread by `wbb\system\interaction\user\ThreadPostInteractions`, which adds the quote and the best answer interactions.
Plugins add their interactions in a listener of `wbb\event\interaction\user\PostInteractionCollecting`, which is fired for both providers, or in a subclass that overrides `PostInteractions::addContextInteractions()`.

The bulk interactions of posts are provided by `wbb\system\interaction\bulk\user\PostBulkInteractions` and are extended through `wbb\event\interaction\bulk\user\PostBulkInteractionCollecting`.
They replace the clipboard of posts, see [Removals](#removals).

The interaction menu in the header of the thread page uses `wbb\system\interaction\user\ThreadPageInteractions`, which extends `ThreadInteractions` and fires `wbb\event\interaction\user\ThreadInteractionCollecting`.
`ThreadInteractions` is no longer final.

The dialogs of the interactions are form builder dialogs that are provided by actions:

| Interaction | Action |
|---|---|
| Editing a post | `wbb\action\PostEditAction` |
| Enabling a post | `wbb\action\PostEnableDialogAction` |
| Enabling a thread | `wbb\action\ThreadEnableDialogAction` |
| Merging posts | `wbb\action\PostMergeDialogAction` |
| Moving posts to a new thread | `wbb\action\PostMoveToNewThreadDialogAction` |
| Moving posts to a thread | `wbb\action\PostMoveToExistingThreadDialogAction` |
| Copying posts to a new thread | `wbb\action\PostCopyToNewThreadDialogAction` |
| Copying posts to a thread | `wbb\action\PostCopyToExistingThreadDialogAction` |

`Post` provides the checks `canClose()`, `canMerge()`, `canMove()` and `canCopy()` for these interactions.

## Editing Posts and the Quick Reply

Posts are edited inline through the form `wbb\system\form\builder\PostEditForm`, which `wbb\action\PostEditAction` provides and saves through `wbb\command\post\UpdatePost`.
The quick reply is the form `wbb\system\form\builder\ThreadQuickReplyForm`, which is shown on the thread page and saved by `wbb\action\ThreadQuickReplyAction` through `wbb\command\post\CreatePost`.
Neither of them runs `PostAction` any more, the methods `beginEdit()`, `save()` and `quickReply()` of `PostAction` have been removed.

The events `wbb\event\post\PostEditFormBuilding` and `wbb\event\post\QuickReplyFormBuilding` are fired before the forms are built.
Their listeners add form nodes, either to the form itself or to the settings of the message container with the id `message`.
The values are written into the `PostBuilder` by the save value callbacks of the added fields: values of custom columns of `wbb1_post` with `setCustomProperty()`, any other values with `setExtensionData()`, which a listener of `wbb\event\post\PostUpdated` or `wbb\event\post\PostCreated` processes.

```php
<?php

namespace example\system\event\listener;

use wbb\data\post\PostBuilder;
use wbb\event\post\PostEditFormBuilding;
use wcf\system\form\builder\container\wysiwyg\WysiwygFormContainer;
use wcf\system\form\builder\field\BooleanFormField;

final class PostEditFormBuildingListener
{
    public function __invoke(PostEditFormBuilding $event): void
    {
        $container = $event->form->getNodeById('message');
        \assert($container instanceof WysiwygFormContainer);

        $container->addSettingsNode(
            BooleanFormField::create('examplePinned')
                ->label('example.post.pinned')
                ->value($event->post->examplePinned)
                ->saveValueCallback(static function (PostBuilder $builder, BooleanFormField $field) {
                    $builder->setCustomProperty('examplePinned', $field->getSaveValue());
                })
        );
    }
}
```

The ids of added form nodes must be unique, prefix them with the identifier of the package.

A reply that is written by the author of the last post of the thread shortly after it is merged into that post.
The event `wbb\event\post\QuickReplyMergeChecking` is fired before a reply is merged, interrupting it with `preventDefault()` creates a new post instead.
It replaces the event `quickReplyShouldMerge` of `PostAction`.

## Removals

The removed classes, methods, properties, JavaScript and templates are listed in [Deprecations and Removals](deprecations_removals.md#removals).

### PHP

`wbb\data\post\PostAction` no longer implements `IClipboardAction`, `IAttachmentMessageQuickReplyAction`, `IMessageInlineEditorAction` and `IMessageQuickReplyParametersAction`, `wbb\data\thread\ThreadAction` no longer implements `ISearchAction`.

The clipboard of posts has been removed, together with the object type `com.woltlab.wbb.post` of the definition `com.woltlab.wcf.clipboardItem` and its clipboard actions.
Use the bulk interactions of the post list views instead.

### JavaScript

`js/WBB.js` has been removed together with the `WBB` namespace and all of its classes.
The modules of the legacy thread page in `WoltLabSuite/Forum/Controller`, `WoltLabSuite/Forum/Handler/Thread`, `WoltLabSuite/Forum/Ui/Post` and `WoltLabSuite/Forum/Ui/Thread` have been removed as well.
The thread page uses the modules in `WoltLabSuite/Forum/Component/Post` and `WoltLabSuite/Forum/Component/Thread` instead.

### Templates

The templates `thread` and `userPostList` have been rewritten, they only contain the container of their list view.
The posts are rendered by the template `postListItems` with the markup of the message list view of Core.
Each post is an `article.listView__item.messageListView__item` with the id `post{postID}` and its id in `data-object-id`, its message is in `.messageListView__item__message`.

| Previous markup | Replacement |
|---|---|
| `article.wbbPost.message.jsMessage` | `article.listView__item.messageListView__item` |
| `.messageBody`, `.messageText` | `.messageListView__item__message` |
| `data-post-id` | `data-object-id` |
| `data-can-*`, `data-is-*` | The interactions of the post |
| `.wbbPostBestAnswer` | `.wbbPostListView__item--bestAnswer` |
| `.wbbPostOfficial` | `.wbbPostListView__item--official` |
| `.messageGroupStarter` | `.messageListView__item--contentAuthor` |
| `.wbbPostDeleted`, `.messageCollapsed`, `.ignoredUserMessage` | `.messageListView__item--collapsed` |
| `.messageDeleted`, `.messageDisabled` | `{badge type='deleted'}` and `{badge type='disabled'}` |
| `#messageQuickReply` | `#wbbThreadQuickReplyContainer` |

The template events of the posts have been removed together with the templates `threadPostList`, `deletedPostList` and `moderationPost`, and from the template `userPostList`.
`postListItems` provides these template events instead:

| Event | Location |
|---|---|
| `header` | In the header of the post, before its toolbar |
| `badges` | After the badges of the post |
| `noticesBeforeMessage` | After the notices before the message |
| `beforeMessage` | Before the message |
| `noticesAfterMessage` | After the notices after the message |
| `afterMessage` | After the message, before the footer |
| `footerButtons` | After the buttons in the footer, before the interaction menu |

The sidebar of a post is the template `messageListViewSidebar`, see [Message List View Sidebar](templates.md#message-list-view-sidebar).
The template events `messageHeaderMetaData`, `messageStatus`, `messageQuickOptions`, `messageHeader`, `beforeMessageText`, `afterMessageText`, `messageFooter`, `messageFooterNotes`, `messageFooterButtons`, `messageFooterButtonsExtra`, `afterMessageFooterGroup`, `beforePosts`, `beforePost` and `afterPost` have no direct replacement.
The template event `javascriptInclude` of `thread` has been removed.

The templates `threadListItems` and `threadContentHeaderTitle` show the state of a thread through the [`{badge}`](../../view/template-plugins.md#badge) template plugin, the classes `.wbbThreadListView__item__badge` and their styles have been removed.

The quick reply and the inline editor no longer use the templates `threadQuickReply`, `postInlineEditor`, `__threadFormInline`, `__threadFormInlineTab` and `__messageFormSettingsInlinePost`, which have been removed together with their template listeners and the template event `beforeWysiwyg`.
Use the events `QuickReplyFormBuilding` and `PostEditFormBuilding` instead, see [Editing Posts and the Quick Reply](#editing-posts-and-the-quick-reply).
