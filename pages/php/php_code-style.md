---
title: Code Style
sidebar: sidebar
permalink: php_code-style.html
folder: php
---

## General Code Style

### Naming conventions

The relevant naming conventions are:

- **Upper camel case**:
  The first letters of all compound words are written in upper case.
- **Lower camel case**:
  The first letters of compound words are written in upper case, except for the first letter which is written in lower case.
- **Screaming snake case**:
  All letters are written in upper case and compound words are seperated by underscores.


|Type|Convention|Example|
|----|----------|-------|
|Variable|lower camel case|`$variableName`|
|Class|upper camel case|`class UserGroupEditor`|
|Properties|lower camel case|`public $propertyName`|
|Method|lower camel case|`public function getObjectByName()`|
|Constant|screaming snake case|`MODULE_USER_THING`|

### Arrays

For arrays, use the short array syntax introduced with PHP 5.4.
The following example illustrates the different cases that can occur when working with arrays and how to format them:

```php
<?php

$empty = [];

$oneElement = [1];
$multipleElements = [1, 2, 3];

$oneElementWithKey = ['firstElement' => 1];
$multipleElementsWithKey = [
	'firstElement' => 1,
	'secondElement' => 2,
	'thirdElement' => 3
];
```

### Whitespaces

You have to put a whitespace *in front* of the following things:

- equal sign in assigments: `$x = 1;`
- comparison operators: `$x == 1`
- opening bracket of a block `public function test() {`

You have to put a whitespace *behind* the following things:

- equal sign in assigments: `$x = 1;`
- comparison operators: `$x == 1`
- comma in a function/method parameter list if the comma is not followed by a line break: `public function test($a, $b) {`
- `if`, `for`, `foreach`, `while`: `if ($x == 1)`
