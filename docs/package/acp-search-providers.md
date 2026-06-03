# ACP Search Providers

The API for the ACP search providers allows you to add your own provider for the admin panel search.

Since WoltLab Suite 6.3 you can attach an event listener to the `wcf\event\acp\search\provider\ProviderCollecting` event inside a [bootstrap script](../package/bootstrap-scripts.md) to lazily register your ACP search providers.

The `register` method of the event expects the following parameters:

| Name | Type | Description |
|------|------|-------------|
| `$providerName` | string | Identifier of the provider; must be unique. Also used as the language item key for the result group title (`wcf.acp.search.provider.{$providerName}`). |
| `$provider` | IACPSearchResultProvider | Provider instance that implements `wcf\system\search\acp\IACPSearchResultProvider`. |

Providers are sorted by their translated label.

The provider class must implement `wcf\system\search\acp\IACPSearchResultProvider` and return a list of `wcf\system\search\acp\ACPSearchResult` instances from its `search()` method. The provider itself is responsible for enforcing the relevant admin permissions.

Example:

```php
<?php

use wcf\event\acp\search\provider\ProviderCollecting;
use wcf\system\event\EventHandler;
use wcf\system\search\acp\ACPSearchResult;
use wcf\system\search\acp\IACPSearchResultProvider;
use wcf\system\WCF;

final class FooACPSearchResultProvider implements IACPSearchResultProvider
{
    #[\Override]
    public function search(string $query)
    {
        if (!WCF::getSession()->hasPermission('admin.foo.canManageFoo')) {
            return [];
        }

        $results = [];
        // collect matching items and append:
        // $results[] = new ACPSearchResult($title, $link, $subtitle);

        return $results;
    }
}

return static function (): void {
    EventHandler::getInstance()->register(ProviderCollecting::class, static function (ProviderCollecting $event) {
        $event->register(
            'com.woltlab.foo.bar',
            new FooACPSearchResultProvider()
        );
    });
};
```

The language item `wcf.acp.search.provider.com.woltlab.foo.bar` must be provided through the [language package installation plugin](pip/language.md) so that the provider appears in the search type dropdown and as the heading of its result group.

The legacy [`acpSearchProvider` package installation plugin](pip/acp-search-provider.md) and the associated database object `wcf\data\acp\search\provider\ACPSearchProvider` are deprecated as of WoltLab Suite 6.3. Providers that are still registered through the deprecated PIP keep working but are shadowed by event-registered providers using the same `providerName`.
