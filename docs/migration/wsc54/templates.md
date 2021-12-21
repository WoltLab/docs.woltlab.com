# Migrating from WSC 5.4 - Templates

## Content Interaction Buttons

Content interactions buttons are a new way to display action buttons at the top of the page. They are intended to replace the icons in `pageNavigationIcons` for better accessibility while reducing the amount of buttons in `contentHeaderNavigation`.

As a rule of thumb, there should be at most only one button in `contentHeaderNavigation` (primary action on this page) and three buttons in `contentInteractionButtons` (important actions on this page). Use `contentInteractionDropdownItems` for all other buttons.

The template [`contentInteraction`](https://github.com/WoltLab/WCF/blob/master/com.woltlab.wcf/templates/contentInteraction.tpl) is included in the header and the corresponding placeholders are thus available on every page.

See [WoltLab/WCF#4315](https://github.com/WoltLab/WCF/pull/4315) for details.
