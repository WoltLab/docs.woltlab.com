# RPC API

The RPC API provides a consistent and predictable JSON API for the interaction with the server.
Authentication is entirely handled through the existing cookie-backed session system.

The current implementation covers a few reference endpoints that replaced existing PSR-based actions.
It is designed to be a replacement for the existing `AbstractDatabaseObjectAction` actions in the mid to long term.
You are more than welcome to share any feedback, including but not limited to suggestions for improvements, on GitHub.

The implementation of the RPC API is built in a way that it could support a token based authentication in the future but this is entirely out of scope for the current iteration.

# Predictable API

## Idempotency Whenever Reasonably Possible

Some endpoints will perform a specific action, such as following a user or reacting to a message. These endpoints SHOULD be idempotent whenever possible, treating the request to be the “should” state.

For example, following a user for the first time should produce the same response as attempting to follow them again while still following. Reacting to a message that has already been reacted to using a difference reaction should implicitly revoke the previous reaction. If the user has already reacted using the same reaction then no change should be made. However, in both cases the response should be indistinguishable from reacting to a message for the first time.

## HTTP Verbs

We will only support three basic verbs: `GET`, `POST` and `DELETE`.

Some implementations also make use of `PATCH` and `PUT`, but this would make it much more complex and adds little benefit plus its quite verbose since the verb implies the semantics of the endpoint. The same can be achieved by using distinct endpoints and using `POST` instead. Plus historically the support for `PATCH` and `PUT` was rather poor.

## Response Format

The PSR messaging interface allows for a host of useful response types, such as `204 No Content`, returning a plain “HTML” response or any format that is suitable. While this is more efficient, it also makes the API much more complicated and less predictable, requiring different response handling based on the endpoint being targeted.

## HTTP Status Codes

| Code | Name                  | Meaning                                                   |
| ---- | --------------------- | --------------------------------------------------------- |
| 200  | OK                    | The request was successful.                               |
| 400  | Bad Request           | The provided parameters are insufficient.                 |
| 403  | Permission Denied     | The callee is not authorized to execute this request.     |
| 404  | Not Found             | The requested endpoint does not exist.                    |
| 405  | Method Not Allowed    | Using any HTTP verb other than `GET`, `POST` or `DELETE`. |
| 500  | Internal Server Error | The server failed to process the request.                 |
| 503  | Service Unavailable   | The API is currently unavailable.                         |

## Error Format

Whenever a request is rejected with the error code 400, the response will match the following data structure:

```ts
type ErrorResponse = {
  type: "api_error" | "invalid_request_error";
  code: string;
  message: string;
  param: string;
};
```

A typical response could look like this:

```json
{
  "type": "invalid_request_error",
  "code": "value_too_short",
  "message": "",
  "param": "username"
}
```

### `type`

The error type is used to tell apart issues caused by the request being made by the client or by unexpected errors taking place on the server side processing.

### `code`

The error code is all lowercase and using snake case to describe the type of error, for example, `missing_api_key`.

### `message`

The message can be empty but when present contains a non-localized string explaining the cause of the error with the intention of assisting a developer to resolve the issue.

You MUST NOT present this message to the end user.

### `param`

Validation errors may refer to a specific parameter that has caused the request to be rejected. A common use case is to point to a specific input field, allowing for contextual error message presented to the user.

## Examples

The endpoints below may or may not exist at any point and are only used for illustration purposes.

| Verb     | Endpoint                                 | Explanation                                                                     |
| -------- | ---------------------------------------- | ------------------------------------------------------------------------------- |
| `GET`    | `/core/users`                            | Retrieve a list of users, may include query parameters to control the response. |
| `GET`    | `/core/users/{id:\d+}`                   | Retrieve a user, may include query parameters to control the response.          |
| `GET`    | `/core/users/{id:\d+}/following`         | Retrieve a list of users that the target user is following.                     |
| -        | -                                        | -                                                                               |
| `POST`   | `/core/users`                            | Creates a new user, parameters are always presented in the body.                |
| `POST`   | `/core/users/{id:\d+}`                   | (Partially) updates a user.                                                     |
| `POST`   | `/core/moderationqueues/{id:\d+}/assign` | Assigns a user.                                                                 |
| -        | -                                        | -                                                                               |
| `DELETE` | `/forum/threads/{id:\d+}`                | Deletes a thread.                                                               |

# Implementation of an Endpoint

## Namespaces for Endpoints

Endpoints are defined using a strict rule set:

- The first path segment is a namespace that holds other objects.
- The namespaces `core`, `forum`, `blog`, `filebase` and `gallery` are reserved and MUST NOT be used by third parties.
- Segments MUST be lowercase ASCII and use the plural form.
- Parameters MAY appear starting with the third path segment and are defined with a leading colon.
- The name of a parameter MUST be unique within one endpoint.

## Convention for File Name and Location

It is strongly recommended to place the files in `lib/system/endpoint/<namespace>/<objects>/<nameOfTheAction>.class.php`.
The file name should reflect the action itself, following the pattern `<Verb><Object>`, for example, `DeleteFile` or `CreatePost`.

## Registering the Route of an Endpoint

Every endpoint needs to implement `wcf\system\endpoint\IController` which defines the `__invoke()` method that will receive the `ServerRequestInterface` and an array containing any defined route parameters.

Any endpoint can only ever serve a single verb, registered through the use of the `wcf\system\endpoint\GetRequest`, `wcf\system\endpoint\PostRequest` or `wcf\system\endpoint\DeleteRequest` class attribute.
The attribute expects a single parameter to define the endpoint’s route.

### Placeholders

The route implementation uses [FastRoute](https://github.com/nikic/FastRoute) which supports named placeholders through the `{name}` syntax. Optionally, a validation pattern can be specified to further narrow down the valid value of the placeholder: `{id:\d+}`

## Available Helper Methods

The `wcf\http\Helper` class offers a few helpful methods that simplify the validation and processing of request parameters.

### `mapApiParameters(ServerRequestInterface $request, string $className)`

Takes the `$request` from the `__invoke()` call and maps the parameters against the provided class name.
By convention it is recommended to use an internal class that is defined at the end of the class file and uses a `Parameters` suffix.

For `GET` and `DELETE` requests the query string is used as the source.
For `POST` requests the request body is mapped to the parameters.

### `fetchObjectFromRequestParameter(int|string $objectID, string $className)`

Expects `$className` to be derived from `wcf\data\DatabaseObject` and attempts to fetch it using the `$objectID` parameter.
Afterwards the object is tested to have a non-falsy object id, otherwise a `UserInputException` is raised.

Returns the fetched object on success.
