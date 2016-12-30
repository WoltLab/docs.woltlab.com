---
title: SQL Package Installation Plugin
sidebar: sidebar
permalink: package_pip_sql.html
folder: package/pip
parent: package_pip
---

Execute SQL instructions using a MySQL-flavored syntax.

{% include callout.html content="This file is parsed by WoltLab Suite Core to allow reverting of certain changes, but not every syntax MySQL supports is recognized by the parser. To avoid any troubles, you should always use statements relying on the SQL standard." type="warning" %}


## Expected Value

The `sql` package installation plugin expects a relative path to a `.sql` file.


## Features

### Logging

WoltLab Suite Core uses a SQL parser to extract queries and log certain actions.
This allows WoltLab Suite Core to revert some of the changes you apply upon package uninstallation.

The logged changes are:

- `CREATE TABLE`
- `ALTER TABLE … ADD COLUMN`
- `ALTER TABLE … ADD … KEY`

### Instance Number

It is possible to use different instance numbers, e.g. two separate WoltLab Suite Core installations within one database.
WoltLab Suite Core requires you to always use `wcf1_<tableName>` or `<app>1_<tableName>` (e.g. `blog1_blog` in WoltLab Suite Blog), the number (`1`) will be automatically replaced prior to execution.
If you every use anything other but `1`, you will eventually break things, thus always use `1`!

### Table Type

WoltLab Suite Core will determine the type of database tables on its own:
If the table contains a `FULLTEXT` index, it uses `MyISAM`, otherwise `InnoDB` is used.


## Limitations

### Logging

WoltLab Suite Core cannot revert changes to the database structure which would cause to the data to be either changed or new data to be incompatible with the original format.
Additionally, WoltLab Suite Core does not track regular SQL queries such as `DELETE` or `UPDATE`.

### Triggers

WoltLab Suite Core does not support trigger since MySQL does not support execution of triggers if the event was fired by a cascading foreign key action.
If you really need triggers, you should consider adding them by custom SQL queries using a [script](package_pip_script.html).


## Example

`package.xml`:

```xml
<instruction type="sql">install.sql</instruction>
```

Example content:

```sql
CREATE TABLE wcf1_foo_bar (
	fooID INT(10) NOT NULL AUTO_INCREMENT PRIMARY KEY,
	packageID INT(10) NOT NULL,
	bar VARCHAR(255) NOT NULL DEFAULT '',
	foobar VARCHAR(50) NOT NULL DEFAULT '',
	
	UNIQUE KEY baz (bar, foobar)
);

ALTER TABLE wcf1_foo_bar ADD FOREIGN KEY (packageID) REFERENCES wcf1_package (packageID) ON DELETE CASCADE;
```
