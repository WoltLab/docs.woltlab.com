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

## Emogrifier / CSS Inliner

The Emogrifier library was updated from version 2.2 to 5.0.
This update comes with a breaking change, as the `Emogrifier` class was removed.
With the updated Emogrifier library, the `CssInliner` class must be used instead.

No compatibility layer was added for the `Emogrifier` class, as the Emogrifier library's purpose was to be used within the email subsystem of WoltLab Suite.
In case you use Emogrifier directly within your own code, you will need to adjust the usage.
Refer to the [Emogrifier CHANGELOG](https://github.com/MyIntervals/emogrifier/blob/v5.0.0/CHANGELOG.md) and [WoltLab/WCF #3738](https://github.com/WoltLab/WCF/pull/3738) if you need help making the necessary adjustments.

If you only use Emogrifier indirectly by sending HTML mail via the email subsystem then you might notice unexpected visual changes due to the improved CSS support.
Double check your CSS declarations and particularly the specificity of your selectors in these cases.
