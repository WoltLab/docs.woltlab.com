# Toggle Button

A toggle button is a control used for switching (or toggling) between two states or options. 

## Example

```html
<label>
    <woltlab-core-toggle-button name="foo" checked></woltlab-core-toggle-button>
    Toggle Button Description ...
</label>
```

## Parameters

### `checked`

Indicates that the button is active.

### `name`

The name of the button.
If the button is part of a form, the name is used to transmit the value when the form is sent.

### `value`

The value that should be transmitted when the form is sent if the button is active.

Defaults to `1`.
