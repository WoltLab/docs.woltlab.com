# List Views

List views are a generic solution for the creation of listings that are ubiquitous in the software.
In contrast to [grid views](grid_views.md), list views do not specify a particular layout.
The developer must specify a custom template that takes care of the rendering of the entries.
A list view takes care of sorting, filtering and pagination, and ensure that a lot of boilerplating becomes obsolete.

The implementation essentially offers the following advantages:
1. A uniform appearance and usability for the user.
2. An easy way for developers to create their own list views.
3. An easy way for developers to extend existing list views using plugins.

## Usage

### AbstractListView

List views obtain their data from a database object list and display it using custom template.

Example:

```php
<?php

namespace wcf\system\listView\user;

use wcf\data\DatabaseObjectList;
use wcf\system\listView\AbstractListView;
use wcf\system\WCF;

/**
 * @extends AbstractListView<Example, ExampleList>
 */
class ExampleListView extends AbstractListView
{
    #[\Override]
    protected function createObjectList(): DatabaseObjectList
    {
        return new ExampleList();
    }

    #[\Override]
    public function isAccessible(): bool
    {
        return true;
    }

    #[\Override]
    public function renderItems(): string
    {
        return WCF::getTPL()->render('wcf', 'exampleListItems', ['view' => $this]);
    }
}
```

Example `exampleListItems.tpl`:

```smarty
{foreach from=$view->getItems() item='item'}
	<div class="listView__item" data-object-id="{$item->getObjectID()}">
		<h2>{$item->getTitle()}</h2>
	</div>
{/foreach}
```

### AbstractListViewPage

A list view can be displayed on a page by inheriting from `AbstractListViewPage`.

Example:

```php
<?php

namespace wcf\page;

use wcf\system\listView\user\ArticleListView;

/**
 * @extends AbstractListViewPage<ExampleListView>
 */
class ExampleListPage extends AbstractListViewPage
{
    #[\Override]
    protected function createListView(): ExampleListView
    {
        return new ExampleListView();
    }
}
```

```smarty
{include file='header'}

<div class="section">
	{unsafe:$listView->render()}
</div>

{include file='footer'}
```

## Sorting

The `addAvailableSortFields` method allows you to define columns that the user can use to sort the list.
The columns must exist in the linked database object list.

```php
class ExampleListView extends AbstractListView
{
    public function __construct() {
        $this->addAvailableSortFields([
            new ListViewSortField('time', 'wcf.global.date'),
            new ListViewSortField('title', 'wcf.global.title'),
        ]);
    }
}
```

By default, sorting is based on the `id` (first parameter) of the specified sort field.
Optionally, you can specify the name of an alternative database column to be used for sorting instead:

```php
new ListViewSortField('title', 'wcf.global.title', 'table_alias.columnName'),
```

The default sorting can be defined after the configuration of the sort fields has been defined:

```php
class ExampleListView extends AbstractListView
{
    public function __construct()
    {
        $this->addAvailableSortFields([
            new ListViewSortField('time', 'wcf.global.date'),
            new ListViewSortField('title', 'wcf.global.title'),
        ]);
        
        $this->setSortField('title');
        $this->setSortOrder('ASC');
    }
}
```

## Filtering

Filters can be defined for columns so that the user has the option to filter by the content of a column.

```php
class ExampleListView extends AbstractListView
{
    public function __construct()
    {
        $this->addAvailableFilters([
            new TextFilter('title', 'wcf.global.title'),
        ]);
    }
}
```

### BooleanFilter

`BooleanFilter` is a filter for columns that contain boolean values (`1` or `0`).

### CategoryFilter

`CategoryFilter` is a filter for columns that contain category ids.

```php
class ExampleListView extends AbstractListView
{
    public function __construct()
    {
        $this->addAvailableFilters([
            new CategoryFilter((new CategoryNodeTree('identifier'))->getIterator()), 'categoryID'),
        ]);
    }
}
```

### DateFilter

`DateFilter` is a filter for columns that contain unix timestamps.

### LabelFilter

`LabelFilter` allows to filter a list view by labels.

