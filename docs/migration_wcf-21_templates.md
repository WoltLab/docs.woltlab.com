---
title: WCF 2.1.x - Templates
sidebar: sidebar
permalink: migration_wcf-21_templates.html
folder: migration/wcf-21
---

## Page Layout

The template structure has been overhauled and it is no longer required nor recommended to include internal templates, such as `documentHeader`, `headInclude` or `userNotice`. Instead use a simple `{include file='header'}` that now takes care of of the entire application frame.

* Templates must not include a trailing `</body></html>` after including the `footer` template.
* The `documentHeader`, `headInclude` and `userNotice` template should no longer be included manually, the same goes with the `<body>` element, please use `{include file='header'}` instead.
* The `sidebarOrientation` variable for the `header` template has been removed and no longer works.
* `header.boxHeadline` has been unified and now reads `header.contentHeader`

Please see the full example at the end of this page for more information.

## Sidebars

Sidebars are now dynamically populated by the box system, this requires a small change to unify the markup. Additionally the usage of `<fieldset>` has been deprecated due to browser inconsistencies and bugs and should be replaced with `section.box`.

Previous markup used in WoltLab Community Framework 2.1 and earlier:

```html
<fieldset>
    <legend><!-- Title --></legend>

    <div>
        <!-- Content -->
    </div>
</fieldset>
```

The new markup since WoltLab Suite 3.0:

```html
<section class="box">
    <h2 class="boxTitle"><!-- Title --></h2>

    <div class="boxContent">
        <!-- Content -->
    </div>
</section>
```

## Forms

The input tag for session ids `SID_INPUT_TAG` has been deprecated and no longer yields any content, it can be safely removed. In previous versions forms have been wrapped in `<div class="container containerPadding marginTop">…</div>` which no longer has any effect and should be removed.

If you're using the preview feature for WYSIWYG-powered input fields, you need to alter the preview button include instruction:

```smarty
{include file='messageFormPreviewButton' previewMessageObjectType='com.example.foo.bar' previewMessageObjectID=0}
```

*The message object id should be non-zero when editing.*

## Icons

The old `.icon-<iconName>` classes have been removed, you are required to use the official `.fa-<iconName>` class names from FontAwesome. This does not affect the generic classes `.icon` (indicates an icon) and `.icon<size>` (e.g. `.icon16` that sets the dimensions), these are still required and have not been deprecated.

Before:

```html
<span class="icon icon16 icon-list">
```

Now:

```html
<span class="icon icon16 fa-list">
```

### Changed Icon Names

Quite a few icon names have been renamed, the official wiki lists the [new icon names](https://github.com/FortAwesome/Font-Awesome/wiki/Upgrading-from-3.2.1-to-4) in FontAwesome 4.

## Changed Classes

* `.dataList` has been replaced and should now read `<ol class="inlineList commaSeparated">` (same applies to `<ul>`)
* `.framedIconList` has been changed into `.userAvatarList`

## Removed Elements and Classes

* `<nav class="jsClipboardEditor">` and `<div class="jsClipboardContainer">` have been replaced with a floating button.
* The anchors `a.toTopLink` have been replaced with a floating button.
* Avatars should no longer receive the class `framed`
* The `dl.condensed` class, as seen in the editor tab menu, is no longer required.
* Anything related to `sidebarCollapsed` has been removed as sidebars are no longer collapsible.

## Simple Example

The code below includes only the absolute minimum required to display a page, the content title is already included in the output.

```smarty
{include file='header'}

<div class="section">
    Hello World!
</div>

{include file='footer'}
```

## Full Example

```smarty
{*
    The page title is automatically set using the page definition, avoid setting it if you can!
    If you really need to modify the title, you can still reference the original title with:
    {$__wcf->getActivePage()->getTitle()}
*}
{capture assign='pageTitle'}Custom Page Title{/capture}

{*
    NOTICE: The content header goes here, see the section after this to learn more.
*}

{* you must not use `headContent` for JavaScript *}
{capture assign='headContent'}
    <link rel="alternate" type="application/rss+xml" title="{lang}wcf.global.button.rss{/lang}" href="…">
{/capture}

{* optional, content will be added to the top of the left sidebar *}
{capture assign='sidebarLeft'}
    …

    {event name='boxes'}
{/capture}

{* optional, content will be added to the top of the right sidebar *}
{capture assign='sidebarRight'}
    …

    {event name='boxes'}
{/capture}

{capture assign='headerNavigation'}
    <li><a href="#" title="Custom Button" class="jsTooltip"><span class="icon icon16 fa-check"></span> <span class="invisible">Custom Button</span></a></li>
{/capture}

{include file='header'}

{hascontent}
    <div class="paginationTop">
        {content}
            {pages …}
        {/content}
    </div>
{/hascontent}

{* the actual content *}
<div class="section">
    …
</div>

<footer class="contentFooter">
    {* skip this if you're not using any pagination *}
    {hascontent}
        <div class="paginationBottom">
            {content}{@$pagesLinks}{/content}
        </div>
    {/hascontent}

    <nav class="contentFooterNavigation">
        <ul>
            <li><a href="…" class="button"><span class="icon icon16 fa-plus"></span> <span>Custom Button</span></a></li>
            {event name='contentFooterNavigation'}
        </ul>
    </nav>
</footer>

<script data-relocate="true">
    /* any JavaScript code you need */
</script>

{* do not include `</body></html>` here, the footer template is the last bit of code! *}
{include file='footer'}
```

### Content Header

There are two different methods to set the content header, one sets only the actual values, but leaves the outer HTML untouched, that is generated by the `header` template. This is the recommended approach and you should avoid using the alternative method whenever possible.

#### Recommended Approach

```smarty
{* This is automatically set using the page data and should not be set manually! *}
{capture assign='contentTitle'}Custom Content Title{/capture}

{capture assign='contentDescription'}Optional description that is displayed right after the title.{/capture}

{capture assign='contentHeaderNavigation'}List of navigation buttons displayed right next to the title.{/capture}
```

#### Alternative

```smarty
{capture assign='contentHeader'}
    <header class="contentHeader">
        <div class="contentHeaderTitle">
            <h1 class="contentTitle">Custom Content Title</h1>
            <p class="contentHeaderDescription">Custom Content Description</p>
        </div>

        <nav class="contentHeaderNavigation">
            <ul>
                <li><a href="{link controller='CustomController'}{/link}" class="button"><span class="icon icon16 fa-plus"></span> <span>Custom Button</span></a></li>
                {event name='contentHeaderNavigation'}
            </ul>
        </nav>
    </header>
{/capture}
```
