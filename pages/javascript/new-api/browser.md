---
title: Browser and Screen Sizes - JavaScript API
sidebar: sidebar
permalink: javascript_new-api_browser.html
folder: javascript
---

## `Ui/Screen`

CSS offers powerful media queries that alter the layout depending on the screen
sizes, including but not limited to changes between landscape and portrait mode
on mobile devices.

The `Ui/Screen` module exposes a consistent interface to execute JavaScript code
based on the same media queries that are available in the CSS code already. It
features support for unmatching and executing code when a rule matches for the
first time during the page lifecycle.

### Supported Aliases

You can pass in custom media queries, but it is strongly recommended to use the
built-in media queries that match the same dimensions as your CSS.

| Alias | Media Query |
|---|---|
| `screen-xs` | `(max-width: 544px)` |
| `screen-sm` | `(min-width: 545px) and (max-width: 768px)` |
| `screen-sm-down` | `(max-width: 768px)` |
| `screen-sm-up` | `(min-width: 545px)` |
| `screen-sm-md` | `(min-width: 545px) and (max-width: 1024px)` |
| `screen-md` | `(min-width: 769px) and (max-width: 1024px)` |
| `screen-md-down` | `(max-width: 1024px)` |
| `screen-md-up` | `(min-width: 769px)` |
| `screen-lg` | `(min-width: 1025px)`

### `on(query: string, callbacks: Object): string`

Registers a set of callback functions for the provided media query, the possible
keys are `match`, `unmatch` and `setup`. The method returns a randomly generated
UUIDv4 that is used to identify these callbacks and allows them to be removed
via `.remove()`.

### `remove(query: string, uuid: string)`

Removes all callbacks for a media query that match the UUIDv4 that was previously
obtained from the call to `.on()`.

### `is(query: string): boolean`

Tests if the provided media query currently matches and returns true on match.

### `scrollDisable()`

Temporarily prevents the page from being scrolled, until `.scrollEnable()` is
called.

### `scrollEnable()`

Enables page scrolling again, unless another pending action has also prevented
the page scrolling.

## `Environment`

{% include callout.html content="The `Environment` module uses a mixture of feature detection and user agent sniffing to determine the browser and platform. In general, its results have proven to be very accurate, but it should be taken with a grain of salt regardless. Especially the browser checks are designed to be your last resort, please use feature detection instead whenever it is possible!" type="warning" %}

Sometimes it may be necessary to alter the behavior of your code depending on
the browser platform (e. g. mobile devices) or based on a specific browser in
order to work-around some quirks.

### `browser(): string`

Attempts to detect browsers based on their technology and supported CSS vendor
prefixes, and although somewhat reliable for major browsers, it is highly
recommended to use feature detection instead.

Possible values:
 - `chrome` (includes Opera 15+ and Vivaldi)
 - `firefox`
 - `safari`
 - `microsoft` (Internet Explorer and Edge)
 - `other` (default)

### `platform(): string`

Attempts to detect the browser platform using user agent sniffing.

Possible values:
 - `ios`
 - `android`
 - `windows` (IE Mobile)
 - `mobile` (generic mobile device)
 - `desktop` (default)

{% include links.html %}
