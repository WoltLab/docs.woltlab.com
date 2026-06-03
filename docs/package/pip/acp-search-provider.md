# ACP Search Provider Package Installation Plugin

!!! warning "Deprecated in WoltLab Suite 6.3"
    Use the [ACP search providers](../acp-search-providers.md) API based on the PSR-14 event `wcf\event\acp\search\provider\ProviderCollecting` to register ACP search providers.
    See the [migration guide](../../migration/wsc62/php.md#acp-search-providers) for details.

Registers data provider for the admin panel search.

## Components

Each acp search result provider is described as an `<acpsearchprovider>` element with the mandatory attribute `name`.

### `<classname>`

The name of the class providing the search results,
the class has to implement the `wcf\system\search\acp\IACPSearchResultProvider` interface.

### `<showorder>`

<span class="label label-info">Optional</span>

Determines at which position of the search result list the provided results are shown.

## Example

{jinja{ codebox(
  title="acpSearchProvider.xml",
  language="xml",
  filepath="package/pip/acpSearchProvider.xml"
) }}
