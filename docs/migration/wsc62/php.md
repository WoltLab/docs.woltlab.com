# Migrating from WoltLab Suite 6.2 - PHP

## Category List Pages

The legacy category list pages based on `AbstractCategoryListPage` have been replaced by [Node Tree Views](../../php/api/node_tree_views.md).
The new implementation renders the categories using `CategoryNodeTreeView` and integrates with the [Interactions](../../php/api/interactions.md) system, which provides drag and drop sorting, an interaction context menu and inline quick interactions out of the box.

To migrate an existing category list page to the new infrastructure, the following steps are required.

### Extend `AbstractCategoryNodeTreeViewPage`

Change the parent class of the list page from `AbstractCategoryListPage` to `AbstractCategoryNodeTreeViewPage` and update the type declaration of `$objectTypeName` from untyped to `string`:

```php
class ArticleCategoryListPage extends AbstractCategoryNodeTreeViewPage
{
    public $activeMenuItem = 'wcf.acp.menu.link.article.category.list';

    public string $objectTypeName = 'com.woltlab.wcf.article.category';
}
```

The previously used template, action handlers and read methods are no longer required and can be removed.
`AbstractCategoryNodeTreeViewPage` uses the `categoryNodeTreeView` template and creates the matching `CategoryNodeTreeView` instance automatically.

### Implement `getEditControllerClass()` and `getAddControllerClass()`

Two new methods have been added to `ICategoryType`:

- `getEditControllerClass(): string` returns the class name of the controller used to edit categories of this type.
- `getAddControllerClass(): string` returns the class name of the controller used to add categories of this type.

Both methods must be implemented by every concrete category type so that the node tree view and the interactions can link to the correct edit and add forms.

```php
class ArticleCategoryType extends AbstractCategoryType
{
    #[\Override]
    public function getEditControllerClass(): string
    {
        return ArticleCategoryEditForm::class;
    }

    #[\Override]
    public function getAddControllerClass(): string
    {
        return ArticleCategoryAddForm::class;
    }
}
```

`AbstractCategoryType` provides empty default implementations to avoid breaking existing third-party category types. Categories of types that do not provide these methods will not be editable through the node tree view.

### Pre-Selecting the Parent Category in the Add Form

`CategoryAddFormBuilderForm` now reads the optional `parentCategoryID` GET parameter and pre-selects the corresponding parent category in the add form.
This is used by the “Add Child Category” interaction in the node tree view to allow users to directly create a child category for a given node.

Custom subclasses of `CategoryAddFormBuilderForm` do not require any changes for this to work.

## ACP Search Providers

The registration of ACP search providers has been overhauled ([WoltLab/WCF#6681](https://github.com/WoltLab/WCF/issues/6681)).

### Registering Providers Through an Event

Providers are now registered through the PSR-14 event `wcf\event\acp\search\provider\ProviderCollecting`.
See the [ACP search providers](../../package/acp-search-providers.md) page for the full API reference.
The `acpSearchProvider` package installation plugin and the underlying database object remain available but are deprecated and should no longer be used for new providers.

A bootstrap script registers an event listener that adds the provider instances:

```php
EventHandler::getInstance()->register(
    \wcf\event\acp\search\provider\ProviderCollecting::class,
    static function (\wcf\event\acp\search\provider\ProviderCollecting $event) {
        $event->register(
            'com.example.myProvider',
            new \example\system\search\acp\MyAcpSearchResultProvider()
        );
    }
);
```

The provider class must still implement `wcf\system\search\acp\IACPSearchResultProvider`.
The first argument of `register()` is the textual identifier that is also used as the language item key for the result group title (`wcf.acp.search.provider.{providerName}`).

Providers are sorted by their translated label; the explicit `showOrder` from the deprecated PIP is no longer evaluated for event-registered providers.

Providers that are still registered through the deprecated `acpSearchProvider` PIP continue to work and are merged with the event-registered providers, but they are shadowed by event-registered providers using the same `providerName`.

### Searching Through the RPC API

The legacy AJAX action `wcf\data\acp\search\provider\ACPSearchProviderAction::getSearchResultList()` has been replaced by the RPC endpoint `GET /core/acp/search`.

| Parameter | Type | Description |
|-----------|------|-------------|
| `query` | `non-empty-string` | The search query. |
| `provider` | `string` (optional) | Restrict the search to a single provider identified by its `providerName`. |

The response payload has the following shape:

```json
{
    "results": [
        {
            "title": "Translated provider title",
            "items": [
                { "title": "…", "link": "…", "subtitle": "…" }
            ]
        }
    ]
}
```

The endpoint requires the `admin.general.canUseAcp` permission.
Each registered provider remains responsible for enforcing its own admin permissions inside `IACPSearchResultProvider::search()`.
