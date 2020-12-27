# Migrating from WSC 5.2 - Third Party Libraries

## SCSS Compiler

WoltLab Suite Core 5.3 upgrades the bundled SCSS compiler from `leafo/scssphp` 0.7.x to `scssphp/scssphp` 1.1.x.
With the updated composer package name the SCSS compiler also received updated namespaces.
WoltLab Suite Core adds a compatibility layer that maps the old namespace to the new namespace.
The classes themselves appear to be drop-in compatible.
Exceptions cannot be mapped using this compatibility layer, any `catch` blocks catching a specific Exception within the `Leafo` namespace will need to be adjusted.

More details can be found in the [Pull Request WoltLab/WCF#3415](https://github.com/WoltLab/WCF/pull/3415).

## Guzzle

WoltLab Suite Core 5.3 ships with a bundled version of [Guzzle 6](http://docs.guzzlephp.org/en/6.5/).
Going forward using Guzzle is the recommended way to perform HTTP requests.
The `\wcf\util\HTTPRequest` class should no longer be used and transparently uses Guzzle under the hood.

Use `\wcf\system\io\HttpFactory` to retrieve a correctly configured `GuzzleHttp\ClientInterface`.

Please note that it is recommended to explicitely specify a `sink` when making requests, due to a PHP / Guzzle bug.
Have a [look at the implementation in WoltLab/WCF](https://github.com/WoltLab/WCF/blob/ce163806c468763f6e3b04e4bf7318c6f8035737/wcfsetup/install/files/lib/util/HTTPRequest.class.php#L194-L195) for an example.
