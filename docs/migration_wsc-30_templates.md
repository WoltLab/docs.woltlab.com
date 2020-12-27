---
title: Migrating from WSC 3.0 - Templates
sidebar: sidebar
permalink: migration_wsc-30_templates.html
folder: migration/wsc-30
---

## Comment-System Overhaul

{% include callout.html content="Unfortunately, there has been a breaking change related to the creation of comments. You need to apply the changes below before being able to create new comments." type="danger" %}

### Adding Comments

Existing implementations need to include a new template right before including the generic `commentList` template.

```html
<ul id="exampleCommentList" class="commentList containerList" data-...>
  {include file='commentListAddComment' wysiwygSelector='exampleCommentListAddComment'}
  {include file='commentList'}
</ul>
```

## Redesigned ACP User List

Custom interaction buttons were previously added through the template event `rowButtons` and were merely a link-like element with an icon inside. This is still valid and supported for backwards-compatibility, but it is recommend to adapt to the new drop-down-style options using the new template event `dropdownItems`.

```html
<!-- button for usage with the `rowButtons` event -->
<span class="icon icon16 fa-list jsTooltip" title="Button Title"></span>

<!-- new drop-down item for the `dropdownItems` event -->
<li><a href="#" class="jsMyButton">Button Title</a></li>
```

## Sidebar Toogle-Buttons on Mobile Device

{% include callout.html content="You cannot override the button label for sidebars containing navigation menus." type="info" %}

The page sidebars are automatically collapsed and presented as one or, when both sidebar are present, two condensed buttons. They use generic sidebar-related labels when open or closed, with the exception of embedded menus which will change the button label to read "Show/Hide Navigation".

You can provide a custom label before including the sidebars by assigning the new labels to a few special variables:

```html
{assign var='__sidebarLeftShow' value='Show Left Sidebar'}
{assign var='__sidebarLeftHide' value='Hide Left Sidebar'}
{assign var='__sidebarRightShow' value='Show Right Sidebar'}
{assign var='__sidebarRightHide' value='Hide Right Sidebar'}
```

{% include links.html %}
