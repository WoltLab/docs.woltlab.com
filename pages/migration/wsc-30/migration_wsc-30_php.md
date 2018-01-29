---
title: Migrating from WSC 3.0 - PHP
sidebar: sidebar
permalink: migration_wsc-30_php.html
folder: migration/wsc-30
---

## Approval-System for Comments

Comments can now be set to require approval by a moderator before being published. This feature is disabled by default if you do not provide a permission in the manager class, enabling it requires a new permission that has to be provided in a special property of your manage implementation.

```php
<?php
class ExampleCommentManager extends AbstractCommentManager {
  protected $permissionAddWithoutModeration = 'foo.bar.example.canAddCommentWithoutModeration';
}
```

## Raw HTML in User Activity Events

User activity events were previously encapsulated inside `<div class="htmlContent">…</div>`, with impacts on native elements such as lists. You can now disable the class usage by defining your event as raw HTML:

```php
<?php
class ExampleUserActivityEvent {
  // enables raw HTML for output, defaults to `false`
  protected $isRawHtml = true;
}
```

## Permission to View Likes of an Object

Being able to view the like summary of an object was restricted to users that were able to like the object itself. This creates situations where the object type in general is likable, but the particular object cannot be liked by the current users, while also denying them to view the like summary (but it gets partly exposed through the footer note/summary!).

Implement the interface `\wcf\data\like\IRestrictedLikeObjectTypeProvider` in your object provider to add support for this new permission check.

```php
<?php
class LikeableExampleProvider extends ExampleProvider implements IRestrictedLikeObjectTypeProvider, IViewableLikeProvider {
  public function canViewLikes(ILikeObject $object) {
    // perform your permission checks here
    return true;
  }
}
```

## Developer Tools: Sync Feature

The synchronization feature of the newly added developer tools works by invoking a package installation plugin (PIP) outside of a regular installation, while simulating the basic environment that is already exposed by the API.

However, not all PIPs qualify for this kind of execution, especially because it could be invoked multiple times in a row by the user. This is solved by requiring a special marking for PIPs that have no side-effects (= idempotent) when invoked any amount of times with the same arguments.

There's another feature that allows all matching PIPs to be executed in a row using a single button click. In order to solve dependencies on other PIPs, any implementing PIP must also provide the method `getSyncDependencies()` that returns the dependent PIPs in an arbitrary order.

```php
<?php
class ExamplePackageInstallationPlugin extends AbstractXMLPackageInstallationPlugin implements IIdempotentPackageInstallationPlugin {
  public static function getSyncDependencies() {
    // provide a list of dependent PIPs in arbitrary order
    return [];
  }
}
```

## Media Providers

Media providers were added through regular SQL queries in earlier versions, but this is neither convenient, nor did it offer a reliable method to update an existing provider. WoltLab Suite 3.1 adds a new `mediaProvider`-PIP that also offers a `className` parameter to off-load the result evaluation and HTML generation.

### Example Implementation

#### mediaProvider.xml

```xml
<?xml version="1.0" encoding="UTF-8"?>
<data xmlns="http://www.woltlab.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.woltlab.com http://www.woltlab.com/XSD/tornado/mediaProvider.xsd">
  <import>
    <provider name="example">
      <title>Example Provider</title>
      <regex><![CDATA[https?://example.com/watch?v=(?P<ID>[a-zA-Z0-9])]]></regex>
      <className><![CDATA[wcf\system\bbcode\media\provider\ExampleBBCodeMediaProvider]]></className>
    </provider>
  </import>
</data>
```

#### PHP Callback

The full match is provided for `$url`, while any capture groups from the regular expression are assigned to `$matches`.

```php
<?php
class ExampleBBCodeMediaProvider implements IBBCodeMediaProvider {
  public function parse($url, array $matches = []) {
    return "final HTML output";
  }
}
```

## Re-Evaluate HTML Messages

{% include callout.html content="You need to manually set the disallowed bbcodes in order to avoid unintentional bbcode evaluation. Please see [this commit](https://github.com/WoltLab/WCF/commit/7e058783da1378dda5393a9bb4df9cfe94e5b394) for a reference implementation inside worker processes." type="warning" %}

The HtmlInputProcessor only supported two ways to handle an existing HTML message:

 1. Load the string through `process()` and run it through the validation and sanitation process, both of them are rather expensive operations and do not qualify for rebuild data workers.
 2. Detect embedded content using `processEmbeddedContent()` which bypasses most tasks that are carried out by `process()` which aren't required, but does not allow a modification of the message.

The newly added method `reprocess($message, $objectType, $objectID)` solves this short-coming by offering a full bbcode and text re-evaluation while bypassing any input filters, assuming that the input HTML was already filtered previously.

### Example Usage

```php
<?php
// rebuild data workers tend to contain code similar to this:
foreach ($this->objectList as $message) {
 // ...
 if (!$message->enableHtml) {
   // ...
 }
 else {
   // OLD:
   $this->getHtmlInputProcessor()->processEmbeddedContent($message->message, 'com.example.foo.message', $message->messageID);

   // REPLACE WITH:
   $this->getHtmlInputProcessor()->reprocess($message->message, 'com.example.foo.message', $message->messageID);
   $data['message'] = $this->getHtmlInputProcessor()->getHtml();
 }
 // ...
}
```

{% include links.html %}
