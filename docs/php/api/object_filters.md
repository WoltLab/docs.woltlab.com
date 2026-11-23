# Object Filters

Object filters are configurable conditions that decide whether an object matches a set of criteria.
They are used for the conditions of ads, notices, automatic user group assignments and trophies, where they decide which users a notice or ad is shown to, which users are assigned to a user group or which users receive a trophy.

The API consists of three parts:

- A **filter** (`wcf\system\object\filter\IObjectFilter`) represents a single type of condition, e.g. “Member of user group”.
  It provides the form field to enter a value, converts the value to and from its stored representation, summarizes it for the user and tests it against an object.
- A **builder** (`wcf\system\object\filter\builder\IObjectFilterBuilder`) provides the set of filters that is available for one use case, e.g. the conditions of notices.
  Builders are the extension point that allows plugins to add their own filters.
- A **handler** (`wcf\system\object\filter\ObjectFilterHandler`) evaluates the stored filters, it is created and used by the builder.

Filters are configured through the form field `wcf\system\form\builder\field\ObjectFilterFormField`, which allows the user to add, edit and remove filters through a dialog.

## Stored Values

The configured filters are stored as a JSON-encoded list of pairs that consist of the identifier of the filter and its serialized value, both are strings:

```json
[["com.woltlab.wcf.userGroup","4"],["com.woltlab.wcf.requestedPage","1,5"]]
```

The objects that use object filters store this value in a nullable JSON column named `conditions`.

All configured filters are combined with a logical **AND**, there is no support for alternatives.
Filters that support multiple values, e.g. a list of pages or languages, match if any of the values matches.

