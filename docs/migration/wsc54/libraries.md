# Migrating from WSC 5.4 - Third Party Libraries

## scssphp

scssphp was updated from version 1.4 to 1.6.

If you interact with scssphp only by deploying `.scss` files, then you should not experience any breaking changes, except when the improved SCSS compatibility interprets your SCSS code differently.

If you happen to directly use scssphp in your PHP code, you should be aware that scssphp deprecated the use of the `compile()` method, non-UTF-8 processing and also adjusted the handling of pure PHP values for variable handling.

Refer to [WoltLab/WCF#4345](https://github.com/WoltLab/WCF/pull/4345) and the [scssphp releases](https://github.com/scssphp/scssphp/releases) for details.
