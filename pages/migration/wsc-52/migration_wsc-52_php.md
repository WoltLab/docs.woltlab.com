---
title: Migrating from WSC 5.2 - PHP
sidebar: sidebar
permalink: migration_wsc-52_php.html
folder: migration/wsc-52
---

## Comments

The [`ICommentManager::isContentAuthor(Comment|CommentResponse): bool`](https://github.com/WoltLab/WCF/blob/aa96d34130d58c150a35ebd8936f09c830ccd685/wcfsetup/install/files/lib/system/comment/manager/ICommentManager.class.php#L151-L158) was added.
A default implementation that always returns `false` is available when inheriting from `AbstractCommentManager`.

It is strongly recommended to implement `isContentAuthor` within your custom comment manager.
An example implementation [can be found in `ArticleCommentManager`](https://github.com/WoltLab/WCF/blob/aa96d34130d58c150a35ebd8936f09c830ccd685/wcfsetup/install/files/lib/system/comment/manager/ArticleCommentManager.class.php#L213-L219).

## Email Activation

Starting with WoltLab Suite 5.3 the user activation status is independent of the email activation status. 
A user can be activated even though their mail is not confirmed (and therefore emails are not sent to the user). 
Going forward the new `User::isEmailConfirmed()` method should be used to check whether sending automated emails to this user is acceptable. 
If you need to check the user's activation status you should use the new method `User::pendingActivation()` instead of relying on `activationCode`. 
To check, which type of activation is missing, you can use the new methods `User::requiresEmailActivation()` and `User::requiresAdminActivation()`. 
