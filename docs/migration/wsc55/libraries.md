# Migrating from WoltLab Suite 5.5 - Third Party Libraries

## Symfony PHP Polyfills

The Symfony Polyfills for 7.3, 7.4, and 8.0 were removed, as the minimum PHP version was increased to PHP 8.1.
The Polyfill for PHP 8.2 was added.

Refer to the documentation within the [symfony/polyfill](https://github.com/symfony/polyfill/) repository for details.

## IDNA Handling

The true/punycode and pear/net_idna2 dependencies were removed, because of a lack of upstream maintenance and because the `intl` extension is now required.
Instead the [`idn_to_ascii`](https://www.php.net/manual/en/function.idn-to-ascii.php) function should be used.

## Laminas Diactoros

Diactoros was updated from version 2.4 to 2.22.

## Diff

WoltLab Suite 6.0 ships with sebastian/diff as a replacement for `wcf\util\Diff`.
The `wcf\util\Diff::rawDiffFromSebastianDiff()` method was added as a compatibility helper to transform sebastian/diff's output format into Diff's output format.

Refer to the documentation within the [sebastianbergmann/diff](https://github.com/sebastianbergmann/diff) repository for details on how to use the library.

See [WoltLab/WCF#4918](https://github.com/WoltLab/WCF/pull/4918) for examples on how to use the compatibility helper if you need to preserve the output format for the time being.

## Cronjobs

WoltLab Suite 6.0 ships with dragonmantank/cron-expression as a replacement for `wcf\util\CronjobUtil`.

This library is considered an internal library / implementation detail and not covered by backwards compatibility promises of WoltLab Suite.
