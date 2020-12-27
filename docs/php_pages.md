---
title: Page Types
sidebar: sidebar
permalink: php_pages.html
folder: php
---

## AbstractPage

The default implementation for pages to present any sort of content, but are designed to handle `GET` requests only. They usually follow a fixed method chain that will be invoked one after another, adding logical sections to the request flow.

### Method Chain

#### \__run()

This is the only method being invoked from the outside and starts the whole chain.

#### readParameters()

Reads and sanitizes request parameters, this should be the only method to ever read user-supplied input. Read data should be stored in class properties to be accessible at a later point, allowing your code to safely assume that the data has been sanitized and is safe to work with.

A typical example is the board page from the forum app that reads the id and attempts to identify the request forum.

```php
public function readParameters() {
	parent::readParameters();

	if (isset($_REQUEST['id'])) $this->boardID = intval($_REQUEST['id']);
	$this->board = BoardCache::getInstance()->getBoard($this->boardID);
	if ($this->board === null) {
		throw new IllegalLinkException();
	}

	// check permissions
	if (!$this->board->canEnter()) {
		throw new PermissionDeniedException();
	}
}
```

<span class="label label-info">Events</span> `readParameters`

#### show()

Used to be the method of choice to handle permissions and module option checks, but has been used almost entirely as an internal method since the introduction of the properties `$loginRequired`, `$neededModules` and `$neededPermissions`.

<span class="label label-info">Events</span> `checkModules`, `checkPermissions` and `show`

#### readData()

Central method for data retrieval based on class properties including those populated with user data in `readParameters()`. It is strongly recommended to use this method to read data in order to properly separate the business logic present in your class.

<span class="label label-info">Events</span> `readData`

#### assignVariables()

Last method call before the template engine kicks in and renders the template. All though some properties are bound to the template automatically, you still need to pass any custom variables and class properties to the engine to make them available in templates.

Following the example in `readParameters()`, the code below adds the board data to the template.

```php
public function assignVariables() {
	parent::assignVariables();

	WCF::getTPL()->assign([
		'board' => $this->board,
		'boardID' => $this->boardID
	]);
}
```

<span class="label label-info">Events</span> `assignVariables`

## AbstractForm

Extends the AbstractPage implementation with additional methods designed to handle form submissions properly.

### Method Chain

#### \__run()

*Inherited from AbstractPage.*

#### readParameters()

*Inherited from AbstractPage.*

#### show()

*Inherited from AbstractPage.*

#### submit()

{% include callout.html content="The methods `submit()` up until `save()` are only invoked if either `$_POST` or `$_FILES` are not empty, otherwise they won't be invoked and the execution will continue with `readData()`." type="warning" %}

This is an internal method that is responsible of input processing and validation.

<span class="label label-info">Events</span> `submit`

#### readFormParameters()

This method is quite similar to `readParameters()` that is being called earlier, but is designed around reading form data submitted through POST requests. You should avoid accessing `$_GET` or `$_REQUEST` in this context to avoid mixing up parameters evaluated when retrieving the page on first load and when submitting to it.

<span class="label label-info">Events</span> `readFormParameters`

#### validate()

Deals with input validation and automatically catches exceptions deriving from `wcf\system\exception\UserInputException`, resulting in a clean and consistent error handling for the user.

<span class="label label-info">Events</span> `validate`

#### save()

Saves the processed data to database or any other source of your choice. Please keep in mind to invoke `$this->saved()` before resetting the form data.

<span class="label label-info">Events</span> `save`

#### saved()

{% include callout.html content="This method is not called automatically and must be invoked manually by executing `$this->saved()` inside `save()`." type="warning" %}

The only purpose of this method is to fire the event `saved` that signals that the form data has been processed successfully and data has been saved. It is somewhat special as it is dispatched after the data has been saved, but before the data is purged during form reset. This is by default the last event that has access to the processed data.

<span class="label label-info">Events</span> `saved`

#### readData()

*Inherited from AbstractPage.*

#### assignVariables()

*Inherited from AbstractPage.*
