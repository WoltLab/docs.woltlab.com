# Migrating from WSC 5.3 - Third Party Libraries

## Guzzle

The bundled Guzzle version was updated to Guzzle 7.
No breaking changes are expected for simple uses.
A detailed [Guzzle migration guide](https://github.com/guzzle/guzzle/blob/master/UPGRADING.md#60-to-70) can be found in the Guzzle documentation.

The explicit `sink` that was recommended in the [migration guide for WSC 5.2](../wsc52/libraries.md#guzzle) can now be removed, as [the Guzzle issue #2735](https://github.com/guzzle/guzzle/issues/2735) was fixed in Guzzle 7.

## Emogrifier / CSS Inliner

The Emogrifier library was updated from version 2.2 to 5.0.
This update comes with a breaking change, as the `Emogrifier` class was removed.
With the updated Emogrifier library, the `CssInliner` class must be used instead.

No compatibility layer was added for the `Emogrifier` class, as the Emogrifier library's purpose was to be used within the email subsystem of WoltLab Suite.
In case you use Emogrifier directly within your own code, you will need to adjust the usage.
Refer to the [Emogrifier CHANGELOG](https://github.com/MyIntervals/emogrifier/blob/v5.0.0/CHANGELOG.md) and [WoltLab/WCF #3738](https://github.com/WoltLab/WCF/pull/3738) if you need help making the necessary adjustments.

If you only use Emogrifier indirectly by sending HTML mail via the email subsystem then you might notice unexpected visual changes due to the improved CSS support.
Double check your CSS declarations and particularly the specificity of your selectors in these cases.

## scssphp

scssphp was updated from version 1.1 to 1.4.

If you interact with scssphp only by deploying `.scss` files, then you should not experience any breaking changes, except when the improved SCSS compatibility interprets your SCSS code differently.

If you happen to directly use scssphp in your PHP code, you should be aware that scssphp deprecated the use of output formatters in favor of a simple output style enum.

Refer to [WoltLab/WCF #3851](https://github.com/WoltLab/WCF/pull/3851) and the [scssphp releases](https://github.com/scssphp/scssphp/releases) for details.

## Constant Time Encoder

WoltLab Suite 5.4 ships the [`paragonie/constant_time_encoding` library](https://github.com/paragonie/constant_time_encoding).
It is recommended to use this library to perform encoding and decoding of secrets to prevent leaks via cache timing attacks.
Refer to [the library author’s blog post](https://paragonie.com/blog/2016/06/constant-time-encoding-boring-cryptography-rfc-4648-and-you) for more background detail.

For the common case of encoding the bytes taken from a CSPRNG in hexadecimal form, the required change would look like the following:

Previously:

```php
<?php
$encoded = hex2bin(random_bytes(16));
```

Now:

```php
<?php
use ParagonIE\ConstantTime\Hex;

// For security reasons you should add the backslash
// to ensure you refer to the `random_bytes` function
// within the global namespace and not a function
// defined in the current namespace.
$encoded = Hex::encode(\random_bytes(16));
```

Please refer to the documentation and source code of the `paragonie/constant_time_encoding` library to learn how to use the library with different encodings (e.g. base64).
