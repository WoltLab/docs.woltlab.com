---
title: Ajax Requests - JavaScript API
sidebar: sidebar
permalink: javascript_new-api_ajax.html
folder: javascript
---

## Ajax inside Modules

The Ajax component was designed to be used from inside modules where an object
reference is used to delegate request callbacks. This is acomplished through
a set of magic methods that are automatically called when the request is created
or its state has changed.

### `_ajaxSetup()`

The lazy initialization is performed upon the first invocation from the callee,
using the magic `_ajaxSetup()` method to retrieve the basic configuration for
this and any future requests.

The data returned by `_ajaxSetup()` is cached and the data will be used to
pre-populate the request data before sending it. The callee can overwrite any of
these properties. It is intended to reduce the overhead when issuing request
when these requests share the same properties, such as accessing the same endpoint.

```js
// App/Foo.js
define(["Ajax"], function(Ajax) {
  "use strict";

  function Foo() {};
  Foo.prototype = {
    one: function() {
      // this will issue an ajax request with the parameter `value` set to `1`
      Ajax.api(this);
    },

    two: function() {
      // this request is almost identical to the one issued with `.one()`, but
      // the value is now set to `2` for this invocation only.
      Ajax.api(this, {
        parameters: {
          value: 2;
        }
      })
    },

    _ajaxSetup: function() {
      return {
        data: {
          actionName: "makeSnafucated",
          className: "app\\data\\foo\\FooAction",
          parameters: {
            value: 1
          }
        }
      }
    }
  };

  return Foo;
});
```

### Request Settings

The object returned by the aforementioned `_ajaxSetup()` callback can contain these
values:

#### `data`

_Defaults to `{}`._

A plain JavaScript object that contains the request data that represents the form
data of the request. The `parameters` key is recognized by the PHP Ajax API and
becomes accessible through `$this->parameters`.

#### `contentType`

_Defaults to `application/x-www-form-urlencoded; charset=UTF-8`._

The request content type, sets the `Content-Type` HTTP header if it is not empty.

#### `responseType`

_Defaults to `application/json`._

The server must respond with the `Content-Type` HTTP header set to this value,
otherwise the request will be treated as failed. Requests for `application/json`
will have the return body attempted to be evaluated as JSON.

Other content types will only be validated based on the HTTP header, but no
additional transformation is performed. For example, setting the `responseType`
to `application/xml` will check the HTTP header, but will not transform the
`data` parameter, you'll still receive a string in `_ajaxSuccess`!

#### `type`

_Defaults to `POST`._

The HTTP Verb used for this request.

#### `url`

_Defaults to an empty string._

Manual override for the request endpoint, it will be automatically set to the
Core API endpoint if left empty. If the Core API endpoint is used, the options
`includeRequestedWith` and `withCredentials` will be force-set to true.

#### `withCredentials`

{% include callout.html content="Enabling this parameter for any domain other than the current will trigger a CORS preflight request." type="warning" %}

_Defaults to `false`._

Include cookies with this requested, is always true when `url` is (implicitly)
set to the Core API endpoint.

#### `autoAbort`

_Defaults to `false`._

When set to `true`, any pending responses to earlier requests will be silently
discarded when issuing a new request. This only makes sense if the new request
is meant to completely replace the result of the previous one, regardless of its
reponse body.

Typical use-cases include input field with suggestions, where possible values
are requested from the server, but the input changed faster than the server was
able to reply. In this particular case the client is not interested in the result
for an earlier value, auto-aborting these requests avoids implementing this logic
in the requesting code.

#### `ignoreError`

_Defaults to `false`._

Any failing request will invoke the `failure`-callback to check if an error
message should be displayed. Enabling this option will suppress the general
error overlay that reports a failed request.

You can achieve the same result by returning `false` in the `failure`-callback.

#### `silent`

_Defaults to `false`._

Enabling this option will suppress the loading indicator overlay for this request,
other non-"silent" requests will still trigger the loading indicator.

#### `includeRequestedWith`

{% include callout.html content="Enabling this parameter for any domain other than the current will trigger a CORS preflight request." type="warning" %}

_Defaults to `true`._

Sets the custom HTTP header `X-Requested-With: XMLHttpRequest` for the request,
it is automatically set to `true` when `url` is pointing at the WSC API endpoint.

#### `failure`

_Defaults to `null`._

Optional callback function that will be invoked for requests that have failed
for one of these reasons:
 1. The request timed out.
 2. The HTTP status is not `2xx` or `304`.
 3. A `responseType` was set, but the response HTTP header `Content-Type` did not match the expected value.
 4. The `responseType` was set to `application/json`, but the response body was not valid JSON.

The callback function receives the parameter `xhr` (the `XMLHttpRequest` object)
and `options` (deep clone of the request parameters). If the callback returns
`false`, the general error overlay for failed requests will be suppressed.

There will be no error overlay if `ignoreError` is set to `true` or if the
request failed while attempting to evaluate the response body as JSON.

#### `finalize`

_Defaults to `null`._

Optional callback function that will be invoked once the request has completed,
regardless if it succeeded or failed. The only parameter it receives is
`options` (the request parameters object), but it does not receive the request's
`XMLHttpRequest`.

#### `success`

_Defaults to `null`._

This semi-optional callback function will always be set to `_ajaxSuccess()` when
invoking `Ajax.api()`. It receives four parameters:
 1. `data` - The request's response body as a string, or a JavaScript object if
    `contentType` was set to `application/json`.
 2. `responseText` - The unmodified response body, it equals the value for `data`
    for non-JSON requests.
 3. `xhr` - The underlying `XMLHttpRequest` object.
 4. `requestData` - The request parameters that were supplied when the request
    was issued.

### `_ajaxSuccess()`

This callback method is automatically called for successful AJAX requests, it
receives four parameters, with the first one containing either the response body
as a string, or a JavaScript object for JSON requests.

### `_ajaxFailure()`

Optional callback function that is invoked for failed requests, it will be
automatically called if the callee implements it, otherwise the global error
handler will be executed.

## Single Requests Without a Module

The `Ajax.api()` method expects an object that is used to extract the request
configuration as well as providing the callback functions when the request state
changes.

You can issue a simple Ajax request without object binding through `Ajax.apiOnce()`
that will destroy the instance after the request was finalized. This method is
significantly more expensive for repeated requests and does not offer deriving
modules from altering the behavior. It is strongly  recommended to always use
`Ajax.api()` for requests to the WSC API endpoint.

```html
<script data-relocate="true">
  require(["Ajax"], function(Ajax) {
    Ajax.apiOnce({
      data: {
        actionName: "makeSnafucated",
        className: "app\\data\\foo\\FooAction",
        parameters: {
          value: 3
        }
      },
      success: function(data) {
        elBySel(".some-element").textContent = data.bar;
      }
    })
  });
</script>
```

{% include links.html %}
