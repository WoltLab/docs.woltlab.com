# Grid Views

Grid views are a generic solution for the creation of listings that are ubiquitous in the software.
The layout is hard-coded and renders entries in the form of a table.
In addition to rendering, the grid view also takes care of sorting, filtering and pagination, and ensure that a lot of boilerplating becomes obsolete.

The implementation essentially offers the following advantages:
1. A uniform appearance and usability for the user.
2. An easy way for developers to create their own grid views.
3. An easy way for developers to extend existing grid views using plugins.

## Usage

### AbstractGridView

Grid views obtain their data from a database object list and display it using a defined column configuration.

Example:

```php
<?php

namespace wcf\system\gridView\admin;

use wcf\data\DatabaseObjectList;
use wcf\system\gridView\AbstractGridView;
use wcf\system\gridView\GridViewColumn;
use wcf\system\gridView\renderer\ObjectIdColumnRenderer;

/**
 * @extends AbstractGridView<Example, ExampleList>
 */
final class ExampleGridView extends AbstractGridView
{
    public function __construct()
    {
        $this->addColumns([
            GridViewColumn::for('id')
                ->label('wcf.global.objectID')
                ->renderer(new ObjectIdColumnRenderer())
                ->sortable(),
            GridViewColumn::for('title')
                ->label('wcf.global.title')
                ->sortable()
                ->titleColumn()
        ]);

        $this->setDefaultSortField('title');
    }

    #[\Override]
    public function isAccessible(): bool
    {
        return true;
    }

    #[\Override]
    protected function createObjectList(): DatabaseObjectList
    {
        return new ExampleDatabaseObjectList();
    }
}
```

### AbstractGridViewPage

A grid view can be displayed on a page by inheriting from `AbstractGridViewPage`.

Example:

```php
<?php

namespace wcf\acp\page;

use wcf\page\AbstractGridViewPage;

/**
 * @extends AbstractGridViewPage<ExampleGridView>
 */
final class ExampleListPage extends AbstractGridViewPage
{
    #[\Override]
    protected function createGridView(): AbstractGridView
    {
        return new ExampleGridView();
    }
}
```

```smarty
{include file='header'}

<div class="section">
	{unsafe:$gridView->render()}
</div>

{include file='footer'}
```

## Columns

Columns can be created using the `GridViewColumn::for` method. This expects a unique string as a parameter, which is equivalent to the corresponding key in the data source.

The `label` method can be used to give the column a human readable label.

Example:

```php
final class FooGridView extends AbstractGridView
{
    public function __construct()
    {
        $this->addColumns([
            GridViewColumn::for('id')
                ->label('wcf.global.objectID'),
            GridViewColumn::for('name')
                ->label('wcf.global.name'),
        ]);
    }
}
```

### Renderer

Renderers can be applied to columns to format the output. A column can have multiple renderers. The renderers are applied in the order in which they were set.

```php
GridViewColumn::for('foo')
    ->renderer([
        new FooColumnRenderer(),
        new BarColumnRenderer(),
    ])
```

#### CategoryColumnRenderer

`CategoryColumnRenderer` can be set to columns that contain the ID of categories. This results in the name of the category being output.

#### CurrencyColumnRenderer

`CurrencyColumnRenderer` formats the content of a column as a currency. Expects the content of the column to be a decimal.

Example:

```php
GridViewColumn::for('foo')
    ->renderer(new CurrencyColumnRenderer('EUR'))
```

#### DefaultColumnRenderer

The `DefaultColumnRenderer` is implicitly applied to all columns unless one ore more renderers have been explicitly set.

#### EmailColumnRenderer

`EmailColumnRenderer` formats the content of the column as an email address.

#### FilesizeColumnRenderer

`FilesizeColumnRenderer` formats the content of the column as a file size.

#### IpAddressColumnRenderer

`IpAddressColumnRenderer` outputs the value by attempting to interpret it as IPv4 if possible, otherwise shows the IPv6 address.

#### LinkColumnRenderer

`LinkColumnRenderer` allows the setting of a link to a column.

Example:

```php
GridViewColumn::for('foo')
    ->renderer(new LinkColumnRenderer(FooEditForm::class))
```

#### NumberColumnRenderer

`NumberColumnRenderer` formats the content of a column as a number using `StringUtil::formatNumeric()`.

#### ObjectIdColumnRenderer

`ObjectIdColumnRenderer` formats the content of a column as an object id.

#### PhraseColumnRenderer

`PhraseColumnRenderer` attempts to evaluate the value as a phrase and outputs it as plain text otherwise.

