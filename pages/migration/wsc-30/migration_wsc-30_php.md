---
title: WSC 3.0 - PHP
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

{% include links.html %}
