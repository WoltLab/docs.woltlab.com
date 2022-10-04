# Migrating from WoltLab Suite 5.5 - Dialogs

## The State of Dialogs in WoltLab Suite 5.5 and earlier

In the past dialogs have been used for all kinds of purposes, for example, to provide more details.
Dialogs make it incredibly easy to add extra information or forms to an existing page without giving much thought: A simple button is all that it takes to show a dialog.

This has lead to an abundance of dialogs that have been used in a lot of places where dialogs are not the right choice, something we are guilty of in a lot of cases.
A lot of research has gone into the accessibility of dialogs and the general recommendations towards their usage and the behavior.

One big issue of dialogs have been their inconsistent appearance in terms of form buttons and their (lack of) keyboard support for input fields.
WoltLab Suite 6.0 provides a completely redesigned API that strives to make the process of creating dialogs much easier and features a consistent keyboard support out of the box.

## Migrating to the Dialogs of WoltLab Suite 6.0

The old dialogs are still fully supported and have remained unchanged apart from a visual update to bring them in line with the new dialogs.
We do recommend that you use the new dialog API exclusively for new components and migrate the existing dialogs whenever you see it fit, we’ll continue to support the legacy dialog API for the entire 6.x series at minimum.

### Migration by Example

TODO