#### TimeColumnRenderer

`TimeColumnRenderer` renders a unix timestamp into a human readable format.

#### TruncatedTextColumnRenderer

`TruncatedTextColumnRenderer` truncates the content of a column to a length of 80 characters (default value).

#### UserColumnRenderer

`UserColumnRenderer` formats the content of a column as a user.

#### UserLinkColumnRenderer

`UserLinkColumnRenderer` is a combination of the `UserColumnRenderer` and the `LinkColumnRenderer`.

Example:

```php
GridViewColumn::for('foo')
    ->renderer(new UserLinkColumnRenderer(FooEditForm::class))
```

### Custom Renderer

If necessary, you can define your own renderers:

```php
GridViewColumn::for('id')
    ->renderer([
        new class extends DefaultColumnRenderer {
            public function render(mixed $value, DatabaseObject $row): string
            {
                return 'foo: ' . $value;
            }
        },
    ]),
```

### Row Link

A row link applies a link to every column in the grid.

```php
final class FooGridView extends AbstractGridView
{
    public function __construct()
    {
        $this->addRowLink(new GridViewRowLink(FooEditForm::class));
    }
}
```

The constructor supports 3 optional parameters:
1. `string $controllerClass`: The controller to which the link should refer.
2. `array $parameters`: Additional parameters for the controller.
3. `string $cssClass`: CSS class for the link.

### Sorting

Columns can be marked as sortable so that the user has the option of sorting according to the content of the column.

```php
GridViewColumn::for('foo')
    ->sortable()
```

By default, sorting is based on the `id` of the column.
Optionally, you can specify the name of an alternative database column to be used for sorting instead:

```php
GridViewColumn::for('foo')
    ->sortable(sortByDatabaseColumn: "table_alias.columnName")
```

The default sorting can be defined after the column configuration has been defined:

```php
final class FooGridView extends AbstractGridView
{
    public function __construct()
    {
        GridViewColumn::for('title')
            ->sortable();
        
        $this->setDefaultSortField('title');
        $this->setDefaultSortOrder('ASC');
    }
}
```

### Filtering

Filters can be defined for columns so that the user has the option to filter by the content of a column.

```php
GridViewColumn::for('foo')
    ->filter(new FooFilter())
```

#### BooleanFilter

`BooleanFilter` is a filter for columns that contain boolean values (`1` or `0`).

#### CategoryFilter

`CategoryFilter` is a filter for columns that contain category ids.

```php
GridViewColumn::for('categoryID')
    ->filter(new CategoryFilter((new CategoryNodeTree('identifier'))->getIterator()))
```

#### DateFilter

`DateFilter` is a filter for columns that contain unix timestamps.

#### I18nTextFilter

`I18nTextFilter` is a filter for text columns that are using i18n phrases.

#### IpAddressFilter

`IpAddressFilter` is a filter for columns that contain IPv6 addresses, allowing the user to enter addresses in the IPv4 format too.

#### NumericFilter

`NumericFilter` is a filter for columns that contain numeric values.

#### ObjectIdFilter

`ObjectIdFilter` is a filter for columns that contain object ids.

#### SelectFilter

`SelectFilter` allows a column to be filtered on the basis of a select dropdown.

```php
GridViewColumn::for('foo')
    ->filter(new SelectFilter([
        1 => 'value 1',
        0 => 'value 0',
    ]));
```

#### TextFilter

`TextFilter` is a filter for text columns.

#### TimeFilter

`TimeFilter` is a filter for columns that contain unix timestamps.
In contrast to `DateFilter`, this filter also allows filtering by a specific time.

#### UserFilter

`UserFilter` is a filter for columns that contain user ids.

## Events

Existing grid views can be modified using events.

Example of adding an additional column:

```php
$eventHandler->register(
    \wcf\event\gridView\admin\UserRankGridViewInitialized::class,
    static function (\wcf\event\gridView\admin\UserRankGridViewInitialized $event) {
         $event->gridView->addColumnBefore(
            GridViewColumn::for('hideTitle')
                ->label('hideTitle')
                ->renderer(new NumberColumnRenderer())
                ->sortable(),
            'requiredPoints'
        );
    }
);
```

## Interactions

Interaction providers can be specified using the methods `setInteractionProvider()` and `setBulkInteractionProvider()` (for bulk interactions). 

Example: 

```php
final class ExampleGridView extends AbstractGridView
{
    public function __construct()
    {
        ...

        $this->setInteractionProvider(new ExampleInteractions());
        $this->setBulkInteractionProvider(new ExampleBulkInteractions());
    }
}
```
