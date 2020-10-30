---
title: Migrating from WSC 5.3 - Third Party Libraries
sidebar: sidebar
permalink: migration_wsc-53_libraries.html
folder: migration/wsc-53
---

## Guzzle

The bundled Guzzle version was updated to Guzzle 7.
No breaking changes are expected for simple uses.
A detailed [Guzzle migration guide](https://github.com/guzzle/guzzle/blob/master/UPGRADING.md#60-to-70) can be found in the Guzzle documentation.

The explicit `sink` that was recommended in the [migration guide for WSC 5.2](migration_wsc-52_libraries.html#guzzle) can now be removed, as [the Guzzle issue #2735](https://github.com/guzzle/guzzle/issues/2735) was fixed in Guzzle 7.
