# Migrating from WoltLab Suite 6.1 - Tolerant and Eager Caching

In the upcoming version of WoltLab Suite, the caching system has been reworked to provide a more flexible and efficient way to cache data.

The previous implementation had three distinct shortcomings:

- Resetting a cache causes the next request that needs this cache to trigger a synchronous rebuild.
- Non-critical caches can sometimes be expensive to generate, causing dips in response times.
- The same rebuild can take place simultaneously by concurrent requests.

The new caching systems solves this moving the any request for a cache rebuild into the request that triggered the cache invalidation.

This will add the burden to rebuild these caches onto the current request which is usually an (in comparison) expensive request anyway.
The idea behind this is that adding a few miliseconds to a request that already takes half a second makes no difference to the user.
On the other hand, accessing a page and suddenly have a significantly longer loading time is unexpected to the visitor.

Performing a full cache reset will still have the same latency impact as before.

## General Guidelines

- MUST NOT rely on any (runtime) cache.
- Return a `CacheData` object instead of an `array`.
- Parametrized caches are supported through `readonly` properties in the constructor.
  ```php
  namespace wcf\system\cache\tolerant;

  final class FooCache extends AbstractTolerantCache {
      public function __construct(
          public readonly int $categoryID
      ) {}
      // Additional methods …
  }
  ```

## Eager Caches

The eager cache has no lifetime and must be rebuild manually by the developer if the data changes.
An eager cache is guaranteed to be always present, either by fetching the cached data or by rebuilding it synchronously.

```php
(new FooCache())->rebuild()
```

#### Example

```php
namespace wcf\system\cache\eager;

use wcf\system\cache\eager\data\FooCacheData;

/**
* @extends AbstractEagerCache<FooCacheData>
*/
final class FooCache extends AbstractEagerCache
{
    public function __construct(
      public readonly bool $snafucated,
    ) {}

    #[\Override]
    protected function getCacheData(): FooCacheData
    {
        // Load and return the data here …
    }
}
```

Parameters for a stateful cache are passed through the constructor and are required to be marked as `readonly`.

```php
$cache = (new FooCache(true))->getCache();
```

## Tolerant Caches

A tolerant cache is similar to an eager cache but has a maximum lifetime after which it is queued up for a rebuild.
Similar to the eager cache, it is also guaranteed to exist when queried, serving either cached data or rebuilding it synchronously.

The main difference is that the tolerant cache is permitted to serve stale content. For example, a cache is set a lifetime of 300 seconds but querying it 307 seconds after its creation may still return the old data.
The cache content will be refreshed eventually, but callees must not expect the data to be of a precise age.

The cache is updated by a background job when the lifetime expires or by a [probabilistic early expiration](https://en.wikipedia.org/wiki/Cache_stampede#Probabilistic_early_expiration).
The early expiration will randomly queue a tolerant cache for a rebuild before it exceeds its maximum lifetime.
The odds of an early rebuild increases significantly the less time is remaining, making it very likely that a tolerant does not become stale.

#### Example

```php
namespace wcf\system\cache\tolerant;

use wcf\system\cache\tolerant\data\BarCacheData;

/**
* @extends AbstractTolerantCache<BarCacheData>
*/
final class BarCache extends AbstractTolerantCache
{
    #[\Override]
    public function getLifetime(): int
    {
        // 3,600 seconds = 1 hour
        return 3_600;
    }

    #[\Override]
    protected function rebuildCacheData(): BarCacheData
    {
        // Load and return the data here …
    }
}
```

This cache can be used as follows:

```php
$cache = (new BarCache())->getCache();
```
