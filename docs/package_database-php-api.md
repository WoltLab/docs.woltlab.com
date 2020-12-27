# Database PHP API

!!! info "Available since WoltLab Suite 5.2."

While the [sql](package_pip_sql.md) package installation plugin supports adding and removing tables, columns, and indices, it is not able to handle cases where the added table, column, or index already exist.
We have added a new PHP-based API to manipulate the database scheme which can be used in combination with the [script](package_pip_script.md) package installation plugin that skips parts that already exist:

```php
$tables = [
	// TODO
];

(new DatabaseTableChangeProcessor(
	/** @var ScriptPackageInstallationPlugin $this */
	$this->installation->getPackage(),
	$tables,
	WCF::getDB()->getEditor())
)->process();
```

All of the relevant components can be found in the `wcf\system\database\table` namespace.


## Database Tables

There are two classes representing database tables: `DatabaseTable` and `PartialDatabaseTable`.
If a new table should be created, use `DatabaseTable`.
In all other cases, `PartialDatabaseTable` should be used as it provides an additional save-guard against accidentally creating a new table by having a typo in the table name:
If the tables does not already exist, a table represented by `PartialDatabaseTable` will cause an exception (while a `DatabaseTable` table will simply be created).

To create a table, a `DatabaseTable` object with the table's name as to be created and table's columns, foreign keys and indices have to be specified:

```php
DatabaseTable::create('foo1_bar')
    ->columns([
        // columns
    ])
    ->foreignKeys([
        // foreign keys
    ])
    ->indices([
        // indices
    ])
```

To update a table, the same code as above can be used, except for `PartialDatabaseTable` being used instead of `DatabaseTable`.

To drop a table, only the `drop()` method has to be called:

```php
PartialDatabaseTable::create('foo1_bar')
    ->drop()
```


## Columns

To represent a column of a database table, you have to create an instance of the relevant column class found in the `wcf\system\database\table\column` namespace.
Such instances are created similarly to database table objects using the `create()` factory method and passing the column name as the parameter.

Every column type supports the following methods:

- `defaultValue($defaultValue)` sets the default value of the column (default: none).
- `drop()` to drop the column.
- `notNull($notNull = true)` sets if the value of the column can be `NULL` (default: `false`).

Depending on the specific column class implementing additional interfaces, the following methods are also available:

- `IAutoIncrementDatabaseTableColumn::autoIncrement($autoIncrement = true)` sets if the value of the colum is auto-incremented.
- `IDecimalsDatabaseTableColumn::decimals($decimals)` sets the number of decimals the column supports.
- `IEnumDatabaseTableColumn::enumValues(array $values)` sets the predetermined set of valid values of the column.
- `ILengthDatabaseTableColumn::length($length)` sets the (maximum) length of the column.

Additionally, there are some additionally classes of commonly used columns with specific properties:

- `DefaultFalseBooleanDatabaseTableColumn` (a `tinyint` column with length `1`, default value `0` and whose values cannot be `null`)
- `DefaultTrueBooleanDatabaseTableColumn` (a `tinyint` column with length `0`, default value `0` and whose values cannot be `null`)
- `NotNullInt10DatabaseTableColumn` (a `int` column with length `10` and whose values cannot be `null`)
- `NotNullVarchar191DatabaseTableColumn` (a `varchar` column with length `191` and whose values cannot be `null`)
- `NotNullVarchar255DatabaseTableColumn` (a `varchar` column with length `255` and whose values cannot be `null`)
- `ObjectIdDatabaseTableColumn` (a `int` column with length `10`, whose values cannot be `null`, and whose values are auto-incremented)

Examples:

```php
DefaultFalseBooleanDatabaseTableColumn::create('isDisabled')

NotNullInt10DatabaseTableColumn::create('fooTypeID')

SmallintDatabaseTableColumn::create('bar')
	->length(5)
	->notNull()
```


## Foreign Keys

Foreign keys are represented by `DatabaseTableForeignKey` objects: 

```php
DatabaseTableForeignKey::create()
	->columns(['fooID'])
	->referencedTable('wcf1_foo')
	->referencedColumns(['fooID'])
	->onDelete('CASCADE')
```

The supported actions for `onDelete()` and `onUpdate()` are `CASCADE`, `NO ACTION`, and `SET NULL`.
To drop a foreign key, all of the relevant data to create the foreign key has to be present and the `drop()` method has to be called.

`DatabaseTableForeignKey::create()` also supports the foreign key name as a parameter.
If it is not present, `DatabaseTable::foreignKeys()` will automatically set one based on the foreign key's data.


## Indices

Indices are represented by `DatabaseTableIndex` objects: 

```php
DatabaseTableIndex::create()
	->type(DatabaseTableIndex::UNIQUE_TYPE)
	->columns(['fooID'])
```

There are four different types: `DatabaseTableIndex::DEFAULT_TYPE` (default), `DatabaseTableIndex::PRIMARY_TYPE`, `DatabaseTableIndex::UNIQUE_TYPE`, and `DatabaseTableIndex::FULLTEXT_TYPE`.
For primary keys, there is also the `DatabaseTablePrimaryIndex` class which automatically sets the type to `DatabaseTableIndex::PRIMARY_TYPE`.
To drop a index, all of the relevant data to create the index has to be present and the `drop()` method has to be called.

`DatabaseTableIndex::create()` also supports the index name as a parameter.
If it is not present, `DatabaseTable::indices()` will automatically set one based on the index data.
