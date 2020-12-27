---
title: Code Style
sidebar: sidebar
permalink: php_code-style.html
folder: php
---

{% include callout.html content="The following code style conventions are used by us for our own packages. While you do not have to follow every rule, you are encouraged to do so." type="info" %}

For information about how to document your code, please refer to the [documentation page](php_code-style_documentation.html).


## General Code Style

### Naming conventions

The relevant naming conventions are:

- **Upper camel case**:
  The first letters of all compound words are written in upper case.
- **Lower camel case**:
  The first letters of compound words are written in upper case, except for the first letter which is written in lower case.
- **Screaming snake case**:
  All letters are written in upper case and compound words are separated by underscores.


| Type | Convention | Example |
|------|------------|---------|
| Variable | lower camel case | `$variableName` |
| Class | upper camel case | `class UserGroupEditor` |
| Properties | lower camel case | `public $propertyName` |
| Method | lower camel case | `public function getObjectByName()` |
| Constant | screaming snake case | `MODULE_USER_THING` |

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

### Ternary Operator

The ternary operator can be used for short conditioned assignments:

```php
<?php

$name = isset($tagArgs['name']) ? $tagArgs['name'] : 'default';
```

The condition and the values should be short so that the code does not result in a very long line which thus decreases the readability compared to an `if-else` statement.

Parentheses may only be used around the condition and not around the whole statement:

```php
<?php

// do not do it like this
$name = (isset($tagArgs['name']) ? $tagArgs['name'] : 'default');
```

Parentheses around the conditions may not be used to wrap simple function calls:

```php
<?php

// do not do it like this
$name = (isset($tagArgs['name'])) ? $tagArgs['name'] : 'default';
```

but have to be used for comparisons or other binary operators:

```php
<?php

$value = ($otherValue > $upperLimit) ? $additionalValue : $otherValue;
``` 

If you need to use more than one binary operator, use an `if-else` statement.

The same rules apply to assigning array values:

```php
<?php

$values = [
	'first' => $firstValue,
	'second' => $secondToggle ? $secondValueA : $secondValueB,
	'third' => ($thirdToogle > 13) ? $thirdToogleA : $thirdToogleB
];
```

or return values:

```php
<?php

return isset($tagArgs['name']) ? $tagArgs['name'] : 'default';
```

### Whitespaces

You have to put a whitespace *in front* of the following things:

- equal sign in assignments: `$x = 1;`
- comparison operators: `$x == 1`
- opening bracket of a block `public function test() {`

You have to put a whitespace *behind* the following things:

- equal sign in assignments: `$x = 1;`
- comparison operators: `$x == 1`
- comma in a function/method parameter list if the comma is not followed by a line break: `public function test($a, $b) {`
- `if`, `for`, `foreach`, `while`: `if ($x == 1)`


## Classes

### Referencing Class Names

If you have to reference a class name inside a php file, you have to use the `class` keyword.

```php
<?php

// not like this
$className = 'wcf\data\example\Example';

// like this
use wcf\data\example\Example;
$className = Example::class;
```

### Static Getters (of `DatabaseObject` Classes)

Some database objects provide static getters, either if they are decorators or for a unique combination of database table columns, like `wcf\data\box\Box::getBoxByIdentifier()`:

```php
<?php
namespace wcf\data\box;
use wcf\data\DatabaseObject;
use wcf\system\WCF;

class Box extends DatabaseObject { 
	/**
	 * Returns the box with the given identifier.
	 *
	 * @param	string		$identifier
	 * @return	Box|null
	 */
	public static function getBoxByIdentifier($identifier) {
		$sql = "SELECT	*
			FROM	wcf".WCF_N."_box
			WHERE	identifier = ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute([$identifier]);
		
		return $statement->fetchObject(self::class);
	}
}
```

Such methods should always either return the desired object or `null` if the object does not exist.
`wcf\system\database\statement\PreparedStatement::fetchObject()` already takes care of this distinction so that its return value can simply be returned by such methods.

The name of such getters should generally follow the convention `get{object type}By{column or other description}`.

### Long method calls

In some instances, methods with many argument have to be called which can result in lines of code like this one:

```php
<?php

\wcf\system\search\SearchIndexManager::getInstance()->set('com.woltlab.wcf.article', $articleContent->articleContentID, $articleContent->content, $articleContent->title, $articles[$articleContent->articleID]->time, $articles[$articleContent->articleID]->userID, $articles[$articleContent->articleID]->username, $articleContent->languageID, $articleContent->teaser);
```

which is hardly readable.
Therefore, the line must be split into multiple lines with each argument in a separate line:

```php
<?php

\wcf\system\search\SearchIndexManager::getInstance()->set(
	'com.woltlab.wcf.article',
	$articleContent->articleContentID,
	$articleContent->content,
	$articleContent->title,
	$articles[$articleContent->articleID]->time,
	$articles[$articleContent->articleID]->userID,
	$articles[$articleContent->articleID]->username,
	$articleContent->languageID,
	$articleContent->teaser
);
```

In general, this rule applies to the following methods:

- `wcf\system\edit\EditHistoryManager::add()`
- `wcf\system\message\quote\MessageQuoteManager::addQuote()`
- `wcf\system\message\quote\MessageQuoteManager::getQuoteID()`
- `wcf\system\search\SearchIndexManager::set()`
- `wcf\system\user\object\watch\UserObjectWatchHandler::updateObject()`
- `wcf\system\user\notification\UserNotificationHandler::fireEvent()`
