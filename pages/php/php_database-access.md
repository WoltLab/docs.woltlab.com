---
title: Database Access
sidebar: sidebar
permalink: php_database-access.html
folder: php
---

[Database Objects][php_database-objects] provide a convenient and object-oriented approach to work with the database, but there can be use-cases that require raw access including writing methods for model classes. This section assumes that you have either used [prepared statements](https://en.wikipedia.org/wiki/Prepared_statement) before or at least understand how it works.

## The PreparedStatement Object

The database access is designed around [PreparedStatement](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/database/statement/PreparedStatement.class.php), built on top of `PDOStatement`, and each query requires you to obtain a statement object.

```php
<?php
$statement = \wcf\system\WCF::getDB()->prepareStatement("SELECT * FROM wcf".WCF_N."_example");
$statement->execute();
while ($row = $statement->fetchArray()) {
    // handle result
}
```

### Query Parameters

The example below illustrates the usage of parameters where each value is replaced with the generic `?`-placeholder. Values are provided by calling `$statement->execute()` with a continuous, one-dimensional array that exactly match the number of question marks.

```php
<?php
$sql = "SELECT  *
        FROM    wcf".WCF_N."_example
        WHERE   exampleID = ?
                OR bar IN (?, ?, ?, ?, ?)";
$statement = \wcf\system\WCF::getDB()->prepareStatement($sql);
$statement->execute([
    $exampleID,
    $list, $of, $values, $for, $bar
]);
while ($row = $statement->fetchArray()) {
    // handle result
}
```

### Fetching a Single Result

{% include callout.html content="Do not attempt to use `fetchSingleRow()` or `fetchSingleColumn()` if the result contains more than one row." type="danger" %}

You can opt-in to retrieve only a single row from database and make use of shortcut methods to reduce the code that you have to write.

```php
<?php
$sql = "SELECT  *
        FROM    wcf".WCF_N."_example
        WHERE   exampleID = ?";
$statement = \wcf\system\WCF::getDB()->prepareStatement($sql, 1);
$statement->execute([$exampleID]);
$row = $statement->fetchSingleRow();
```

There are two distinct differences when comparing with the example on query parameters above:

1. The method `prepareStatement()` receives a secondary parameter that will be appended to the query as `LIMIT 1`.
2. Data is read using `fetchSingleRow()` instead of `fetchArray()` or similar methods, that will read one result and close the cursor.

### Fetch by Column

{% include callout.html content="There is no way to return another column from the same row if you use `fetchColumn()` to retrieve data." type="warning" %}

Fetching an array is only useful if there is going to be more than one column per result row, otherwise accessing the column directly is much more convenient and increases the code readability.

```php
<?php
$sql = "SELECT  bar
        FROM    wcf".WCF_N."_example";
$statement = \wcf\system\WCF::getDB()->prepareStatement($sql);
$statement->execute();
while ($bar = $statement->fetchColumn()) {
    // handle result
}
$bar = $statement->fetchSingleColumn();
```

Similar to fetching a single row, you can also issue a query that will select a single row, but reads only one column from the result row.

```php
$sql = "SELECT  bar
        FROM    wcf".WCF_N."_example
        WHERE   exampleID = ?";
$statement = \wcf\system\WCF::getDB()->prepareStatement($sql, 1);
$statement->execute([$exampleID]);
$bar = $statement->fetchSingleColumn();
```

## Building Complex Conditions

Building conditional conditions can turn out to be a real mess and it gets even worse with SQL's `IN (…)` which requires as many placeholders as there will be values. The solutions is `PreparedStatementConditionBuilder`, a simple but useful helper class with a bulky name, it is also the class used when accessing `DatabaseObjecList::getConditionBuilder()`.

```php
<?php
$conditions = new \wcf\system\database\util\PreparedStatementConditionBuilder();
$conditions->add("exampleID = ?", [$exampleID]);
if (!empty($valuesForBar)) {
    $conditions->add("bar IN (?)", [$valuesForBar]);
}
```

The `IN (?)` in the example above is automatically expanded to match the number of items contained in `$valuesForBar`. Be aware that the method will generate an invalid query if `$valuesForBar` is empty!

## INSERT or UPDATE in Bulk

Prepared statements not only protect against SQL injection by separating the logical query and the actual data, but also provides the ability to reuse the same query with different values. This leads to a performance improvement as the code does not have to transmit the query with for every data set and only has to parse and analyze the query once.

```php
<?php
$data = ['abc', 'def', 'ghi'];

$sql = "INSERT INTO  wcf".WCF_N."_example
                     (bar)
        VALUES       (?)";
$statement = \wcf\system\WCF::getDB()->prepareStatement($sql);

\wcf\system\WCF::getDB()->beginTransaction();
foreach ($data as $bar) {
    $statement->execute([$bar]);
}
\wcf\system\WCF::getDB()->commitTransaction();
```

It is generally advised to wrap bulk operations in a transaction as it allows the database to optimize the process, including fewer I/O operations.

```php
$data = [
    1 => 'abc',
    3 => 'def',
    4 => 'ghi'
];

$sql = "UPDATE  wcf".WCF_N."_example
        SET     bar = ?
        WHERE   exampleID = ?";
$statement = \wcf\system\WCF::getDB()->prepareStatement($sql);

\wcf\system\WCF::getDB()->beginTransaction();
foreach ($data as $exampleID => $bar) {
    $statement->execute([
        $bar,
        $exampleID
    ]);
}
\wcf\system\WCF::getDB()->commitTransaction();
```

{% include links.html %}
