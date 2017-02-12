---
title: Exceptions
sidebar: sidebar
permalink: php_exceptions.html
folder: php
---

## SPL Exceptions

The [Standard PHP Library (SPL)](https://secure.php.net/manual/en/book.spl.php) provides some [exceptions](https://secure.php.net/manual/en/spl.exceptions.php) that should be used whenever possible.


## Custom Exceptions

{% include callout.html content="Do not use `wcf\system\exception\SystemException` anymore, use specific exception classes!" type="warning" %}

The following table contains a list of custom exceptions that are commonly used.

| exception | (examples) when to use |
|-----------|------------------------|
| `wcf\system\exception\IllegalLinkException` | access to a page that belongs to a non-existing object, executing actions on specific non-existing objects (is shown as http 404 error to the user) |
| `wcf\system\exception\ImplementationException` | a class does not implement an expected interface |
| `wcf\system\exception\InvalidObjectTypeException` | object type is not of an expected object type definition |
| `wcf\system\exception\InvalidSecurityTokenException` | given security token does not match the security token of the active user's session |
| `wcf\system\exception\ParentClassException` | a class does not extend an expected (parent) class |
| `wcf\system\exception\PermissionDeniedException` | page access without permission, action execution without permission (is shown as http 403 error to the user) |
| `wcf\system\exception\UserInputException` | user input does not pass validation |
