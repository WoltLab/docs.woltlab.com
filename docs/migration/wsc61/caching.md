# Migrating from WoltLab Suite 6.1 - Tolerant and Eager Caching

In the upcoming version of WoltLab Suite, the caching system has been reworked to provide a more flexible and efficient way to cache data.

To solve the problems:

- Resetting a cache causes the next request that needs this cache to trigger a synchronous rebuild.
- Non-critical caches can sometimes be expensive to generate, causing dips in response times.
- The same rebuild can take place simultaneously by concurrent requests.

## General Guidelines

- MUST NOT rely on any (runtime) cache
- Return a `CacheData` object instead of an `array`
- Optional, parameterized caches with state
    - Use `readonly` properties in the constructor
    ```PHP
    final class FooCache extends AbstractTolerantCache {
        public function __construct(
            public readonly int $categoryID
        ) {}
        // Additional methods …
    }
    ```

## Eager Caches

The eager cache has no lifetime and must be updated manually by the developer if the data changes.
`(new FooEagerCache())->rebuild()`

#### Example

```PHP
/**
* @extends AbstractEagerCache<\stdClass>
*/
final class FooEagerCache extends AbstractEagerCache
{
    public function __construct(
      public readonly int $categoryID,
    ) {}
    
    #[\Override]
    protected function getCacheData(): \stdClass
    {
        // Load cache data from database
        return new \stdClass();
    }
}
```

The parameter `$categoryID` is certainly not required.
It can also be omitted.

This cache can be used as follows

```PHP
$cache = (new FooEagerCache(1))->getCache();
// without a state
$cache = (new FooEagerCache())->getCache();
```

## Tolerant Caches

The tolerant cache is a special cache that can return outdated content.
This must not cause any problems at runtime.

The cache is updated by a background job when the lifetime expires or by a [probabilistic early expiration](https://en.wikipedia.org/wiki/Cache_stampede#Probabilistic_early_expiration).

#### Example

```PHP
/**
* @extends AbstractTolerantCache<\stdClass>
*/
final class FooAsyncCache extends AbstractTolerantCache
{
    #[\Override]
    public function getLifetime(): int
    {
        return 6000;
    }
    
    #[\Override]
    protected function rebuildCacheData(): \stdClass
    {
        // Load cache data from database
        return new \stdClass();
    }
}
```

This cache can be used as follows

```PHP
$cache = (new FooAsyncCache())->getCache();
// with a state
$cache = (new FooAsyncCache($parameterOne, $parameterTwo, …))->getCache();
```
