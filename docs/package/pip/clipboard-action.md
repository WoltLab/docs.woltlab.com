# Clipboard Action Package Installation Plugin

Registers clipboard actions.

## Components

Each clipboard action is described as an `<action>` element with the mandatory attribute `name`.

### `<actionclassname>`

The name of the class used by the clipboard API to process the concrete action.
The class has to implement the `wcf\system\clipboard\action\IClipboardAction` interface, best by extending `wcf\system\clipboard\action\AbstractClipboardAction`.

### `<pages>`

Element with `<page>` children whose value contains the class name of the controller of the page on which the clipboard action is available.

### `<showorder>`

<span class="label label-info">Optional</span>

Determines at which position of the clipboard action list the action is shown.


## Example

{jinja{ codebox(
    "xml",
    "package/pip/clipboardAction.xml",
    "clipboardAction.xml"
) }}