```php
class ExampleListView extends AbstractListView
{
    public function __construct()
    {
        $objectTypeID = ObjectTypeCache::getInstance()->getObjectTypeIDByName(
            'com.woltlab.wcf.label.object',
            'example.identifier'
        );

        foreach (ExampleCategory::getAccessibleLabelGroups('canViewLabel') as $groupID => $categoryIDs) {
            $this->addAvailableFilters([
                new LabelFilter(
                    LabelHandler::getInstance()->getLabelGroup($groupID),
                    $objectTypeID,
                    'labelIDs' . $groupID
                )
            ]);
        }
    }
}
```

### NumericFilter

`NumericFilter` is a filter for columns that contain numeric values.

### SelectFilter

`SelectFilter` allows a column to be filtered on the basis of a select dropdown.

```php
class ExampleListView extends AbstractListView
{
    public function __construct()
    {
        $this->addAvailableFilters([
            new SelectFilter([
                1 => 'value 1',
                0 => 'value 0',
            ], 'id', 'language.item'),
        ]);
    }
}
```

### TextFilter

`TextFilter` is a filter for text columns.

### TimeFilter

`TimeFilter` is a filter for columns that contain unix timestamps.
In contrast to `DateFilter`, this filter also allows filtering by a specific time.

### UserFilter

`UserFilter` is a filter for columns that contain user ids.

## Customization

### Number of Items

By default, list views use a pagination that shows 20 items per page. You can set a custom number of items per page:

```php
class ExampleListView extends AbstractListView
{
    public function __construct()
    {
        $this->setItemsPerPage(50);
    }
}
```

There are some cases where only a list with a fixed number of items is required, for example, showcasing the 10 latests items.

```php
class ExampleListView extends AbstractListView
{
    public function __construct()
    {
        $this->fixedNumberOfItems(10);
    }
}
```

### CSS Class Names

Optionally, a CSS class can be set on the surrounding HTML element:

```php
class ExampleListView extends AbstractListView
{
    public function __construct()
    {
        $this->setCssClassName('exampleList');
    }
}
```

### Additional Parameters

A list view can be provided with additional parameters, e.g. to filter them by a specific category:

```php
class ExampleListView extends AbstractListView
{
    public function __construct(public readonly int $categoryID)
    {
        parent::__construct();
    }

    #[\Override]
    protected function createObjectList(): DatabaseObjectList
    {
        $list = new ExampleList();
        $list->getConditionBuilder()->add('categoryID = ?', [$this->categoryID]);

        return $list;
    }

    #[\Override]
    public function getParameters(): array
    {
        return ['categoryID' => $this->categoryID];
    }
}
```

```php
class ExampleListPage extends AbstractListViewPage
{
    public int $categoryID = 0;

    #[\Override]
    public function readParameters()
    {
        if (isset($_REQUEST['categoryID'])) {
            $this->categoryID = \intval($_REQUEST['categoryID']);
        }

        parent::readParameters();
    }

    #[\Override]
    protected function createListView(): AbstractListView
    {
        return new ExampleListView($this->categoryID);
    }

    #[\Override]
    protected function getBaseUrlParameters(): array
    {
        return [
            'categoryID' => $this->categoryID,
        ];
    }
}
```

## Events

Existing list views can be modified using events.

Example of adding an additional sort field:

```php
$eventHandler->register(
    \wcf\event\listView\user\ArticleListViewInitialized::class,
    static function (\wcf\event\listView\user\ArticleListViewInitialized $event) {
         $event->listView->addAvailableSortField(
            new ListViewSortField('example', 'wcf.global.example'),
        );
    }
);
```

## Interactions

Interaction providers can be specified using the methods `setInteractionProvider()` and `setBulkInteractionProvider()` (for bulk interactions). 

Example: 

```php
final class ExampleGridView extends AbstractListView
{
    public function __construct()
    {
        ...

        $this->setInteractionProvider(new ExampleInteractions());
        $this->setBulkInteractionProvider(new ExampleBulkInteractions());
    }
}
```

The following template code must be included in the template for rendering of the items so that the buttons for the interactions are displayed.

```smarty
{if $view->hasBulkInteractions()}
    <label class="listView__selectItem__label jsTooltip" title="{lang}wcf.clipboard.item.mark{/lang}">
        <input type="checkbox" class="listView__selectItem" aria-label="{lang}wcf.clipboard.item.mark{/lang}">
    </label>
{/if}

{unsafe:$view->renderInteractionContextMenuButton($article)}
```
