---
title: Comments
sidebar: sidebar
permalink: php_api_comments.html
folder: php/api
---

## User Group Options

You need to create the following permissions:

| user group type | permission type | naming |
| --------------- | --------------- | ------ |
| user | creating comments | `user.foo.canAddComment` |
| user | editing own comments | `user.foo.canEditComment` |
| user | deleting own comments | `user.foo.canDeleteComment` |
| moderator | moderating comments | `mod.foo.canModerateComment` |
| moderator | editing comments | `mod.foo.canEditComment` |
| moderator | deleting comments | `mod.foo.canDeleteComment` |

Within their respective user group option category, the options should be listed in the same order as in the table above.


### Language Items

#### User Group Options

The language items for the comment-related user group options generally have the same values:

- `wcf.acp.group.option.user.foo.canAddComment`

  German: `Kann Kommentare erstellen`

  English: `Can create comments`

- `wcf.acp.group.option.user.foo.canEditComment`

  German: `Kann eigene Kommentare bearbeiten`

  English: `Can edit their comments`

- `wcf.acp.group.option.user.foo.canDeleteComment`

  German: `Kann eigene Kommentare löschen`

  English: `Can delete their comments`

- `wcf.acp.group.option.mod.foo.canModerateComment`

  German: `Kann Kommentare moderieren`

  English: `Can moderate comments`

- `wcf.acp.group.option.mod.foo.canEditComment`

  German: `Kann Kommentare bearbeiten`

  English: `Can edit comments`

- `wcf.acp.group.option.mod.foo.canDeleteComment`

  German: `Kann Kommentare löschen`

  English: `Can delete comments`
