---
title: Database Objects
sidebar: sidebar
permalink: php_database-objects.html
folder: php
---

WoltLab Suite uses a unified interface to work with database rows using an object based approach instead of using native arrays holding arbitrary data. Each database table is mapped to a model class that is designed to hold a single record from that table and expose methods to work with the stored data, for example providing assistance when working with normalized datasets.

Developers are required to provide the proper DatabaseObject implementations themselves, they're not automatically generated, all though the actual code that needs to be written is rather small. The following examples assume the fictional database table `wcf1_example`, `exampleID` as the auto-incrementing primary key and the column `bar` to store some text.

## DatabaseObject

The basic model derives from `wcf\data\DatabaseObject` and provides a convenient constructor to fetch a single row or construct an instance using pre-loaded rows.

```php
<?php
namespace wcf\data\example;
use wcf\data\DatabaseObject;

class Example extends DatabaseObject {}
```

The class is intended to be empty by default and there only needs to be code if you want to add additional logic to your model. Both the class name and primary key are determinted by `DatabaseObject` using the namespace and class name of the derived class. The example above uses the namespace `wcf\…` which is used as table prefix and the class name `Example` is converted into `exampleID`, resulting in the database table name `wcfN_example` with the primary key `exampleID`.

You can prevent this automatic guessing by setting the class properties `$databaseTableName` and `$databaseTableIndexName` manually.

## DatabaseObjectEditor

{% include callout.html content="This is the low-level interface to manipulate data rows, it is recommended to use `AbstractDatabaseObjectAction`." type="info" %}

Adding, editing and deleting models is done using the `DatabaseObjectEditor` class that decorates a `DatabaseObject` and uses its data to perform the actions.

```php
<?php
namespace wcf\data\example;
use wcf\data\DatabaseObjectEditor;

class ExampleEditor extends DatabaseObjectEditor {
    protected static $baseClass = Example::class;
}
```

The editor class requires you to provide the fullly qualified name of the model, that is the class name including the complete namespace. Database table name and index key will be pulled directly from the model.

### Create a new row

Inserting a new row into the database table is provided through `DatabaseObjectEditor::create()` which yields a `DatabaseObject` instance after creation.

```php
<?php
$example = \wcf\data\example\ExampleEditor::create([
    'bar' => 'Hello World!'
]);

// output: Hello World!
echo $example->bar;
```

### Updating an existing row

{% include callout.html content="The internal state of the decorated `DatabaseObject` is not altered at any point, the values will still be the same after editing or deleting the represented row. If you need an object with the latest data, you'll have to discard the current object and refetch the data from database." type="warning" %}

```php
<?php
$example = new \wcf\data\example\Example($id);
$exampleEditor = new \wcf\data\example\ExampleEditor($example);
$exampleEditor->update([
    'bar' => 'baz'
]);

// output: Hello World!
echo $example->bar;

// re-creating the object will query the database again and retrieve the updated value
$example = new \wcf\data\example\Example($example->id);

// output: baz
echo $example->bar;
```

### Deleting a row

{% include callout.html content="Similar to the update process, the decorated `DatabaseObject` is not altered and will then point to an inexistent row." type="warning" %}

```php
<?php
$example = new \wcf\data\example\Example($id);
$exampleEditor = new \wcf\data\example\ExampleEditor($example);
$exampleEditor->delete();
```

## DatabaseObjectList

Every row is represented as a single instance of the model, but the instance creation deals with single rows only. Retrieving larger sets of rows would be quite inefficient due to the large amount of queries that will be dispatched. This is solved with the `DatabaseObjectList` object that exposes an interface to query the database table using arbitrary conditions for data selection. All rows will be fetched using a single query and the resulting rows are automatically loaded into separate models.

```php
<?php
namespace wcf\data\example;
use wcf\data\DatabaseObjectList;

class ExampleList extends DatabaseObjectList {
    public $className = Example::class;
}
```

The following code listing illustrates loading a large set of examples and iterating over the list to retrieve the objects.