Stored values whose filter is unknown, e.g. because the package that provided it has been uninstalled, are ignored.
If no known filter remains, the handler treats the value as having no active filters, see [Evaluating Filters](#evaluating-filters).

## `IObjectFilter`

```php
interface IObjectFilter
{
    public function getIdentifier(): string;
    public function getTitle(): string;
    public function getFormField(): AbstractFormField;
    public function isAvailable(): bool;
    public function isRepeatable(): bool;
    public function serializeValue(mixed $value): string;
    public function unserializeValue(string $serializedValue): mixed;
    public function toFormFieldValue(mixed $value): mixed;
    public function summarizeValue(mixed $value): string;
    public function testObject(DatabaseObject $object, mixed $configuredValue): bool;
}
```

The interface is generic over the type of the tested objects (`TDatabaseObject`) and the type of the configured value (`TValueType`).
All filters of the bundled builders test `wcf\data\user\User` objects, filters that do not depend on the object, e.g. the start date of a notice, use `DatabaseObject`.

| Method | Description |
|--------|-------------|
| `getIdentifier()` | Unique identifier that is stored with the value, e.g. `com.woltlab.wcf.userGroup`. Use your package identifier as a prefix and never change it once values have been stored. |
| `getTitle()` | Title of the filter in the filter selection of the dialog. |
| `getFormField()` | Form field to enter the value. The id of the form field must be unique among all filters of a builder because all fields are part of the same dialog form. |
| `isAvailable()` | Returns `false` to hide the filter in the dialog, e.g. if there are no objects to choose from. Stored values of an unavailable filter are still evaluated. |
| `isRepeatable()` | Returns `false` if the filter can be configured only once per object, e.g. for yes/no choices. |
| `serializeValue()` | Converts the value of the form field into its stored string representation. |
| `unserializeValue()` | Restores the native value (`TValueType`) from its stored representation. |
| `toFormFieldValue()` | Converts the native value into the value of the form field when an existing filter is edited. |
| `summarizeValue()` | Returns a human-readable summary that is shown in the list of configured filters. The summary is HTML, any user-provided content must be encoded. |
| `testObject()` | Returns whether the given object matches the configured value. |

## `IObjectListFilter`

Filters that implement `wcf\system\object\filter\IObjectListFilter` can additionally be expressed as an SQL condition, which allows them to be applied to a whole `DatabaseObjectList` at once.
The builders of trophies and automatic user group assignments find the matching users this way, therefore they only accept list filters.

```php
interface IObjectListFilter extends IObjectFilter
{
    public function applyFilter(DatabaseObjectList $list, mixed $value): void;
}
```

`applyFilter()` must follow a few rules because the conditions are also embedded into custom queries, e.g. to find users whose trophies must be revoked:

- Add conditions through `$list->getConditionBuilder()` and joins required by them to `$list->sqlConditionJoins`, do not change anything else of the list.
- The user table is available with the alias `user_table`. Use `wcf\system\option\user\TJoinUserOptionValues::joinUserOptionValues()` to join the user option values, it adds the join only once.
- The result must match the result of `testObject()` for the same value, e.g. a case-insensitive `LIKE` in SQL requires a case-insensitive comparison in PHP.
- If the result does not depend on the object, add `1=1` or `1=0` anyway, otherwise the condition of a list with this filter as its only filter would be empty.

## Base Classes

The abstract base classes in `wcf\system\object\filter` implement everything apart from the identifier, the test and the parts that are specific to the filter.
The language item returned by `getLanguageItem()` is used as the title and as the label of the form field, the summary is read from related language items.

| Class | Value | Serialized | Required Methods | Summary |
|-------|-------|------------|------------------|---------|
| `AbstractBooleanObjectFilter` | `bool` | `1` or `0` | `getIdentifier()`, `getLanguageItem()`, `testObject()` | `<languageItem>.summary.yes`, `<languageItem>.summary.no` |
| `AbstractSelectionObjectFilter` | `int` | `4` | `getIdentifier()`, `getLanguageItem()`, `createOptions()`, `testObject()` | `<languageItem>.summary` with `$value` |
| `AbstractMultipleSelectionObjectFilter` | `list<int>` | `1,2,3` | `getIdentifier()`, `getLanguageItem()`, `getLabels()`, `getFormField()`, `testObject()` | `<languageItem>.summary` with `$values` |
| `AbstractRangeObjectFilter` | `array{0: ?TBound, 1: ?TBound}` | `from;to` | `getIdentifier()`, `getLanguageItem()`, `createFormField()`, `unserializeBound()`, `testObject()` | `<languageItem>.summary` with `$from` and `$to` |
| `AbstractTimestampObjectFilter` | `int` | `1700000000` | `getIdentifier()`, `getLanguageItem()`, `testObject()` | `<languageItem>.summary` with `$value` |

Some details of the base classes:

- `AbstractSelectionObjectFilter` expects `createOptions()` to return plain text labels indexed by the object id, which are encoded by the filter.
  The filter is repeatable to allow selecting multiple objects and is unavailable if there are no options.
- `AbstractMultipleSelectionObjectFilter` stores multiple ids in a single value and does not provide a form field, `getFormField()` must be implemented, usually with a `MultipleSelectionFormField`.
  `getLabels()` returns the plain text labels of the given ids for the summary.
- `AbstractRangeObjectFilter` requires at least one of the bounds and validates that the lower bound is not greater than the upper bound.
  Override `supportsInvertedRange()` to allow inverted ranges, e.g. for a time range that spans midnight.
  Missing bounds are `null` in the value and in the summary.

## Example

The following filter matches users depending on the value of a custom column `fooVerified` of `wcf1_user`.
It implements `IObjectListFilter` to be usable for trophies and automatic user group assignments as well.

```php
<?php

namespace foo\system\object\filter;

use wcf\data\DatabaseObject;
use wcf\data\DatabaseObjectList;
use wcf\data\user\User;
use wcf\system\object\filter\AbstractBooleanObjectFilter;
use wcf\system\object\filter\IObjectListFilter;

/**
 * @extends AbstractBooleanObjectFilter<User>
 * @implements IObjectListFilter<User, bool>
 */
final class UserVerifiedObjectFilter extends AbstractBooleanObjectFilter implements IObjectListFilter
{
    #[\Override]
    public function getIdentifier(): string
    {
        return 'com.example.foo.userVerified';
    }

    #[\Override]
    protected function getLanguageItem(): string
    {
        return 'foo.objectFilter.user.verified';
    }

    #[\Override]
    public function applyFilter(DatabaseObjectList $list, mixed $value): void
    {
        $list->getConditionBuilder()->add('user_table.fooVerified = ?', [$value ? 1 : 0]);
    }

    #[\Override]
    public function testObject(DatabaseObject $object, mixed $configuredValue): bool
    {
        if ($object->isGuest()) {
            return false;
        }

        return (bool)$object->fooVerified === $configuredValue;
    }
}
```

The filter requires the phrases `foo.objectFilter.user.verified` (“Verified”), `foo.objectFilter.user.verified.summary.yes` (“Is verified”) and `foo.objectFilter.user.verified.summary.no` (“Is not verified”).

## Reusable Filters

Several filters can be used directly without writing a custom filter class.

### Integer Columns of Users

`wcf\system\object\filter\user\UserIntegerPropertyObjectFilter` matches users whose integer column of `wcf1_user` is within an inclusive range, guests never match.
The identifier is derived from the column, e.g. `com.woltlab.wcf.userFooPoints` for the column `fooPoints`.

```php
new UserIntegerPropertyObjectFilter('fooPoints', 'foo.user.condition.fooPoints');
```

### User Options

User options of the `profile` category tree whose [form option](form_builder/form_options.md) supports filtering are automatically available as filters through `wcf\system\object\filter\user\UserOptionObjectFilterFactory`.
No additional code is required.

### Generic Filters

The following filters do not depend on the tested object and can be used by any builder:

- `wcf\system\object\filter\StartDateObjectFilter` and `wcf\system\object\filter\EndDateObjectFilter` match from or until the configured point in time.
- `wcf\system\object\filter\RequestedPageObjectFilter` and `wcf\system\object\filter\NotRequestedPageObjectFilter` match depending on the active page of the request.
  Requests without an active page never match either filter.
  Neither filter implements `IObjectListFilter`.

## Adding Filters to Existing Builders

Each builder fires a PSR-14 event that allows plugins to register additional filters.
Registering a filter with the identifier of an existing filter replaces that filter.

| Builder | Identifier | Event | Accepted Filters |
|---------|------------|-------|------------------|
| `wcf\system\object\filter\builder\AdObjectFilterBuilder` | `com.woltlab.wcf.ad` | `wcf\event\ad\AdObjectFilterCollecting` | `IObjectFilter` |
| `wcf\system\object\filter\builder\NoticeObjectFilterBuilder` | `com.woltlab.wcf.notice` | `wcf\event\notice\NoticeObjectFilterCollecting` | `IObjectFilter` |
| `wcf\system\object\filter\builder\TrophyObjectFilterBuilder` | `com.woltlab.wcf.trophy` | `wcf\event\trophy\TrophyObjectFilterCollecting` | `IObjectListFilter` |
| `wcf\system\object\filter\builder\UserGroupAssignmentObjectFilterBuilder` | `com.woltlab.wcf.userGroupAssignment` | `wcf\event\user\group\assignment\UserGroupAssignmentObjectFilterCollecting` | `IObjectListFilter` |

The listeners are registered in the bootstrap script of the package (`files/lib/bootstrap/com.example.foo.php`):

```php
<?php

use wcf\system\event\EventHandler;

return static function (): void {
    $registerUserFilters = static function (
        \wcf\event\ad\AdObjectFilterCollecting
        |\wcf\event\notice\NoticeObjectFilterCollecting
        |\wcf\event\trophy\TrophyObjectFilterCollecting
        |\wcf\event\user\group\assignment\UserGroupAssignmentObjectFilterCollecting $event
    ): void {
        $event->register(new \foo\system\object\filter\UserVerifiedObjectFilter());
        $event->register(new \wcf\system\object\filter\user\UserIntegerPropertyObjectFilter('fooPoints', 'foo.user.condition.fooPoints'));
    };

    $eventHandler = EventHandler::getInstance();
    $eventHandler->register(\wcf\event\ad\AdObjectFilterCollecting::class, $registerUserFilters);
    $eventHandler->register(\wcf\event\notice\NoticeObjectFilterCollecting::class, $registerUserFilters);
    $eventHandler->register(\wcf\event\trophy\TrophyObjectFilterCollecting::class, $registerUserFilters);
    $eventHandler->register(\wcf\event\user\group\assignment\UserGroupAssignmentObjectFilterCollecting::class, $registerUserFilters);
};
```

## Object Filters for Custom Objects

Packages can use object filters for their own objects by providing a builder.

### Database Column

The filters are stored in a nullable JSON column of the table of the objects:

```php
JsonDatabaseTableColumn::create('conditions'),
```

### Builder

The builder extends `wcf\system\object\filter\builder\AbstractObjectFilterBuilder`, which creates the filters and the handler on first use and caches them for the lifetime of the builder instance.

- `createFilters()` returns the available filters.
  Firing a dedicated event, modeled after `NoticeObjectFilterCollecting`, allows other packages to add filters.
- `createHandler()` returns an `ObjectFilterHandler` or, if the filters are applied to object lists, an `ObjectListFilterHandler`.
- `getIdentifier()` returns the unique identifier of the builder.
- `isAccessible()` checks the permission that is required to configure the filters.

The base class does not provide a public method to evaluate the filters, the builder adds typed methods for its use case instead.

```php
<?php

namespace foo\system\object\filter\builder;

use foo\data\bar\Bar;
use foo\event\bar\BarObjectFilterCollecting;
use wcf\data\user\User;
use wcf\system\event\EventHandler;
use wcf\system\object\filter\builder\AbstractObjectFilterBuilder;
use wcf\system\object\filter\IObjectFilter;
use wcf\system\object\filter\ObjectFilterHandler;
use wcf\system\object\filter\user\UserGroupObjectFilter;
use wcf\system\object\filter\user\UserLanguageObjectFilter;
use wcf\system\WCF;

/**
 * @extends AbstractObjectFilterBuilder<User, IObjectFilter<User, mixed>, ObjectFilterHandler<User>>
 */
final class BarObjectFilterBuilder extends AbstractObjectFilterBuilder
{
    #[\Override]
    protected function createFilters(): array
    {
        $event = new BarObjectFilterCollecting();
        $event->register(new UserGroupObjectFilter(includeGuests: true));
        $event->register(new UserLanguageObjectFilter());

        EventHandler::getInstance()->fire($event);

        return $event->getFilters();
    }

    #[\Override]
    protected function createHandler(array $filters): ObjectFilterHandler
    {
        return new ObjectFilterHandler($filters);
    }

    #[\Override]
    public function getIdentifier(): string
    {
        return 'com.example.foo.bar';
    }

    #[\Override]
    public function isAccessible(): bool
    {
        return WCF::getSession()->hasPermission('admin.foo.canManageBar');
    }

    public function testUser(Bar $bar, User $user): bool
    {
        if ($bar->conditions === null) {
            return true;
        }

        return $this->getHandler()->testObject($user, $bar->conditions);
    }
}
```

### Registering the Builder

The dialog to configure a filter is provided by `wcf\action\ObjectFilterBuilderAction`, which looks up the builder by its identifier.
Builders must therefore be registered through the `wcf\event\object\filter\ObjectFilterBuilderCollecting` event:

```php
EventHandler::getInstance()->register(
    \wcf\event\object\filter\ObjectFilterBuilderCollecting::class,
    static function (\wcf\event\object\filter\ObjectFilterBuilderCollecting $event) {
        $event->register(new \foo\system\object\filter\builder\BarObjectFilterBuilder());
    }
);
```

### Form Field

`ObjectFilterFormField` requires the builder whose filters can be configured, its value is the JSON-encoded list of filters.
The field validates the structure of the value and rejects repeated use of filters that are not repeatable, use `required()` to require at least one filter.

```php
FormContainer::create('conditionsContainer')
    ->label('foo.acp.bar.conditions')
    ->appendChild(
        ObjectFilterFormField::create('conditions')
            ->builder(new BarObjectFilterBuilder())
            ->saveValueCallback(static function (BarBuilder $builder, IFormField $field) {
                $builder->setConditions($field->getSaveValue());
            })
    ),
```

### Evaluating Filters

The handler returns `false` if no filters are active, i.e. if the stored value is `null`, an empty list or contains only unknown filters.
`ObjectListFilterHandler::applyFilters()` adds a condition that matches no objects in this case and returns whether any filter has been applied.

Decide what an object without filters means for your use case and handle it in the builder.
Notices and ads without filters are visible to everyone, therefore their builders store `null` for an empty list and return `true` for `null` before calling the handler.
Automatic user group assignments and trophies without filters match no users.

Reuse the same builder instance when evaluating the filters of multiple objects, e.g. in a loop, because each instance creates its filters only once.

```php
$filterBuilder = new BarObjectFilterBuilder();
foreach ($bars as $bar) {
    if (!$filterBuilder->testUser($bar, WCF::getUser())) {
        continue;
    }

    // …
}
```
