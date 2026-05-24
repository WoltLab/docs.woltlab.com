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