```php
<?php
$exampleList = new \wcf\data\example\ExampleList();
$exampleList->getConditionBuilder()->add('bar IN (?)', [['Hello World!', 'bar', 'baz']]);
$exampleList->readObjects();
foreach ($exampleList as $example) {
    echo $example->bar;
}

// retrieve the models directly instead of iterating over them
$examples = $exampleList->getObjects();
```

`DatabaseObjecList` implements both [SeekableIterator](https://secure.php.net/manual/en/class.seekableiterator.php) and [Countable](https://secure.php.net/manual/en/class.countable.php).

## AbstractDatabaseObjectAction

Row creation and manipulation can be performed using the aforementioned `DatabaseObjectEditor` class, but this approach has two major issues:

1. Row creation, update and deletion takes place silently without notifying any other components.
2. Data is passed to the database adapter without any further processing.

The `AbstractDatabaseObjectAction` solves both problems by wrapping around the editor class and thus provide an additional layer between the action that should be taken and the actual process. The first problem is solved by a fixed set of events being fired, the second issue is adressed by having a single entry point for all data editing.

```php
<?php
namespace wcf\data\example;
use wcf\data\AbstractDatabaseObjectAction;

class ExampleAction extends AbstractDatabaseObjectAction {
    public $className = Example::class;
}
```

### Executing an Action

{% include callout.html content="The method `AbstractDatabaseObjectAction::validateAction()` is internally used for AJAX method invocation and must not be called programmatically." type="warning" %}

The next example represents the same functionality as seen for `DatabaseObjectEditor`:

```php
<?php
use wcf\data\example\ExampleAction;

// create a row
$exampleAction = new ExampleAction([], 'create', [
    'data' => ['bar' => 'Hello World']
]);
$example = $exampleAction->executeAction()['returnValues'];

// update a row using the id
$exampleAction = new ExampleAction([1], 'update', [
    'data' => ['bar' => 'baz']
]);
$exampleAction->executeAction();

// delete a row using a model
$exampleAction = new ExampleAction([$example], 'delete');
$exampleAction->executeAction();
```

You can access the return values both by storing the return value of `executeAction()` or by retrieving it via `getReturnValues()`.

<span class="label label-info">Events</span> `initializeAction`, `validateAction` and `finalizeAction`

### Custom Method with AJAX Support

This section is about adding the method `baz()` to `ExampleAction` and calling it via AJAX.

#### AJAX Validation

Methods of an action cannot be called via AJAX, unless they have a validation method. This means that `ExampleAction` must define both a `public function baz()` and `public function validateBaz()`, the name for the validation method is constructured by upper-casing the first character of the method name and prepending `validate`.

The lack of the companion `validate*` method will cause the AJAX proxy to deny the request instantaneously. Do not add a validation method if you don't want it to be callable via AJAX ever!

#### create, update and delete

The methods `create`, `update` and `delete` are available for all classes deriving from `AbstractDatabaseObjectAction` and directly pass the input data to the `DatabaseObjectEditor`. These methods deny access to them via AJAX by default, unless you explicitly enable access. Depending on your case, there are two different strategies to enable AJAX access to them.

```
<?php
namespace wcf\data\example;
use wcf\data\AbstractDatabaseObjectAction;

class ExampleAction extends AbstractDatabaseObjectAction {
    // `create()` can now be called via AJAX if the requesting user posses the listed permissions
    protected $permissionsCreate = ['admin.example.canManageExample'];

    public function validateUpdate() {
        // your very own validation logic that does not make use of the
        // built-in `$permissionsUpdate` property

        // you can still invoke the built-in permissions check if you like to
        parent::validateUpdate();
    }
}
```

#### Allow Invokation by Guests

Invoking methods is restricted to logged-in users by default and the only way to override this behavior is to alter the property `$allowGuestAccess`. It is a simple string array that is expected to hold all methods that should be accessible by users, excluding their companion validation methods.

#### ACP Access Only

Method access is usually limited by permissions, but sometimes there might be the need for some added security to avoid mistakes. The `$requireACP` property works similar to `$allowGuestAccess`, but enforces the request to originate from the ACP together with a valid ACP session, ensuring that only users able to access the ACP can actually invoke these methods.
