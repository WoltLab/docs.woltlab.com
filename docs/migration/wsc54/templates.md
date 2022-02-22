# Migrating from WSC 5.4 - Templates

## Content Interaction Buttons

Content interactions buttons are a new way to display action buttons at the top of the page. They are intended to replace the icons in `pageNavigationIcons` for better accessibility while reducing the amount of buttons in `contentHeaderNavigation`.

As a rule of thumb, there should be at most only one button in `contentHeaderNavigation` (primary action on this page) and three buttons in `contentInteractionButtons` (important actions on this page). Use `contentInteractionDropdownItems` for all other buttons.

The template [`contentInteraction`](https://github.com/WoltLab/WCF/blob/master/com.woltlab.wcf/templates/contentInteraction.tpl) is included in the header and the corresponding placeholders are thus available on every page.

See [WoltLab/WCF#4315](https://github.com/WoltLab/WCF/pull/4315) for details.

## Phrase Modifier

The `|language` modifier was added to allow the piping of the phrase through other functions. This has some unwanted side effects when used with plain strings that should not support variable interpolation. Another difference to `{lang}` is the evaluation on runtime rather than at compile time, allowing the phrase to be taken from a variable instead.

We introduces the new modifier `|phrase` as a thin wrapper around `\wcf\system::WCF::getLanguage()->get()`. Use `|phrase` instead of `|language` unless you want to explicitly allow template scripting on a variable's output.

See [WoltLab/WCF#4657](https://github.com/WoltLab/WCF/issues/4657) for details.
