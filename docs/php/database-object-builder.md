# Database Object Builder

!!! info "`DatabaseObjectBuilder` is available since WoltLab Suite 6.3."

`wcf\data\DatabaseObjectBuilder` is an abstract builder for creating, updating and deleting [database objects](database-objects.md) using a fluent setter API.
It is the recommended alternative to `AbstractDatabaseObjectAction` and `DatabaseObjectEditor` for new code:
instead of assembling an untyped `$data` array and dispatching it to an action, you configure a strongly typed builder instance and call `create()` or `update()`.

A builder is deliberately narrow in scope.
It only writes the columns of a single database table (plus any tightly coupled child rows, see [Extending the persistence](#extending-the-persistence)).
Side effects that reach beyond persisting the object – firing events, updating search indexes, sending notifications, resetting caches – are **not** the builder's responsibility.
Those belong into a [command](#use-case-commands) that wraps the builder.


## Motivation

Compared to `AbstractDatabaseObjectAction`, a builder offers:

- **Type safety.** Every value is set through a dedicated setter (`setName()`, `setLanguageID()`, …) with a proper parameter type instead of an arbitrary `['name' => …]` array.
- **A single, obvious entry point.** `forCreate()` returns a builder for a new row, `forUpdate($object)` a builder for an existing one. There is no ambiguity about which action name is being executed.
- **Enforced required values.** `getRequiredProperties()` fails fast with a `\BadMethodCallException` if a mandatory column was never set.
- **Batched, transactional deletes.** `deleteAll()` removes rows in batches of 1000 inside a single transaction.
- **A clear separation of concerns.** Persistence lives in the builder, orchestration lives in a command.


## Using a Builder

A builder instance is always obtained through one of the two static factory methods and is single-use:
once `create()` or `update()` has been called, the instance is consumed and may not be reused.

### Creating an object

```php
<?php

use wcf\data\tag\TagBuilder;

$tag = TagBuilder::forCreate()
    ->setName('Example')
    ->setLanguageID(1)
    ->create();
```

`create()` inserts the row and returns the freshly loaded database object.
If a required property (as declared by `getRequiredProperties()`) is missing, or no property was set at all, a `\BadMethodCallException` is thrown.

If a row with the same unique key may already exist and you would rather skip the insert than deal with an exception, use `createOrIgnore()`.
It returns `null` when the insert would have violated a unique constraint (`INSERT IGNORE` semantics) and the created object otherwise:

```php
<?php

$tag = TagBuilder::forCreate()
    ->setName('Example')
    ->setLanguageID(1)
    ->createOrIgnore();

if ($tag === null) {
    // a tag with this name already exists
}
```

### Updating an object

```php
<?php

use wcf\data\tag\TagBuilder;

$tag = TagBuilder::forUpdate($tag)
    ->setName('New name')
    ->update();
```

`update()` writes only the properties that were actually set and returns the reloaded object.
If no property was set, the original object is returned unchanged without issuing a query.

### Deleting objects

Deletion is performed through the static methods and does not require a builder instance:

```php
<?php

use wcf\data\tag\TagBuilder;

// delete a single object
TagBuilder::delete($tag);

// delete many objects by their primary keys, batched inside one transaction
TagBuilder::deleteAll([1, 2, 3]);
```

### Incrementing counters

For counter columns that must be updated relative to their current value (rather than overwritten), use the increment properties exposed by the concrete builder, for example `ArticleBuilder::incrementViews()`.
These are translated into `column = column + ?` on update, avoiding a read-modify-write race.

### Preserving an explicit ID

`setID()` sets the primary key of a *new* object explicitly.
This is intended for edge cases such as importing existing records from another installation where the ID should be preserved.
It cannot be used together with `forUpdate()`.

### Custom properties

`setCustomProperty(string $name, string|int|float|null $value)` writes an arbitrary column that has no dedicated setter.
This is primarily used to let third-party packages persist columns they added to the table, for example the `additionalFields` collected by a form (see [Use case: Form Builder forms](#use-case-form-builder-forms)).


## Implementing a Builder

A concrete builder extends `DatabaseObjectBuilder` and is named after its database object with a `Builder` suffix (`Tag` → `TagBuilder`).
The base class resolves the associated `DatabaseObject` class by stripping that suffix, so the naming convention is mandatory.

Setters push values into the protected `$properties` array and must return `$this` to keep the API fluent.
Required columns are declared via `getRequiredProperties()`.

```php
<?php

namespace wcf\data\tag;

use wcf\data\DatabaseObject;
use wcf\data\DatabaseObjectBuilder;

/**
 * @extends DatabaseObjectBuilder<Tag>
 */
final class TagBuilder extends DatabaseObjectBuilder
{
    public function setLanguageID(int $languageID): static
    {
        $this->properties['languageID'] = $languageID;

        return $this;
    }

    public function setName(string $name): static
    {
        $this->properties['name'] = $name;

        return $this;
    }

    #[\Override]
    protected function getRequiredProperties(): array
    {
        return ['name'];
    }
}
```

### Design guidelines

Concrete builders are expected to follow a common set of conventions.

#### Name setters `set*()` in camel case

Every method that configures a value is named `set*()` in camel case, for example `setTags()` or `setIsDeleted()`.

#### Make invalid states unrepresentable

Whenever possible, design the setters so that an invalid combination of properties cannot be expressed in the first place.
Do not expose two independent setters that must be kept consistent by the caller; offer a single setter that writes the coupled properties together.

For example, instead of a separate `setUserID()` and `setUsername()`, offer `setUser(User $user)` and `setGuest(string $username)`, each of which internally sets both the `userID` and the `username` property:

```php
<?php

public function setUser(User $user): static
{
    $this->properties['userID'] = $user->userID;
    $this->properties['username'] = $user->username;

    return $this;
}

public function setGuest(string $username): static
{
    $this->properties['userID'] = null;
    $this->properties['username'] = $username;

    return $this;
}
```

#### Type parameters as strictly as reasonably possible

Require the most specific type that is still reasonable, rather than a raw scalar.
Accept the object a value belongs to and read the scalar off it inside the setter.

For example, require `?Language $language` instead of `?int $languageID`:

```php
<?php

public function setLanguage(?Language $language): static
{
    $this->properties['languageID'] = $language?->languageID;

    return $this;
}
```

Note the emphasis on *reasonably*: do not force an object type where a scalar is the natural representation.

#### Track extra state in uninitialized, typed properties with asymmetric visibility

Any state beyond the plain column values – for example the synonyms handled by `TagBuilder` – must be tracked in a private-set property that is both typed and left **uninitialized**.
Leaving the property uninitialized lets you distinguish "do not consider this" (uninitialized) from an explicit `null`.
Use this pattern for all such properties, including nullable ones, to keep a common shape:

```php
<?php

public private(set) ?Foo $foo;
```

#### Keep validation minimal

Do not perform any validation beyond checking for the minimum properties that are formally required to create the object; that check belongs into `getRequiredProperties()`.
Checks for the extra state described above belong into `afterValidateCreate()`, which runs after the required-property validation but before the object is persisted:

```php
<?php

#[\Override]
protected function afterValidateCreate(): void
{
    if (!isset($this->foo)) {
        throw new \BadMethodCallException("Missing value for 'foo'.");
    }
}
```

#### Name persistence helpers `save*()`

Any method invoked from `afterCreate()` or `afterUpdate()` that persists additional data is named `save*()`, to clearly distinguish it from the `set*()` configuration methods.
`TagBuilder::saveSynonyms()` is an example of this.


### Extending the persistence

Persisting an object often involves more than a single row, for example tightly coupled child rows.
The base class provides protected hook methods for this that are invoked around the actual query:

- `afterValidateCreate()` is called after the properties have been validated but before the insert. It must not modify any properties.
- `afterCreate(DatabaseObject $object)` is called after a new row was inserted.
- `afterUpdate(DatabaseObject $object)` is called after an existing row was updated.
- `beforeDeleteAll(array $objectIDs)` is called before rows are deleted.

`TagBuilder` uses these hooks to keep a tag's synonyms in sync:

```php
<?php

#[\Override]
protected function afterCreate(DatabaseObject $object): void
{
    if ($this->synonyms !== null && $this->synonyms !== []) {
        $this->saveSynonyms($object, $this->synonyms);
    }
}

#[\Override]
protected function afterUpdate(DatabaseObject $object): void
{
    if ($this->synonyms !== null) {
        $this->removeSynonyms($object);

        if ($this->synonyms !== []) {
            $this->saveSynonyms($object, $this->synonyms);
        }
    }
}
```

!!! info "Keep the hooks limited to persisting data that belongs to the object itself. Cross-cutting side effects such as events, search index updates or notifications belong into a command."


## Use case: Commands

A **command** is a small, single-purpose, invokable class (`__invoke()`) that orchestrates a unit of work.
It receives a configured builder, calls `create()`/`update()`/`delete()` on it, and performs all of the surrounding side effects that are not the builder's concern.

This keeps the builder reusable and free of side effects while giving each use case (creating, deleting, publishing, …) its own well-named class.

```php
<?php

namespace wcf\command\tag;

use wcf\data\tag\Tag;
use wcf\data\tag\TagBuilder;
use wcf\event\tag\TagCreated;
use wcf\system\event\EventHandler;

final class CreateTag
{
    public function __construct(
        private readonly TagBuilder $builder,
    ) {}

    public function __invoke(): Tag
    {
        $tag = $this->builder->create();

        EventHandler::getInstance()->fire(new TagCreated($tag, $this->builder));

        return $tag;
    }
}
```

A command is invoked by constructing it and calling it:

```php
<?php

$tag = (new CreateTag(
    TagBuilder::forCreate()
        ->setName('Example')
        ->setLanguageID(1)
))();
```

Commands can also encapsulate more elaborate workflows.
`CreateArticle`, for example, calls `ArticleBuilder::create()` and then updates the search index, fires a recent activity event, dispatches watch notifications and increments the author's article counter, while `DeleteArticle` deletes the article via `ArticleBuilder::delete()` and cleans up reactions, comments, tags, the search index, notifications, activity events, embedded objects and attachments.
Keeping this logic in dedicated commands means it can be reused from forms, the RPC API, cronjobs and workers alike.


## Use case: Form Builder forms

Forms can persist their data through a builder and a command instead of a database object action by extending `wcf\form\AbstractDatabaseObjectBuilderForm`.
It is the builder-based counterpart to [`AbstractFormBuilderForm`](api/form_builder/overview.md#abstractformbuilderform).

A deriving form provides two things:

- `getDatabaseObjectBuilder()` returns the builder – a `forCreate()` instance for the `create` action, a `forUpdate($this->formObject)` instance for the `edit` action.
- `getCommand(DatabaseObjectBuilder $builder)` returns the invokable command that persists the builder. The default implementation simply calls `create()`/`update()`; override it to wrap saving in a command that performs additional side effects.

Instead of collecting the field values into a data array, each field registers callbacks via the `IBuilderNode` interface:

- `saveValueCallback()` receives the builder and the field and writes the field's save value into the builder.
- `loadValueCallback()` receives the edited object and the field and loads the field's value back out of it when an edit form is populated.

The form document (`DatabaseObjectBuilderFormDocument`) applies every field's `saveValueCallback()` to the builder, honoring field availability and dependencies.
Any `additionalFields` collected by the form are written through `setCustomProperty()`.

```php
<?php

namespace wcf\acp\form;

use wcf\command\tag\CreateTag;
use wcf\command\tag\UpdateTag;
use wcf\data\DatabaseObjectBuilder;
use wcf\data\tag\Tag;
use wcf\data\tag\TagBuilder;
use wcf\form\AbstractDatabaseObjectBuilderForm;
use wcf\system\form\builder\container\FormContainer;
use wcf\system\form\builder\field\IFormField;
use wcf\system\form\builder\field\TextFormField;

/**
 * @extends AbstractDatabaseObjectBuilderForm<Tag, TagBuilder>
 */
class TagAddForm extends AbstractDatabaseObjectBuilderForm
{
    public string $objectEditLinkController = TagEditForm::class;

    #[\Override]
    protected function getDatabaseObjectBuilder(): TagBuilder
    {
        if ($this->formObject !== null) {
            return TagBuilder::forUpdate($this->formObject);
        }

        return TagBuilder::forCreate();
    }

    #[\Override]
    protected function getCommand(DatabaseObjectBuilder $builder): callable
    {
        if ($this->formObject !== null) {
            return new UpdateTag($builder);
        }

        return new CreateTag($builder);
    }

    #[\Override]
    protected function createForm(): void
    {
        $this->form->appendChildren([
            FormContainer::create('general')
                ->appendChildren([
                    TextFormField::create('name')
                        ->label('wcf.global.name')
                        ->required()
                        ->maximumLength(\TAGGING_MAX_TAG_LENGTH)
                        ->saveValueCallback(static function (TagBuilder $builder, IFormField $field) {
                            $builder->setName($field->getSaveValue());
                        })
                        ->loadValueCallback(static function (Tag $object, IFormField $field) {
                            $field->value($object->name);
                        }),
                ]),
        ]);
    }
}
```

The same form class handles both the add and the edit case:
`TagEditForm` extends `TagAddForm`, identifies the edited object and assigns it to `$formObject`, which switches `getDatabaseObjectBuilder()` and `getCommand()` over to the update path.

!!! info "`saveValueCallback()` and `loadValueCallback()` are declared on the `IBuilderNode` interface and are available on the form fields shipped with WoltLab Suite. Only fields that register a `saveValueCallback()` contribute to the builder; a field without one is ignored during saving."
