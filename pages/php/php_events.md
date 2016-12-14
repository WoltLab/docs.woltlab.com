---
title: Events
sidebar: sidebar
permalink: php_events.html
folder: php
---

WoltLab Suite's event system allows manipulation of program flows and data without having to change any of the original source code.
At many locations throughout the PHP code of WoltLab Suite Core and mainly through inheritance also in the applications and plugins, so called *events* are fired which trigger registered *event listeners* that get access to the object firing the event (or at least the class name if the event has been fired in a static method).

This page focuses on the technical aspects of events and event listeners, [the eventListener package installation plugin page](package_pip_event-listener.html) covers how you can actually register an event listener.
A comprehensive list of all available events is provided [here](php_event_list.html).


## Introductory Example

Let's start with a simple example to illustrate how the event system works.
Consider this pre-existing class:

```php
<?php
namespace wcf\system\example;
use wcf\system\event\EventHandler;

class ExampleComponent {
	public $var = 1;
	
	public function getVar() {
		EventHandler::getInstance()->fireAction($this, 'getVar');
		
		return $this->var;
	}
}
```

where an event with event name `getVar` is fired in the `getVar()` method.

If you create an object of this class and call the `getVar()` method, the return value will be `1`, of course:

```php
<?php

$example = new wcf\system\example\ExampleComponent();
if ($example->getVar() == 1) {
	echo "var is 1!";
}
else if ($example->getVar() == 2) {
	echo "var is 2!";
}
else {
	echo "No, var is neither 1 nor 2.";
}

// output: var is 1!
```

Now, consider that we have registered the following event listener to this event:

```php
<?php
namespace wcf\system\event\listener;

class ExampleEventListener implements IParameterizedEventListener {
	public function execute($eventObj, $className, $eventName, array &$parameters) {
		$eventObj->var = 2;
	}
}

```

Whenever the event in the `getVar()` method is called, this method (of the same event listener object) is called.
In this case, the value of the method's first parameter is the `ExampleComponent` object passed as the first argument of the `EventHandler::fireAction()` call in `ExampleComponent::getVar()`.
As `ExampleComponent::$var` is a public property, the event listener code can change it and set it to `2`.

If you now execute the example code from above again, the output will change from `var is 1!` to `var is 2!` because prior to returning the value, the event listener code changes the value from `1` to `2`.

This introductory example illustrates how event listeners can change data in a non-intrusive way.
Program flow can be changed, for example, by throwing a `wcf\system\exception\PermissionDeniedException` if some additional constraint to access a page is not fulfilled.


## Listening to Events

In order to listen to events, you need to register the event listener and the event listener itself needs to implement the interface `wcf\system\event\listener\IParameterizedEventListener` which only contains the `execute` method (see example above).

The first parameter `$eventObj` of the method contains the passed object where the event is fired or the name of the class in which the event is fired if it is fired from a static method.
The second parameter `$className` always contains the name of the class where the event has been fired.
The third parameter `$eventName` provides the name of the event within a class to uniquely identify the exact location in the class where the event has been fired.
The last parameter `$parameters` is a reference to the array which contains additional data passed by the method firing the event.
If no additional data is passed, `$parameters` is empty.


## Firing Events

If you write code and want plugins to have access at certain points, you can fire an event on your own.
The only thing to do is to call the `wcf\system\event\EventHandler::fireAction($eventObj, $eventName, array &$parameters = [])` method and pass the following parameters:

1. `$eventObj` should be `$this` if you fire from an object context, otherwise pass the class name `static::class`.
2. `$eventName` identifies the event within the class and generally has the same name as the method.
   In cases, were you might fire more than one event in a method, for example before and after a certain piece of code, you can use the prefixes `before*` and `after*` in your event names.
3. `$parameters` is an optional array which allows you to pass additional data to the event listeners without having to make this data accessible via a property explicitly only created for this purpose.
   This additional data can either be just additional information for the event listeners about the context of the method call or allow the event listener to manipulate local data if the code, where the event has been fired, uses the passed data afterwards.  

### Example: Using `$parameters` argument

Consider the following method which gets some text that the methods parses.

```php
<?php
namespace wcf\system\example;
use wcf\system\event\EventHandler;

class ExampleParser {
	public function parse($text) {
		// [some parsing done by default]
		
		$parameters = ['text' => $text];
		EventHandler::getInstance()->fireAction($this, 'parse', $parameters);
		
		return $parameters['text'];
	}
}
```

After the default parsing by the method itself, the author wants to enable plugins to do additional parsing and thus fires an event and passes the parsed text as an additional parameter.
Then, a plugin can deliver the following event listener

```php
<?php
namespace wcf\system\event\listener;

class ExampleParserEventListener implements IParameterizedEventListener {
	public function execute($eventObj, $className, $eventName, array &$parameters) {
		$text = $parameters['text'];
		
		// [some additional parsing which changes $text]
		
		$parameters['text'] = $text;
	}
}
```

which can access the text via `$parameters['text']`.

This example can also be perfectly used to illustrate how to name multiple events in the same method.
Let's assume that the author wants to enable plugins to change the text before and after the method does its own parsing and thus fires two events:

```php
<?php
namespace wcf\system\example;
use wcf\system\event\EventHandler;

class ExampleParser {
	public function parse($text) {
		$parameters = ['text' => $text];
		EventHandler::getInstance()->fireAction($this, 'beforeParsing', $parameters);
		$text = $parameters['text'];
		
		// [some parsing done by default]
		
		$parameters = ['text' => $text];
		EventHandler::getInstance()->fireAction($this, 'afterParsing', $parameters);
		
		return $parameters['text'];
	}
}
```
