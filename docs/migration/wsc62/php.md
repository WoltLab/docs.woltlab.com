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

## PSR-7 Responses in Pages and Forms

Since WoltLab Suite 5.5 a page or form could abort request processing in two different ways: by returning a PSR-7 `ResponseInterface` from one of its lifecycle methods, or by emitting the response manually using `HeaderUtil::redirect()` followed by `exit`.
Both approaches are problematic.
Returning a response forces every intermediate override to forward the return value of its parent call, and a manually emitted redirect bypasses the PSR-15 middleware pipeline entirely, causing it to miss the cache-control headers that are applied to every regular response.

WoltLab Suite 6.3 therefore standardizes on `setPsr7Response()`, which exists since WoltLab Suite 5.5, as the only supported way to define the response of a page or a form.

### Changed Return Types

The following methods are now declared as `@return void` and no longer accept a `ResponseInterface` as their return value:

| Interface | Methods |
|-----------|---------|
| `wcf\page\IPage` | `readParameters()`, `readData()`, `show()` |
| `wcf\form\IForm` | `readFormParameters()`, `validate()`, `save()` |

`IPage::__run()` is not affected and continues to be declared as `@return void|ResponseInterface`.

Returning a response from one of the methods listed above keeps working at runtime, because `AbstractPage::__run()` and `AbstractForm::submit()` still pass the return value through `maybeSetPsr7Response()`.
The behavior is retained for backwards compatibility only and should not be relied upon for new code; static analysis will report the return value as unused.

### Migrating a Returned Response

Previously:

```php
#[\Override]
public function readParameters()
{
    parent::readParameters();

    if ($this->shouldRedirect()) {
        return new RedirectResponse(
            LinkHandler::getInstance()->getControllerLink(ExampleListPage::class)
        );
    }

    // …
}
```

Now:

```php
#[\Override]
public function readParameters()
{
    parent::readParameters();

    if ($this->shouldRedirect()) {
        $this->setPsr7Response(new RedirectResponse(
            LinkHandler::getInstance()->getControllerLink(ExampleListPage::class),
            303
        ));

        return;
    }

    // …
}
```

`setPsr7Response()` only stores the response, it does not abort the current method.
The explicit `return` is required whenever the call is not the last statement of the method.

Redirects that follow a successful form submission should use the status code 303 (See Other) instead of relying on the default of 302, so that the browser performs the follow-up request using `GET`.

### Migrating `HeaderUtil::redirect()`

Redirects that were emitted manually are migrated the same way:

```php
// previously
HeaderUtil::redirect(LinkHandler::getInstance()->getControllerLink(ExampleListPage::class));

exit;

// now
$this->setPsr7Response(new RedirectResponse(
    LinkHandler::getInstance()->getControllerLink(ExampleListPage::class),
    303
));

return;
```

Take care when replacing an `exit`: it terminated the whole request, whereas the `return` only leaves the current method.
Every caller up the chain must be able to cope with the aborted method and must not continue as if the method had completed successfully.
Use the `hasPsr7Response()` method to guard the remaining logic:

```php
#[\Override]
public function readParameters()
{
    parent::readParameters();

    $this->readObjectType();
    if ($this->hasPsr7Response()) {
        return;
    }

    $this->readObjectList();
}
```

### `AbstractForm::readData()`

`AbstractForm::readData()` now aborts after `submit()` if a response was set, meaning that `AbstractPage::readData()` is no longer called in that case:

```php
#[\Override]
public function readData()
{
    if ($_POST !== [] || $_FILES !== []) {
        $this->submit();
        if ($this->hasPsr7Response()) {
            return;
        }
    }

    parent::readData();
}
```

Forms that set a response inside `save()` and override `readData()` must add the same guard, because the code following the `parent::readData()` call was previously unreachable due to the `exit` in `save()`:

```php
#[\Override]
public function readData()
{
    parent::readData();

    if ($this->hasPsr7Response()) {
        return;
    }

    $this->readOptionTree();
}
```

As a consequence of the early return, the `readData` event is no longer fired if a response was set during the submission of the form.
Event listeners that relied on being called in this situation must be moved to the `saved` event.

## Access Checks for Views

[Grid views](../../php/api/grid_views.md) and [list views](../../php/api/list_views.md) are exposed through RPC endpoints that instantiate the view by its class name.
The only protection against unauthorized access is `isAccessible()`, whose default implementation grants access to everybody.
Views that did not override this method were therefore accessible to any user, regardless of the permissions of the page they were embedded in.

`isAccessible()` must now be implemented by every view:

```php
final class ExampleGridView extends AbstractGridView
{
    #[\Override]
    public function isAccessible(): bool
    {
        return WCF::getSession()->hasPermission('admin.example.canManageExample');
    }
}
```

The returned value should match the requirements of the page that renders the view, including the checks for the required modules.

`AbstractGridView::isAccessible()` and `AbstractListView::isAccessible()` are deprecated and will become abstract with WoltLab Suite 6.4.
Until then they keep returning `true`, but throw a `BadMethodCallException` if both the debug mode and the developer tools are enabled, in order to surface views that have not been updated yet.

!!! warning "Do not call `parent::isAccessible()` when the parent class does not implement it itself, the deprecated default implementation throws in debug mode. Views that extend another concrete view, for example `ArticleListView`, may continue to delegate to their parent."

## Sitemaps

The registration of sitemap objects has been overhauled.

### Registering Sitemap Objects Through an Event

Sitemap objects are now registered through the PSR-14 event `wcf\event\sitemap\SitemapObjectCollecting`.
See the [sitemaps](../../php/api/sitemaps.md) page for the full API reference.
The object type definition `com.woltlab.wcf.sitemap.object` remains available but is deprecated and should no longer be used for new sitemap objects.

A bootstrap script registers an event listener that adds the sitemap objects:

```php
EventHandler::getInstance()->register(
    \wcf\event\sitemap\SitemapObjectCollecting::class,
    static function (\wcf\event\sitemap\SitemapObjectCollecting $event) {
        $event->register(new \wcf\system\sitemap\object\RegisteredSitemapObject(
            'com.example.plugin.sitemap.object.foo',
            new \example\system\sitemap\object\FooSitemapObject(),
            rebuildTime: 259200,
            packageID: $packageID,
        ));
    }
);
```

The processor class must still implement `wcf\system\sitemap\object\ISitemapObjectObjectType`.
The first argument of the `RegisteredSitemapObject` constructor is the name of the sitemap object that was previously the name of the object type.
It is used as the file name of the generated sitemap, as the key of the settings made by the administrator and as the suffix of the language item `wcf.acp.sitemap.objectType.{objectName}`.
Keeping the previous object type name therefore preserves the settings of existing installations.

Sitemap objects that are still registered through the deprecated object type definition continue to work and are merged with the objects registered through the event, but they are shadowed by an object registered through the event using the same name.

### Sitemap List in the ACP

`wcf\acp\page\SitemapListPage` has been converted into a grid view page and now renders `wcf\system\gridView\admin\SitemapGridView`.
The template events `headColumns`, `columns` and `contentFooterNavigation` of the template `sitemapList` have been removed.
Additional columns are added through the PSR-14 event `wcf\event\gridView\admin\SitemapGridViewInitialized` instead.
