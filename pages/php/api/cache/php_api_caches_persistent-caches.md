---
title: Persistent Caches
sidebar: sidebar
permalink: php_api_caches_persistent-caches.html
folder: php/api/cache
parent: php_api_caches
---

Relational databases are designed around the principle of normalized data that
is organized across clearly separated tables with defined releations between
data rows. While this enables you to quickly access and modify individual rows
and columns, it can create the problem that re-assembling this data into a more
complex structure can be quite expensive.

For example, the user group permissions are stored for each user group and each
permissions separately, but in order to be applied, they need to be fetched and
the cumulative values across all user groups of an user have to be calculated.
These repetitive tasks on barely ever changing data make them an excellent
target for caching, where all sub-sequent requests are accelerated because they
no longer have to perform the same expensive calculations every time.

It is easy to get lost in the realm of caching, especially when it comes to the
decision if you should use a cache or not. When in doubt, you should opt to not
use them, because they also come at a hidden cost that cannot be expressed through
simple SQL query counts. If you haven't already, it is recommended that you read
the [introduction article on caching][php_api_caches] first, it provides a bit
of background on caches and examples that should help you in your decision.

## `AbstractCacheBuilder`

Every cache builder should derive from the base class [AbstractCacheBuilder](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/cache/builder/AbstractCacheBuilder.class.php)
that already implements the mandatory interface [ICacheBuilder](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/cache/builder/ICacheBuilder.class.php).

```php
<?php
namespace wcf\system\cache\builder;

class ExampleCacheBuilder extends AbstractCacheBuilder {
  // 3600 = 1hr
  protected $maxLifetime = 3600;

  public function rebuild(array $parameters) {
    $data = [];

    // fetch and process your data and assign it to `$data`

    return $data;
  }
}
```

Reading data from your cache builder is quite simple and follows a consistent
pattern. The callee only needs to know the name of the cache builder, which
parameters it requires and how the returned data looks like. It does not need
to know how the data is retrieve, where it was stored, nor if it had to be
rebuild due to the maximum lifetime.

```php
<?php
use wcf\system\cache\builder\ExampleCacheBuilder;

$data = ExampleCacheBuilder::getInstance()->getData($parameters);
```

### `getData(array $parameters = [], string $arrayIndex = ''): array`

Retrieves the data from the cache builder, the `$parameters` array is automatically
sorted to allow sub-sequent requests for the same parameters to be recognized,
even if their parameters are mixed. For example, `getData([1, 2])` and `getData([2, 1])`
will have the same exact result.

The optional `$arrayIndex` will instruct the cache builder to retrieve the data
and examine if the returned data is an array that has the index `$arrayIndex`.
If it is set, the potion below this index is returned instead.

### `getMaxLifetime(): int`

Returns the maximum lifetime of a cache in seconds. It can be controlled through
the `protected $maxLifetime` property which defaults to `0`. Any cache that has
a lifetime greater than 0 is automatically discarded when exceeding this age,
otherwise it will remain forever until it is explicitly removed or invalidated.

### `reset(array $parameters = []): void`

Invalidates a cache, the `$parameters` array will again be ordered using the same
rules that are applied for `getData()`.

### `rebuild(array $parameters): array`

_This method is protected._

This is the only method that a cache builder deriving from `AbstractCacheBuilder`
has to implement and it will be invoked whenever the cache is required to be
rebuild for whatever reason.

{% include links.html %}
