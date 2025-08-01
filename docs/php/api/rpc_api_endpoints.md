# RPC Endpoints

| Name | Type  | Route | Description |
| ---- | ----- | ----- | ----------- |
| DeleteBlog | Delete | /blog/blogs/{id:\d+}  | Deletes the blog with the given ID. |
| SetBlogAsFeatured | Post | /blog/blogs/{id:\d+}/set-as-featured  | Flags the blog with the given ID as featured. |
| UnsetBlogAsFeatured | Post | /blog/blogs/{id:\d+}/unset-as-featured  | Deflags the blog with the given ID as featured. |
| DeleteEntry | Delete | /blog/entries/{id:\d+}  | Deletes the blog entry with the given ID. |
| GetEntryContentHeaderTitle | Get | /blog/entries/{id:\d+}/content-header-title  | Retrieves the HTML code for the content header title of the blog entry with the given ID. |
| DisableEntry | Post | /blog/entries/{id:\d+}/disable  | Disables the blog entry with the given ID. |
| EnableEntry | Post | /blog/entries/{id:\d+}/enable  | Enables the blog entry with the given ID. |
| GetEntryPopover | Get | /blog/entries/{id:\d+}/popover  | Retrieves the HTML code for the popover of the blog entry with the given ID. |
| RestoreEntry | Post | /blog/entries/{id:\d+}/restore  | Restores a soft-deleted blog entry with the given ID. |
| SetEntryAsFeatured | Post | /blog/entries/{id:\d+}/set-as-featured  | Flags the blog entry with the given ID as featured. |
| SoftDeleteEntry | Post | /blog/entries/{id:\d+}/soft-delete  | Soft-deletes the blog entry with the given ID. |
| UnsetEntryAsFeatured | Post | /blog/entries/{id:\d+}/unset-as-featured  | Deflags the blog entry with the given ID as featured. |
| GetEventDateOverlap | Get | /calendar/events/dates/overlap  | Retrieves the HTML code for showing overlapping calendar events. |
| GetEventDatePopover | Get | /calendar/events/dates/{id:\d+}/popover  | Retrieves the HTML code for the popover of the calendar event with the given ID. |
| DeleteImport | Delete | /calendar/events/imports/{id:\d+}  | Deletes the calendar event import with the given ID. |
| DisableImport | Post | /calendar/events/imports/{id:\d+}/disable  | Disables the calendar event import with the given ID. |
| EnableImport | Post | /calendar/events/imports/{id:\d+}/enable  | Enables the calendar event import with the given ID. |
| GetShowOrder | Get | /core/ads/show-order  | Retrieves the show order of ads. |
| ChangeShowOrder | Post | /core/ads/show-order  | Saves the show order of ads. |
| DeleteAd | Delete | /core/ads/{id:\d+}  | Deletes the ad with the given ID. |
| DisableAd | Post | /core/ads/{id:\d+}/disable  | Disables the blog entry with the given ID. |
| EnableAd | Post | /core/ads/{id:\d+}/enable  | Enables the blog entry with the given ID. |
| GetArticleContentHeaderTitle | Get | /core/articles/contents/{id:\d+}/content-header-title  | Retrieves the HTML code for the content header title of the article content with the given ID. |
| DeleteArticle | Delete | /core/articles/{id:\d+}  | Deletes the article with the given ID. |
| GetArticlePopover | Get | /core/articles/{id:\d+}/popover  | Retrieves the HTML code for the popover of the article with the given ID. |
| PublishArticle | Post | /core/articles/{id:\d+}/publish  | Publishes the article with the given ID. |
| RestoreArticle | Post | /core/articles/{id:\d+}/restore  | Restores the article with the given ID. |
| SoftDeleteArticle | Post | /core/articles/{id:\d+}/soft-delete  | Soft-deletes the article with the given ID. |
| UnpublishArticle | Post | /core/articles/{id:\d+}/unpublish  | Unpublishes the article with the given ID. |
| ChangeShowOrder | Post | /core/attachments/show-order  | Saves the show order of attachments. |
| DeleteAttachment | Delete | /core/attachments/{id:\d+}  | Deletes the attachment with the given ID. |
| DeleteProvider | Delete | /core/bbcodes/media/providers/{id:\d+}  | Deletes the media provider with the given ID. |
| DisableProvider | Post | /core/bbcodes/media/providers/{id:\d+}/disable  | Disables the media provider with the given ID. |
| EnableProvider | Post | /core/bbcodes/media/providers/{id:\d+}/enable  | Enables the media provider with the given ID. |
| DeleteBBCodes | Delete | /core/bbcodes/{id:\d+}  | Deletes the bbcode with the given ID. |
| DeleteBox | Delete | /core/boxes/{id:\d+}  | Enables the media provider with the given ID. |
| DisableBox | Post | /core/boxes/{id:\d+}/disable  | Disables the box with the given ID. |
| EnableBox | Post | /core/boxes/{id:\d+}/enable  | Enables the box with the given ID. |
| DeleteQuestion | Delete | /core/captchas/questions/{id:\d+}  | Deletes the captcha question with the given ID. |
| DisableQuestion | Post | /core/captchas/questions/{id:\d+}/disable  | Disables the captcha question with the given ID. |
| EnableQuestion | Post | /core/captchas/questions/{id:\d+}/enable  | Enables the captcha question with the given ID. |
| CreateComment | Post | /core/comments  | Creates a new comment. |
| RenderComments | Get | /core/comments/render  | Retrieves the HTML code for the rendering of a list of comments. |
| CreateResponse | Post | /core/comments/responses  | Creates a new comment response. |
| RenderResponses | Get | /core/comments/responses/render  | Retrieves the HTML code for the rendering of a list of comment responses. |
| UpdateResponse | Post | /core/comments/responses/{id:\d+}  | Updates the comment response with the given ID. |
| DeleteResponse | Delete | /core/comments/responses/{id:\d+}  | Deletes the comment response with the given ID. |
| EditResponse | Get | /core/comments/responses/{id:\d+}/edit  | Retrieves the HTML code for the editing of the comment response with the given ID. |
| EnableResponse | Post | /core/comments/responses/{id:\d+}/enable  | Enables the comment response with the given ID. |
| RenderResponse | Get | /core/comments/responses/{id:\d+}/render  | Retrieves the HTML code for the rendering of the comment response with the given ID. |
| DeleteComment | Delete | /core/comments/{id:\d+}  | Deletes the comment with the given ID. |
| UpdateComment | Post | /core/comments/{id:\d+}  | Updates the comment with the given ID. |
| EditComment | Get | /core/comments/{id:\d+}/edit  | Retrieves the HTML code for the editing of the comment with the given ID. |
| EnableComment | Post | /core/comments/{id:\d+}/enable  | Enables the comment response with the given ID. |
| RenderComment | Get | /core/comments/{id:\d+}/render  | Retrieves the HTML code for the rendering of the comment with the given ID. |
| GetShowOrder | Get | /core/contact/options/show-order  | Retrieves the show order of contact options. |
| ChangeShowOrder | Post | /core/contact/options/show-order  | Saves the show order of contact options. |
| DeleteOption | Delete | /core/contact/options/{id:\d+}  | Deletes the contact option with the given ID. |
| DisableOption | Post | /core/contact/options/{id:\d+}/disable  | Disables the contact option with the given ID. |
| EnableOption | Post | /core/contact/options/{id:\d+}/enable  | Enables the contact option with the given ID. |
| GetShowOrder | Get | /core/contact/recipients/show-order  | Retrieves the show order of contact recipients. |
| ChangeShowOrder | Post | /core/contact/recipients/show-order  | Saves the show order of contact recipients. |
| DeleteRecipient | Delete | /core/contact/recipients/{id:\d+}  | Deletes the contact recipient with the given ID. |
| DisableRecipient | Post | /core/contact/recipients/{id:\d+}/disable  | Disables the contact recipient with the given ID. |
| EnableRecipient | Post | /core/contact/recipients/{id:\d+}/enable  | Enables the contact recipient with the given ID. |
| DeleteConversationLabel | Delete | /core/conversations/labels/{id:\d+}  | Deletes the conversation label with the given ID. |
| GetConversationParticipantList | Get | /core/conversations/{conversationId:\d+}/participants  | Retrieves the HTML code for the list of participants of the conversation with the given ID. |
| RemoveConversationParticipant | Delete | /core/conversations/{conversationId:\d+}/participants/{participantId:\d+}  | Removes a participant from the conversation with the given ID. |
| CloseConversation | Post | /core/conversations/{id:\d+}/close  | Closes the conversation with the given ID for new messages. |
| GetConversationHeaderTitle | Get | /core/conversations/{id:\d+}/content-header-title  | Retrieves the HTML code for the content header title of the conversation with the given ID. |
| LeaveConversation | Post | /core/conversations/{id:\d+}/leave  | Leaves the conversation with the given ID. |
| LeavePermanentlyConversation | Post | /core/conversations/{id:\d+}/leave-permanently  | Leaves the conversation with the given ID permanently. |
| OpenConversation | Post | /core/conversations/{id:\d+}/open  | Opens the conversation with the given ID for new messages. |
| GetConversationPopover | Get | /core/conversations/{id:\d+}/popover  | Retrieves the HTML code for the popover of the conversation with the given ID. |
| RestoreConversation | Post | /core/conversations/{id:\d+}/restore  | Restores the conversation with the given ID. |
| ClearLogs | Delete | /core/cronjobs/logs  | Clears the cronjob log. |
| DeleteCronjob | Delete | /core/cronjobs/{id:\d+}  | Deletes the cronjob with the given ID. |
| DisableCronjob | Post | /core/cronjobs/{id:\d+}/disable  | Disables the cronjob with the given ID. |
| EnableCronjob | Post | /core/cronjobs/{id:\d+}/enable  | Enables the cronjob with the given ID. |
| ExecuteCronjob | Post | /core/cronjobs/{id:\d+}/execute  | Executes the cronjob with the given ID. |
| RenderException | Get | /core/exceptions/{id:[a-f0-9]{40}}/render  | Retrieves the HTML code for the rendering of the exception log entry with the given ID. |
| PrepareUpload | Post | /core/files/upload  | Prepares the upload of a file. |
| SaveChunk | Post | /core/files/upload/{identifier}/chunk/{sequenceNo:\d+}  | Reads a chunk of a file upload. |
| GenerateThumbnails | Post | /core/files/{id:\d+}/generate-thumbnails  | Generates thumbnails for the file with the given ID. |
| DeleteFile | Delete | /core/files/{id}  | Deletes the file with the given ID. |
| GetGridView | Get | /core/grid-views/render  | Retrieves the HTML code for the rendering of a grid view in a dialog. |
| GetRow | Get | /core/grid-views/row  | Retrieves the HTML code for the rendering of a grid view row. |
| GetRows | Get | /core/grid-views/rows  | Retrieves the HTML code for the rendering of a list of grid view rows. |
| GetBulkContextMenuOptions | Post | /core/interactions/bulk-context-menu-options  | Retrieves the HTML code for the rendering of a bulk interaction context menu. |
| GetContextMenuOptions | Get | /core/interactions/context-menu-options  | Retrieves the HTML code for the rendering of a interaction context menu. |
| GetShowOrder | Get | /core/labels/groups/show-order  | Retrieves the show order of label groups. |
| ChangeShowOrder | Post | /core/labels/groups/show-order  | Saves the show order of label groups. |
| DeleteGroup | Delete | /core/labels/groups/{id:\d+}  | Deletes the label group with the given ID. |
| ChangeLabelShowOrder | Post | /core/labels/groups/{id:\d+}/labels/show-order  | Saves the show order of the labels in the group with the given ID. |
| GetLabelShowOrder | Get | /core/labels/groups/{id:\d+}/labels/show-order  | Retrieves the show order of the labels in the group with the given ID. |
| DeleteLabel | Delete | /core/labels/{id:\d+}  | Deletes the label with the given ID. |
| DeleteItem | Delete | /core/languages/items/{id:\d+}  | Deletes the language item with the given ID. |
| DeleteLanguage | Delete | /core/languages/{id:\d+}  | Deletes the language with the given ID. |
| SetAsDefaultLanguage | Post | /core/languages/{id:\d+}/default  | Flags the language with the given ID as default language. |
| DisableLanguage | Post | /core/languages/{id:\d+}/disable  | Disables the language with the given ID. |
| EnableLanguage | Post | /core/languages/{id:\d+}/enable  | Enables the language with the given ID. |
| GetItem | Get | /core/list-views/item  | Retrieves the HTML code for the rendering of a list view item. |
| GetItems | Get | /core/list-views/items  | Retrieves the HTML code for the rendering of a list of list view items. |
| DeleteMenu | Delete | /core/menus/{id:\d+}  | Deletes the menu with the given ID. |
| GetMentionSuggestions | Get | /core/messages/mention-suggestions  | Retrieves the list of users and groups that can be mentioned. |
| GetMessageAuthor | Get | /core/messages/message-author  | Returns information about the author of a quoted message. |
| RenderQuote | Get | /core/messages/render-quote  | Retrieves data for the rendering of a quote. |
| ResetRemovalQuotes | Post | /core/messages/reset-removal-quotes  | Resets the session variable that stores the information which quotes should be deleted at the next request. |
| ChangeJustifiedStatus | Post | /core/moderation-queues/{id:\d+}/change-justified-status  | Changes the justified status of the report with the given ID. |
| CloseReport | Post | /core/moderation-queues/{id:\d+}/close  | Closes the report with the given ID by marking it as done without further processing. |
| DeleteContent | Post | /core/moderation-queues/{id:\d+}/delete-content  | Deletes the content associated with the moderation queue entry with the given ID. |
| EnableContent | Post | /core/moderation-queues/{id:\d+}/enable-content  | Enables the content associated with the moderation queue entry with the given ID. |
| MarkAsRead | Post | /core/moderation-queues/{id:\d+}/mark-as-read  | Marks the moderation queue entry with the given ID as read. |
| GetShowOrder | Get | /core/notices/show-order  | Retrieves the show order of user notices. |
| ChangeShowOrder | Post | /core/notices/show-order  | Saves the show order of user notices. |
| DeleteNotice | Delete | /core/notices/{id:\d+}  | Deletes the user notices with the given ID. |
| DisableNotice | Post | /core/notices/{id:\d+}/disable  | Disables the user notices with the given ID. |
| EnableNotice | Post | /core/notices/{id:\d+}/enable  | Enables the user notices with the given ID. |
| DeleteServer | Delete | /core/packages/updates/servers/{id}  | Deletes the package update server with the given ID. |
| DisableServer | Post | /core/packages/updates/servers/{id}/disable  | Disables the package update server with the given ID. |
| EnableServer | Post | /core/packages/updates/servers/{id}/enable  | Enables the package update server with the given ID. |
| DeletePage | Delete | /core/pages/{id:\d+}  | Deletes the page with the given ID. |
| DisablePage | Post | /core/pages/{id:\d+}/disable  | Disables the page with the given ID. |
| EnablePage | Post | /core/pages/{id:\d+}/enable  | Enables the page with the given ID. |
| DeleteSubscriptionUser | Delete | /core/paid/subscriptions/users/{id:\d+}  | Deletes the paid subscription user with the given ID. |
| DeleteSubscription | Delete | /core/paid/subscriptions/{id:\d+}  | Deletes the paid subscription with the given ID. |
| DisableSubscription | Post | /core/paid/subscriptions/{id:\d+}/disable  | Disables the paid subscription with the given ID. |
| EnableSubscription | Post | /core/paid/subscriptions/{id:\d+}/enable  | Enables the paid subscription with the given ID. |
| GetShowOrder | Get | /core/reactions/types/show-order  | Retrieves the show order of reaction types. |
| ChangeShowOrder | Post | /core/reactions/types/show-order  | Saves the show order of reaction types. |
| DeleteType | Delete | /core/reactions/types/{id:\d+}  | Deletes the reaction type with the given ID. |
| DisableType | Post | /core/reactions/types/{id:\d+}/disable  | Disables the reaction type with the given ID. |
| EnableType | Post | /core/reactions/types/{id:\d+}/enable  | Enables the reaction type with the given ID. |
| DeleteSession | Delete | /core/sessions/{id}  | Deletes one of the current user’s sessions, causing a device with that session id to be logged out. |
| GetSmileyShowOrder | Get | /core/smilies/categories/{id:\d+}/show-order  | Retrieves the show order of smilies in the category with the given ID. |
| ChangeSmileyShowOrder | Post | /core/smilies/categories/{id:\d+}/show-order  | Saves the show order of smilies in the category with the given ID. |
| GetShowOrder | Get | /core/smilies/show-order  | Retrieves the show order of smilies. |
| ChangeShowOrder | Post | /core/smilies/show-order  | Saves the show order of smilies. |
| DeleteSmiley | Delete | /core/smilies/{id:\d+}  | Deletes the smiley with the given ID. |
| DeleteStyle | Delete | /core/styles/{id:\d+}  | Deletes the style with the given ID. |
| AddDarkMode | Post | /core/styles/{id:\d+}/add-dark-mode  | Adds a dark mode to the style with the given ID. |
| CopyStyle | Post | /core/styles/{id:\d+}/copy  | Copies the style with the given ID. |
| DisableStyle | Post | /core/styles/{id:\d+}/disable  | Disables the style with the given ID. |
| EnableStyle | Post | /core/styles/{id:\d+}/enable  | Enables the style with the given ID. |
| SetStyleAsDefault | Post | /core/styles/{id:\d+}/set-as-default  | Flags the style with the given ID as default style. |
| DeleteTag | Delete | /core/tags/{id:\d+}  | Deletes the tag with the given ID. |
| DeleteTemplateGroup | Delete | /core/templates/groups/{id:\d+}  | Deletes the template group with the given ID. |
| DeleteTemplate | Delete | /core/templates/{id:\d+}  | Deletes the template with the given ID. |
| GetShowOrder | Get | /core/trophies/show-order  | Retrieves the show order of trophies. |
| ChangeShowOrder | Post | /core/trophies/show-order  | Saves the show order of trophies. |
| DeleteTrophy | Delete | /core/trophies/{id:\d+}  | Deletes the trophy with the given ID. |
| DisableTrophy | Post | /core/trophies/{id:\d+}/disable  | Disables the trophy with the given ID. |
| EnableTrophy | Post | /core/trophies/{id:\d+}/enable  | Enables the trophy with the given ID. |
| DeleteAssignment | Delete | /core/users/groups/assignments/{id:\d+}  | Deletes the trophy with the given ID. API endpoint for deleting user group assignments. |
| DisableAssignment | Post | /core/users/groups/assignments/{id:\d+}/disable  | Disables the user group assignments with the given ID. |
| EnableAssignment | Post | /core/users/groups/assignments/{id:\d+}/enable  | Enables the user group assignments with the given ID. |
| DeleteGroup | Delete | /core/users/groups/{id:\d+}  | Deletes the user group with the given ID. |
| DeleteOption | Delete | /core/users/options/{id:\d+}  | Deletes the user option with the given ID. |
| DisableOption | Post | /core/users/options/{id:\d+}/disable  | Disables the user option with the given ID. |
| EnableOption | Post | /core/users/options/{id:\d+}/enable  | Enables the user option with the given ID. |
| DeleteUserRank | Delete | /core/users/ranks/{id:\d+}  | Deletes the user rank with the given ID. |
| DeleteUserTrophy | Delete | /core/users/trophies/{id:\d+}  | Deletes the user trophy with the given ID. |
| RevertVersion | Post | /core/version-trackers/revert  | Reverts a version tracker object to a previous version. |
| GetFilePopover | Get | /filebase/files/{id:\d+}/popover  | Retrieves the HTML code for the popover of the filebase file with the given ID. |
| DeleteLicense | Delete | /filebase/licenses/{id:\d+}  | Deletes the filebase license with the given ID. |
| GetPostPopover | Get | /forum/posts/{id:\d+}/popover  | Retrieves the HTML code for the popover of the forum post with the given ID. |
| DeleteFeed | Delete | /forum/rss/feeds/{id:\d+}  | Deletes the RSS feed with the given ID. |
| DisableFeed | Post | /forum/rss/feeds/{id:\d+}/disable  | Disables the RSS feed with the given ID. |
| EnableFeed | Post | /forum/rss/feeds/{id:\d+}/enable  | Enables the RSS feed with the given ID. |
| GetThreadPopover | Get | /forum/threads/{id:\d+}/popover  | Retrieves the HTML code for the popover of the forum thread with the given ID. |
