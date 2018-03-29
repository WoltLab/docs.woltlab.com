---
title: Apps for WoltLab Suite
sidebar: sidebar
permalink: php_apps.html
folder: php
---

## Introduction

Apps are among the most powerful components in WoltLab Suite. Unlike plugins
that extend an existing functionality and pages, apps have their own frontend
with a dedicated namespace, database table prefixes and template locations.

However, apps are meant to be a logical (and to some extent physical) separation
from other parts of the framework, including other installed apps. They offer
an additional layer of isolation and enable you to re-use class and template
names that are already in use by the Core itself.

If you've come here, thinking about the question if your next package should be
an app instead of a regular plugin, the result is almost always: _No._

## Differences to Plugins

Apps do offer a couple of unique features that are not available to plugins and
there are valid reasons to use one instead of a plugin, but they also increase
both the code and system complexity. There is a performance penalty for each
installed app, regardless if it is actively used in a request or not, simplying
being there forces the Core to include it in many places, for example, class
resolution or even simple tasks such as constructing a link.

### Unique Namespace

Each app has its own unique namespace that is entirely separated from the Core
and any other installed apps. The namespace is derived from the last part of the
package identifier, for example, `com.example.foo` will yield the namespace `foo`.

The namespace is always relative to the installation directory of the app, it
doesn't matter if the app is installed on `example.com/foo/` or in `example.com/bar/`,
the namespace will always resolve to the right directory.

This app namespace is also used for ACP templates, frontend templates and files:

```xml
<!-- somewhere in the package.xml -->
<instructions type="file" application="foo" />
```

### Unique Database Table Prefix

All database tables make use of a generic prefix that is derived from one of the
installed apps, including `wcf` which resolves to the Core itself. Following the
aforementioned example, the new prefix `fooN_` will be automatically registered
and recognized in any generated statement.

Any `DatabaseObject` that uses the app's namespace is automatically assumed to
use the app's database prefix. For instance, `foo\data\bar\Bar` is implicitly
mapped to the database table `fooN_bar`.

The app prefix is recognized in SQL-PIPs and statements that reference one of
its database tables are automatically rewritten to use the Core's instance number.

### Separate Domain and Path Configuration

Any controller that is provided by a plugin is served from the configured domain
and path of the corresponding app, such as plugins for the Core are always
served from the Core's directory. Apps are different and use their own domain
and/or path to present their content, additionally, this allows the app to re-use
a controller name that is already provided by the Core or any other app itself.

## Creating an App

{% include callout.html content="This is a non-reversible operation! Once a package has been installed, its type cannot be changed without uninstalling and reinstalling the entire package, an app will always be an app and vice versa." type="danger" %}

### `package.xml`

The `package.xml` supports two additional elements in the `<packageinformation>`
block that are unique to applications.

#### `<isapplication>1</isapplication>`

This element is responsible to flag a package as an app.

#### `<applicationdirectory>example</applicationdirectory>`

Sets the suggested name of the application directory when installing it, the
path result in `<path-to-the-core>/example/`. If you leave this element out,
the app identifier (`com.example.foo -> foo`) will be used instead.

### Minimum Required Files

An example project with the [source code can be found on GitHub](https://github.com/WoltLab/woltlab.github.io/tree/master/_includes/tutorial/basic-app/),
it includes everything that is required for a basic app.

{% include links.html %}
