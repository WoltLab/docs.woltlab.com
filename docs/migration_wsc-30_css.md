# Migrating from WSC 3.0 - CSS

## New Style Variables

!!! info "The new style variables are only applied to styles that have the compatibility set to WSC 3.1"

### wcfContentContainer

The page content is encapsulated in a new container that wraps around the inner content, but excludes the sidebars, header and page navigation elements.

 * `$wcfContentContainerBackground` - background color
 * `$wcfContentContainerBorder` - border color

### wcfEditorButton

These variables control the appearance of the editor toolbar and its buttons.

 * `$wcfEditorButtonBackground` - button and toolbar background color
 * `$wcfEditorButtonBackgroundActive` - active button background color
 * `$wcfEditorButtonText` - text color for available buttons
 * `$wcfEditorButtonTextActive` - text color for active buttons
 * `$wcfEditorButtonTextDisabled` - text color for disabled buttons

## Color Variables in `alert.scss`

The color values for `<small class="innerError">` used to be hardcoded values, but have now been changed to use the values for error messages (`wcfStatusError*`) instead.
