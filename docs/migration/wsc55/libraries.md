# Migrating from WoltLab Suite 5.5 - Third Party Libraries

## Symfony PHP Polyfills

The Symfony Polyfills for 7.3, 7.4, and 8.0 were removed, as the minimum PHP version was increased to PHP 8.1.
The Polyfill for PHP 8.2 was added.

Refer to the documentation within the [symfony/polyfill](https://github.com/symfony/polyfill/) repository for details.

## IDNA Handling

The true/punycode and pear/net_idna2 dependencies were removed, because of a lack of upstream maintenance.
Instead the [`idn_to_ascii`](https://www.php.net/manual/en/function.idn-to-ascii.php) function should be used.
A polyfill for environments without the intl extension is provided.

## Laminas Diactoros

Diactoros was updated from version 2.4 to 2.10.
