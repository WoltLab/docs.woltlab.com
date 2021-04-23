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