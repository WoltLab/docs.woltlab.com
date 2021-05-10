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

## Sensitive Arguments in Stack Traces

Sometimes sensitive values are passed as a function or method argument.
If the callee throws an Exception these values will be part of the Exception’s stack trace and logged, unless the Exception is caught and ignored.

WoltLab Suite will automatically suppress the values of parameters named like they might contain sensitive values, namely arguments matching the regular expression `/(?:^(?:password|passphrase|secret)|(?:Password|Passphrase|Secret))/`.

If you need to suppress additional arguments from appearing in the stack trace you can add the `\wcf\SensitiveArgument` attribute to such parameters.
Arguments are only supported as of PHP 8, but interpreted as a comment in lower PHP versions.
In PHP 7 such arguments will not be suppressed, but the code will continue to work.
Make sure to insert a linebreak between the attribute and the parameter name.

Example:

{jinja{ codebox(
    language="php",
    title="wcfsetup/install/files/lib/data/user/User.class.php",
    contents="""
<?php

namespace wcf\data\user;

// …

final class User extends DatabaseObject implements IPopoverObject, IRouteController, IUserContent
{
    // …

    public function checkPassword(
        #[\wcf\SensitiveArgument()]
        $password
    ) {
        // …
    }

    // …
}
"""
) }}
