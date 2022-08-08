# Migrating from WoltLab Suite 5.5 - PHP

## Minimum requirements

The minimum requirements have been increased to the following:

- **PHP:** 8.1.2 (64 bit only)
- **MySQL:** 8.0.29
- **MariaDB:** 10.5.12

It is recommended to make use of the newly introduced features whereever possible.
Please refer to the PHP documentation for details.

## Indicating parameters that hold sensitive information

PHP 8.2 adds native support for redacting parameters holding sensitive information in stack traces.
Parameters with the `#[\SensitiveParameter]` attribute will show a placeholder value within the stack trace and the error log.

WoltLab Suite’s exception handler contains logic to manually apply the sanitization for PHP versions before 8.2.

It is strongly recommended to add this attribute to all parameters holding sensitive information.
Examples for sensitive parameters include passwords/passphrases, access tokens, plaintext values to be encrypted, or private keys.

As attributes are fully backwards and forwards compatible it is possible to apply the attribute to packages targeting older WoltLab Suite or PHP versions without causing errors.

Example:

```php
function checkPassword(
    #[\SensitiveParameter]
    $password,
): bool {
    // …
}
```

See the [PHP RFC: Redacting parameters in back traces](https://wiki.php.net/rfc/redact_parameters_in_back_traces) for more details.
