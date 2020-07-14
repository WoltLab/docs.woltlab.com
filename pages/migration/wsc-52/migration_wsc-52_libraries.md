---
title: Migrating from WSC 5.2 - Third Party Libraries
sidebar: sidebar
permalink: migration_wsc-52_libraries.html
folder: migration/wsc-52
---

## SCSS Compiler

WoltLab Suite Core 5.3 upgrades the bundled SCSS compiler from `leafo/scssphp` 0.7.x to `scssphp/scssphp` 1.1.x.
With the updated composer package name the SCSS compiler also received updated namespaces.
WoltLab Suite Core adds a compatibility layer that maps the old namespace to the new namespace.
The classes themselves appear to be drop-in compatible.
Exceptions cannot be mapped using this compatibility layer, any `catch` blocks catching a specific Exception within the `Leafo` namespace will need to be adjusted.

More details can be found in the [Pull Request WoltLab/WCF#3415](https://github.com/WoltLab/WCF/pull/3415).
