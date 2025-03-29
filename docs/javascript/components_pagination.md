# Pagination - JavaScript API

The pagination component is used to expose multiple pages to the end user.
This component supports both static URLs and dynamic navigation using DOM events.

## Example

```html
<woltlab-core-pagination page="1" count="10" url="https://www.woltlab.com"></woltlab-core-pagination>
```

## Parameters

### `page`

Defaults to `1`.

The number of the currently displayed page.

### `count`

Defaults to `0`.

Number of available pages. Must be greater than `1` for the pagination to be displayed.

### `behavior`

Defines the behavior of the pagination. Defaults to `auto`.

| Value    | Behavior |
| -------- | -------- |
| `auto`   | If a `url` parameter has been passed, the behavior is automatically set to `link`, otherwise to `button`. |
| `link`   | The pagination creates static links using `<a>` tags. Requires the `url` parameter to be set. |
| `button` | The pagination creates buttons that will fire the `switchPage` event when clicked. If you combine `behavior="button"` with a value for the `url` parameter, the pagination will create static links using `<a>` tags, but will still fire the `switchPage` event when a link is clicked. |

### `url`

Defaults to an empty string.

Used to create static links using `<a>` tags with the `pageNo` parameter appended to it.

## Events

### `switchPage`

The `switchPage` event will be fired when the user clicks on a pagination link. The event detail will contain the number of the selected page.
The event can be canceled to prevent navigation.

### `jumpToPage`

The `jumpToPage` event will be fired when the user clicks on one of the ellipsis buttons within the pagination.
