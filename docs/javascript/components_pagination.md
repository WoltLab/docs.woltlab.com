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

### `url`

Defaults to an empty string.

If defined, static pagination links are created based on the URL with the `pageNo` parameter appended to it.
Otherwise only the `switchPage` event will be fired if a user clicks on a pagination link.

## Events

### `switchPage`

The `switchPage` event will be fired when the user clicks on a pagination link. The event detail will contain the number of the selected page.
The event can be canceled to prevent navigation.

### `jumpToPage`

The `jumpToPage` event will be fired when the user clicks on one of the ellipsis buttons within the pagination.
