# Migrating from WoltLab Suite 6.1 - Third Party Libraries

## Symfony PHP Polyfills

The Symfony Polyfill for PHP 8.5 was added.

Refer to the documentation within the [symfony/polyfill](https://github.com/symfony/polyfill/) repository for details.

## Valinor

The Valinor library that is being used to validate request parameters has been updated from version 1.17.0 to 2.1.1.

This upgrade contains breaking changes that affect the error handling in particular.
If you do not handle Valinor’s errors directly then you’re most likely not affected by this.

Refer to Valinor’s [upgrade documentation](https://valinor.cuyz.io/2.1/project/upgrading/#upgrade-from-1x-to-2x) for details.

## scssphp

scssphp was updated from version 1.13.0 to 2.0.1.

This is a complete rewrite of the scssphp library aimed at better compliance with the specifications.
For the most part you should not be affected unless you are using SCSS constructs that previously had undefined behavior or otherwise violated the specs.

Unfortunately, this version has a severe performance regression that increases build times by as much as 3x.
Despite this drawback we had to upgrade in order to add support for PHP 8.4.

Refer to the release notes for [scssphp/scssphp](https://github.com/scssphp/scssphp/releases/tag/v2.0.0) for a list of all (breaking) changes.
