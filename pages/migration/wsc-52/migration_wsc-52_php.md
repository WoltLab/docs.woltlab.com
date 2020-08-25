---
title: Migrating from WSC 5.2 - PHP
sidebar: sidebar
permalink: migration_wsc-52_php.html
folder: migration/wsc-52
---

## Comments

The [`ICommentManager::isContentAuthor(Comment|CommentResponse): bool`](https://github.com/WoltLab/WCF/blob/aa96d34130d58c150a35ebd8936f09c830ccd685/wcfsetup/install/files/lib/system/comment/manager/ICommentManager.class.php#L151-L158) method was added.
A default implementation that always returns `false` is available when inheriting from `AbstractCommentManager`.

It is strongly recommended to implement `isContentAuthor` within your custom comment manager.
An example implementation [can be found in `ArticleCommentManager`](https://github.com/WoltLab/WCF/blob/aa96d34130d58c150a35ebd8936f09c830ccd685/wcfsetup/install/files/lib/system/comment/manager/ArticleCommentManager.class.php#L213-L219).

## Event Listeners

The [`AbstractEventListener`](https://github.com/WoltLab/WCF/blob/75631516d45f9355f6c73d6375bf804d2abd587e/wcfsetup/install/files/lib/system/event/listener/AbstractEventListener.class.php) class was added.
`AbstractEventListener` contains an implementation of `execute()` that will dispatch the event handling to dedicated methods based on the `$eventName` and, in case of the event object being an `AbstractDatabaseObjectAction`, the action name.

Find the details of the dispatch behavior within the class comment of `AbstractEventListener`.
