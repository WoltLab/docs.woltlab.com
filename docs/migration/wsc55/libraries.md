# Migrating from WoltLab Suite 5.5 - Third Party Libraries

## Symfony PHP Polyfills

The Symfony Polyfills for 7.3, 7.4, and 8.0 were removed, as the minimum PHP version was increased to PHP 8.1.
The Polyfills for PHP 8.2 and PHP 8.3 were added.

Refer to the documentation within the [symfony/polyfill](https://github.com/symfony/polyfill/) repository for details.

## IDNA Handling

The true/punycode and pear/net_idna2 dependencies were removed, because of a lack of upstream maintenance and because the `intl` extension is now required.
Instead the [`idn_to_ascii`](https://www.php.net/manual/en/function.idn-to-ascii.php) function should be used.

## Laminas Diactoros

Diactoros was updated from version 2.4 to 3.0.

## Input Validation

WoltLab Suite 6.0 ships with cuyz/valinor 1.5 as a reliable solution to validate untrusted external input values.

Refer to the documentation within the [CuyZ/Valinor](https://github.com/CuyZ/Valinor) repository for details.

## Diff

WoltLab Suite 6.0 ships with sebastian/diff as a replacement for `wcf\util\Diff`.
The `wcf\util\Diff::rawDiffFromSebastianDiff()` method was added as a compatibility helper to transform sebastian/diff's output format into Diff's output format.

Refer to the documentation within the [sebastianbergmann/diff](https://github.com/sebastianbergmann/diff) repository for details on how to use the library.

See [WoltLab/WCF#4918](https://github.com/WoltLab/WCF/pull/4918) for examples on how to use the compatibility helper if you need to preserve the output format for the time being.

## Content Negotiation

WoltLab Suite 6.0 ships with willdurand/negotiation to perform HTTP content negotiation based on the headers sent within the request.
The `wcf\http\Helper::getPreferredContentType()` method provides a convenience interface to perform content negotiation with regard to the MIME type.
It is strongly recommended to make use of this method instead of interacting with the library directly.

In case the API provided by the helper method is insufficient, please refer to the documentation within the [willdurand/Negotiation](https://github.com/willdurand/Negotiation) repository for details on how to use the library.

## Cronjobs

WoltLab Suite 6.0 ships with dragonmantank/cron-expression as a replacement for `wcf\util\CronjobUtil`.

This library is considered an internal library / implementation detail and not covered by backwards compatibility promises of WoltLab Suite.

## .ico converter

The chrisjean/php-ico dependency was removed, because of a lack of upstream maintenance.
As the library was only used for Favicon generation, no replacement is made available.
The favicons are now delivered as PNG files.
