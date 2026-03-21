# Search

The search system allows users to find content across different types of objects through the full-text search.
To make your objects searchable, you need to implement a search provider, a search result object and register an object type.

## Search Provider

The search provider defines how objects are queried, which database table and columns to use, and how access permissions are checked.
Create a class that extends `wcf\system\search\AbstractSearchProvider`:

{jinja{ codebox(
  title="files/lib/system/search/FooSearch.class.php",
  language="php",
  filepath="php/api/search/FooSearch.class.php"
) }}

### Required Methods

The following methods must be implemented in your search provider:

#### `cacheObjects(array $objectIDs, ?array $additionalData = null): void`

Bulk-loads all search result objects for the given IDs.
This is called once with all matching IDs before `getObject()` is called for individual results.

#### `getObject(int $objectID): ?ISearchResultObject`

Returns a single cached search result object by its ID, or `null` if the object was not found.

#### `getTableName(): string`

Returns the database table name that contains the searchable content.

#### `getIDFieldName(): string`

Returns the fully qualified column name of the primary key (e.g. `wcf1_foo.fooID`).

### Optional Methods

The `AbstractSearchProvider` base class provides default implementations for these methods.
Override them as needed.

#### `getSubjectFieldName(): string`

Returns the fully qualified column name of the subject/title field.
Defaults to `{tableName}.subject`.

#### `getUsernameFieldName(): string`

Returns the fully qualified column name of the author's username.
Defaults to `{tableName}.username`.

#### `getTimeFieldName(): string`

Returns the fully qualified column name of the creation timestamp.
Defaults to `{tableName}.time`.

#### `getConditionBuilder(array $parameters): ?PreparedStatementConditionBuilder`

Returns additional SQL conditions to filter the search results, for example to enforce access permissions or only return published content.
The `$parameters` array contains the provider-specific form parameters submitted by the user.
Return `null` if no additional conditions are needed.

#### `getJoins(): string`

Returns additional SQL `JOIN` clauses needed for the search query.
This is useful when the searchable content and its metadata (such as the author or timestamp) are stored in separate tables.

For example, the article system searches the `wcf1_article_content` table but needs to join `wcf1_article` for the author and timestamp:

```php
public function getJoins(): string
{
    return '
        INNER JOIN  wcf1_article
        ON          wcf1_article.articleID = ' . $this->getTableName() . '.articleID';
}
```

#### `isAccessible(): bool`

Returns whether the current user is allowed to use this search provider.
Defaults to `true`.

#### `getFormTemplateName(): string`

Returns the name of a template that provides additional form fields for this search provider on the search page, for example a category selector.
Return an empty string (the default) if no additional form fields are needed.

#### `assignVariables(): void`

Assigns template variables required by the form template returned by `getFormTemplateName()`.

#### `getAdditionalData(): ?array`

Returns additional data that should be stored with the search result and passed back to `getConditionBuilder()` when a cached search is revisited.

#### `getCustomIconName(): ?string`

Returns a custom icon name to display in the search results list.
Return `null` (the default) to use the default icon.

## Search Result Object

The search result object wraps your data model and provides the data needed to render a search result entry.
Create a decorator class that extends `wcf\data\DatabaseObjectDecorator` and implements `wcf\data\search\ISearchResultObject`:

{jinja{ codebox(
  title="files/lib/data/foo/SearchResultFoo.class.php",
  language="php",
  filepath="php/api/search/SearchResultFoo.class.php"
) }}

The `ISearchResultObject` interface requires the following methods:

| Method | Return Type | Description |
| ------ | ----------- | ----------- |
| `getUserProfile()` | `?UserProfile` | Returns the author's user profile. |
| `getSubject()` | `string` | Returns the title/subject of the object. |
| `getTime()` | `int` | Returns the creation timestamp. |
| `getLink($query = '')` | `string` | Returns the URL to the object. When `$query` is set, it should be included as a `highlight` parameter. |
| `getObjectTypeName()` | `string` | Returns the object type name (e.g. `com.example.foo`). |
| `getFormattedMessage()` | `string` | Returns the message text formatted for display in search results. Use `SearchResultTextParser` to truncate and highlight the text. |
| `getContainerTitle()` | `string` | Returns the title of the object's container (e.g. a category or forum). Return an empty string if there is no container. |
| `getContainerLink()` | `string` | Returns the URL of the object's container. Return an empty string if there is no container. |

You also need a corresponding list class that sets `SearchResultFoo` as the decorator:

{jinja{ codebox(
  title="files/lib/data/foo/SearchResultFooList.class.php",
  language="php",
  filepath="php/api/search/SearchResultFooList.class.php"
) }}

## Object Type Registration

Register your search provider as an object type with the definition `com.woltlab.wcf.searchableObjectType`:

```xml
<type>
	<name>com.example.foo</name>
	<definitionname>com.woltlab.wcf.searchableObjectType</definitionname>
	<classname>wcf\system\search\FooSearch</classname>
	<searchindex>wcf1_foo_search_index</searchindex>
</type>
```

The `searchindex` element specifies the name of the search index table.
If omitted, the system will automatically generate a table name based on the object type name.

## Language Item

You need to create a language item for the search type label that is displayed in the search form.
The language item follows the pattern `wcf.search.type.{objectTypeName}`:

```
wcf.search.type.com.example.foo = Foo
```

## Managing the Search Index

### Adding and Updating Entries

Whenever searchable content is created or updated, you must update the search index using `SearchIndexManager::getInstance()->set()`:

```php
use wcf\system\search\SearchIndexManager;

SearchIndexManager::getInstance()->set(
    'com.example.foo',    // object type name
    $foo->fooID,          // object ID
    $foo->message,        // message text (HTML)
    $foo->title,          // subject
    $foo->time,           // timestamp
    $foo->userID,         // author's user ID
    $foo->username,       // author's username
    $foo->languageID,     // language ID (or null)
    $foo->teaser          // optional: metadata (e.g. teaser text)
);
```

The `$message` parameter accepts HTML content.
The `SearchIndexManager` will automatically strip HTML tags before indexing.

### Deleting Entries

When objects are deleted, remove them from the search index:

```php
SearchIndexManager::getInstance()->delete('com.example.foo', [$foo->fooID]);
```

### Rebuilding the Index

If your content type has a rebuild data worker, reset and rebuild the search index in its `execute()` method:

```php
use wcf\system\search\SearchIndexManager;

// Reset the search index on the first iteration.
if (!$this->loopCount) {
    SearchIndexManager::getInstance()->reset('com.example.foo');
}

// Re-index each object.
foreach ($this->objectList as $foo) {
    SearchIndexManager::getInstance()->set(
        'com.example.foo',
        $foo->fooID,
        $foo->message,
        $foo->title,
        $foo->time,
        $foo->userID,
        $foo->username,
        $foo->languageID
    );
}
```
