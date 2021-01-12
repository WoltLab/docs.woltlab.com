# Exceptions

## SPL Exceptions

The [Standard PHP Library (SPL)](https://secure.php.net/manual/en/book.spl.php) provides some [exceptions](https://secure.php.net/manual/en/spl.exceptions.php) that should be used whenever possible.


## Custom Exceptions

!!! warning "Do not use `wcf\system\exception\SystemException` anymore, use specific exception classes!"

The following table contains a list of custom exceptions that are commonly used.
All of the exceptions are found in the `wcf\system\exception` namespace.

| Class name | (examples) when to use |
|-----------|------------------------|
| `IllegalLinkException` | access to a page that belongs to a non-existing object, executing actions on specific non-existing objects (is shown as http 404 error to the user) |
| `ImplementationException` | a class does not implement an expected interface |
| `InvalidObjectArgument` | <span class="label label-info">5.4+</span> API method support generic objects but specific implementation requires objects of specific (sub)class and different object is given |
| `InvalidObjectTypeException` | object type is not of an expected object type definition |
| `InvalidSecurityTokenException` | given security token does not match the security token of the active user's session |
| `ParentClassException` | a class does not extend an expected (parent) class |
| `PermissionDeniedException` | page access without permission, action execution without permission (is shown as http 403 error to the user) |
| `UserInputException` | user input does not pass validation |
