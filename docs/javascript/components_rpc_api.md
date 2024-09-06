# RPC API

The [PHP RPC API](../php/api/rpc_api.md) has a companion implementation in TypeScript that greatly simplifies the communication with the server and provides helper methods to process responses.

# Implementing the API

## Naming Schema

The module should roughly map to the route parameters for simplicity, for example, `WoltLabSuite/Core/Api/Sessions/DeleteSession` maps to the `DELETE /sessions/:id` endpoint.

## Using `WoltLabSuite/Core/Api/Result`

The `Result` module provides a consistent interface to interact with the API.
A comprehensive implementation can be found in `WoltLabSuite/Core/Api/Comments/RenderComment.ts`:

```ts
import { prepareRequest } from "WoltLabSuite/Core/Ajax/Backend";
import { ApiResult, apiResultFromError, apiResultFromValue } from "WoltLabSuite/Core/Api/Result";

type Response = {
  template: string;
  response: string | undefined;
};

export async function renderComment(
  commentId: number,
  responseId: number | undefined = undefined,
  messageOnly: boolean = false,
  objectTypeId: number | undefined = undefined
): Promise<ApiResult<Response>> {
  const url = new URL(
    `${window.WSC_RPC_API_URL}core/comments/${commentId}/render`
  );
  url.searchParams.set("messageOnly", messageOnly.toString());
  if (responseId !== undefined) {
    url.searchParams.set("responseID", responseId.toString());
  }
  if (objectTypeId !== undefined) {
    url.searchParams.set("objectTypeID", objectTypeId.toString());
  }

  let response: Response;
  try {
    response = (await prepareRequest(url).get().fetchAsJson()) as Response;
  } catch (e) {
    return apiResultFromError(e);
  }

  return apiResultFromValue(response);
}
```

For `GET` and `DELETE` parameters it is possibly to provide additional parameters using the query string.
You should use the native `URL` class to (conditionally) set those parameters as shown in the example above.

### `ApiResult`

The type `ApiResult` represents the two possible states of the response, either an error or the actual result.
The callee should evaluate the `ok` property to distinguish between the two cases to properly handle any rejections.

Sometimes an action is infallible by design in which case the shortcut `.unwrap()` should be used.
It will return the result value or fail hard when there is an actual error.

#### `apiResultFromError(error: unknown)`

This function checks if the `error` represents a server response that is not in the 2xx range.
Any network errors or other kind of client errors will fail hard.

The returned value from the server will be attempted to be parsed into an `WoltLabSuite/Core/Api/Error` that represents the well-defined error response from the PHP RPC API.
Validation errors can easily be detected through the `.getValidationError()` method of `ApiError` which returns `undefined` for all other error classes.
