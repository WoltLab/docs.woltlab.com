---
title: User Notifications
sidebar: sidebar
permalink: php_api_user_notifications.html
folder: php/api
---

WoltLab Suite includes a powerful user notification system that supports notifications directly shown on the website and emails sent immediately or on a daily basis.


## `objectType.xml`

For any type of object related to events, you have to define an object type for the object type definition `com.woltlab.wcf.notification.objectType`:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<data xmlns="http://www.woltlab.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.woltlab.com http://www.woltlab.com/XSD/tornado/objectType.xsd">
	<import>
		<type>
			<name>com.woltlab.example.foo</name>
			<definitionname>com.woltlab.wcf.notification.objectType</definitionname>
			<classname>example\system\user\notification\object\type\FooUserNotificationObjectType</classname>
			<category>com.woltlab.example</category>
		</type>
	</import>
</data>
```

The referenced class `FooUserNotificationObjectType` has to implement the [IUserNotificationObjectType](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/user/notification/object/type/IUserNotificationObjectType.class.php) interface, which should be done by extending [AbstractUserNotificationObjectType](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/user/notification/object/type/AbstractUserNotificationObjectType.class.php).


```php
<?php
namespace example\system\user\notification\object\type;
use example\data\foo\Foo;
use example\data\foo\FooList;
use example\system\user\notification\object\FooUserNotificationObject;
use wcf\system\user\notification\object\type\AbstractUserNotificationObjectType;

/**
 * Represents a foo as a notification object type.
 * 
 * @author	Matthias Schmidt
 * @copyright	2001-2017 WoltLab GmbH
 * @license	WoltLab License <http://www.woltlab.com/license-agreement.html>
 * @package	WoltLabSuite\Example\System\User\Notification\Object\Type
 */
class FooUserNotificationObjectType extends AbstractUserNotificationObjectType {
	/**
	 * @inheritDoc
	 */
	protected static $decoratorClassName = FooUserNotificationObject::class;
	
	/**
	 * @inheritDoc
	 */
	protected static $objectClassName = Foo::class;
	
	/**
	 * @inheritDoc
	 */
	protected static $objectListClassName = FooList::class;
}
```

You have to set the class names of the database object (`$objectClassName`) and the related list (`$objectListClassName`).
Additionally, you have to create a class that implements the [IUserNotificationObject](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/user/notification/object/IUserNotificationObject.class.php) whose name you have to set as the value of the `$decoratorClassName` property. 

```php
<?php
namespace example\system\user\notification\object;
use example\data\foo\Foo;
use wcf\data\DatabaseObjectDecorator;
use wcf\system\user\notification\object\IUserNotificationObject;

/**
 * Represents a foo as a notification object.
 * 
 * @author	Matthias Schmidt
 * @copyright	2001-2017 WoltLab GmbH
 * @license	WoltLab License <http://www.woltlab.com/license-agreement.html>
 * @package	WoltLabSuite\Example\System\User\Notification\Object
 * 
 * @method	Foo	getDecoratedObject()
 * @mixin	Foo
 */
class FooUserNotificationObject extends DatabaseObjectDecorator implements IUserNotificationObject {
	/**
	 * @inheritDoc
	 */
	protected static $baseClass = Foo::class;
	
	/**
	 * @inheritDoc
	 */
	public function getTitle() {
		return $this->getDecoratedObject()->getTitle();
	}
	
	/**
	 * @inheritDoc
	 */
	public function getURL() {
		return $this->getDecoratedObject()->getLink();
	}
	
	/**
	 * @inheritDoc
	 */
	public function getAuthorID() {
		return $this->getDecoratedObject()->userID;
	}
}
```

- The `getTitle()` method returns the title of the object.
  In this case, we assume that the `Foo` class has implemented the [ITitledObject](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/data/ITitledObject.class.php) interface so that the decorated `Foo` can handle this method call itself.
- The `getURL()` method returns the link to the object.
  As for the `getTitle()`, we assume that the `Foo` class has implemented the [ILinkableObject](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/data/ILinkableObject.class.php) interface so that the decorated `Foo` can also handle this method call itself.
- The `getAuthorID()` method returns the id of the user who created the decorated `Foo` object.
  We assume that `Foo` objects have a `userID` property that contains this id.


## `userNotificationEvent.xml`

Each event that you fire in your package needs to be registered using the [user notification event package installation plugin](package_pip_user-notification-event.html).
An example file might look like this:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<data xmlns="http://www.woltlab.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.woltlab.com http://www.woltlab.com/XSD/tornado/userNotificationEvent.xsd">
	<import>
		<event>
			<name>bar</name>
			<objecttype>com.woltlab.example.foo</objecttype>
			<classname>example\system\user\notification\event\FooUserNotificationEvent</classname>
			<preset>1</preset>
		</event>
	</import>
</data>
```

Here, you reference the user notification object type created via `objectType.xml`.
The referenced class in the `<classname>` element has to implement the [IUserNotificationEvent](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/user/notification/event/IUserNotificationEvent.class.php) interface by extending the [AbstractUserNotificationEvent](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/user/notification/event/AbstractUserNotificationEvent.class.php) class or the [AbstractSharedUserNotificationEvent](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/user/notification/event/AbstractSharedUserNotificationEvent.class.php) class if you want to pre-load additional data before processing notifications.
In `AbstractSharedUserNotificationEvent::prepare()`, you can, for example, tell runtime caches to prepare to load certain objects which then are loaded all at once when the objects are needed.

```php
<?php
namespace example\system\user\notification\event;
use example\system\cache\runtime\BazRuntimeCache;
use example\system\user\notification\object\FooUserNotificationObject;
use wcf\system\email\Email;
use wcf\system\request\LinkHandler;
use wcf\system\user\notification\event\AbstractSharedUserNotificationEvent;

/**
 * Notification event for foos.
 * 
 * @author	Matthias Schmidt
 * @copyright	2001-2017 WoltLab GmbH
 * @license	WoltLab License <http://www.woltlab.com/license-agreement.html>
 * @package	WoltLabSuite\Example\System\User\Notification\Event
 * 
 * @method	FooUserNotificationObject	getUserNotificationObject()
 */
class FooUserNotificationEvent extends AbstractSharedUserNotificationEvent {
	/**
	 * @inheritDoc
	 */
	protected $stackable = true;
	
	/** @noinspection PhpMissingParentCallCommonInspection */
	/**
	 * @inheritDoc
	 */
	public function checkAccess() {
		$this->getUserNotificationObject()->setBaz(BazRuntimeCache::getInstance()->getObject($this->getUserNotificationObject()->bazID));
		
		if (!$this->getUserNotificationObject()->isAccessible()) {
			// do some cleanup, if necessary
			
			return false;
		}
		
		return true;
	}
	
	/** @noinspection PhpMissingParentCallCommonInspection */
	/**
	 * @inheritDoc
	 */
	public function getEmailMessage($notificationType = 'instant') {
		$this->getUserNotificationObject()->setBaz(BazRuntimeCache::getInstance()->getObject($this->getUserNotificationObject()->bazID));
		
		$messageID = '<com.woltlab.example.baz/'.$this->getUserNotificationObject()->bazID.'@'.Email::getHost().'>';
		
		return [
			'application' => 'example',
			'in-reply-to' => [$messageID],
			'message-id' => 'com.woltlab.example.foo/'.$this->getUserNotificationObject()->fooID,
			'references' => [$messageID],
			'template' => 'email_notification_foo'
		];
	}
	
	/**
	 * @inheritDoc
	 * @since	5.0
	 */
	public function getEmailTitle() {
		$this->getUserNotificationObject()->setBaz(BazRuntimeCache::getInstance()->getObject($this->getUserNotificationObject()->bazID));
		
		return $this->getLanguage()->getDynamicVariable('example.foo.notification.mail.title', [
			'userNotificationObject' => $this->getUserNotificationObject()
		]);
	}
	
	/** @noinspection PhpMissingParentCallCommonInspection */
	/**
	 * @inheritDoc
	 */
	public function getEventHash() {
		return sha1($this->eventID . '-' . $this->getUserNotificationObject()->bazID);
	}
	
	/**
	 * @inheritDoc
	 */
	public function getLink() {
		return LinkHandler::getInstance()->getLink('Foo', [
			'application' => 'example',
			'object' => $this->getUserNotificationObject()->getDecoratedObject()
		]);
	}
	
	/**
	 * @inheritDoc
	 */
	public function getMessage() {
		$authors = $this->getAuthors();
		$count = count($authors);
		
		if ($count > 1) {
			if (isset($authors[0])) {
				unset($authors[0]);
			}
			$count = count($authors);
			
			return $this->getLanguage()->getDynamicVariable('example.foo.notification.message.stacked', [
				'author' => $this->author,
				'authors' => array_values($authors),
				'count' => $count,
				'guestTimesTriggered' => $this->notification->guestTimesTriggered,
				'message' => $this->getUserNotificationObject(),
				'others' => $count - 1
			]);
		}
		
		return $this->getLanguage()->getDynamicVariable('example.foo.notification.message', [
			'author' => $this->author,
			'userNotificationObject' => $this->getUserNotificationObject()
		]);
	}
	
	/**
	 * @inheritDoc
	 */
	public function getTitle() {
		$count = count($this->getAuthors());
		if ($count > 1) {
			return $this->getLanguage()->getDynamicVariable('example.foo.notification.title.stacked', [
				'count' => $count,
				'timesTriggered' => $this->notification->timesTriggered
			]);
		}
		
		return $this->getLanguage()->get('example.foo.notification.title');
	}
	
	/**
	 * @inheritDoc
	 */
	protected function prepare() {
		BazRuntimeCache::getInstance()->cacheObjectID($this->getUserNotificationObject()->bazID);
	}
}
```

- The `$stackable` property is `false` by default and has to be explicitly set to `true` if stacking of notifications should be enabled.
  Stacking of notification does not create new notifications for the same event for a certain object if the related action as been triggered by different users.
  For example, if something is liked by one user and then liked again by another user before the recipient of the notification has confirmed it, the existing notification will be amended to include both users who liked the content.
  Stacking can thus be used to avoid cluttering the notification list of users.
- The `checkAccess()` method makes sure that the active user still has access to the object related to the notification.
  If that is not the case, the user notification system will automatically deleted the user notification based on the return value of the method.
  If you have any cached values related to notifications, you should also reset these values here.
- The `getEmailMessage()` method return data to create the instant email or the daily summary email.
  For instant emails (`$notificationType = 'instant'`), you have to return an array like the one shown in the code above with the following components:
  - `application`:
    abbreviation of application
  - `in-reply-to` (optional):
    message id of the notification for the parent item and used to improve the ordering in threaded email clients
  - `message-id` (optional):
    message id of the notification mail and has to be used in `in-reply-to` and `references` for follow up mails
  - `references` (optional):
    all of the message ids of parent items (i.e. recursive in-reply-to)
  - `template`:
    name of the template used to render the email body, should start with `email_`
  - `variables` (optional):
    template variables passed to the email template where they can be accessed via `$notificationContent[variables]`
  
  For daily emails (`$notificationType = 'daily'`), only `application`, `template`, and `variables` are supported.
- The `getEmailTitle()` returns the title of the instant email sent to the user.
  By default, `getEmailTitle()` simply calls `getTitle()`.
- The `getEventHash()` method returns a hash by which user notifications are grouped.
  Here, we want to group them not by the actual `Foo` object but by its parent `Baz` object and thus overwrite the default implementation provided by `AbstractUserNotificationEvent`.
- The `getLink()` returns the link to the `Foo` object the notification belongs to.
- The `getMessage()` method and the `getTitle()` return the message and the title of the user notification respectively.
  By checking the value of `count($this->getAuthors())`, we check if the notification is stacked, thus if the event has been triggered for multiple users so that different languages items are used.
  If your notification event does not support stacking, this distinction is not necessary.
- The `prepare()` method is called for each user notification before all user notifications are rendered.
  This allows to tell runtime caches to prepare to load objects later on (see [Runtime Caches](php_api_caches_runtime-caches.html)).


## Firing Events

When the action related to a user notification is executed, you can use `UserNotificationHandler::fireEvent()` to create the notifications:

```php
$recipientIDs = []; // fill with user ids of the recipients of the notification
UserNotificationHandler::getInstance()->fireEvent(
	'bar', // event name
	'com.woltlab.example.foo', // event object type name
	new FooUserNotificationObject(new Foo($fooID)), // object related to the event
	$recipientIDs
);
```


## Marking Notifications as Confirmed

In some instances, you might want to manually mark user notifications as confirmed without the user manually confirming them, for example when they visit the page that is related to the user notification.
In this case, you can use `UserNotificationHandler::markAsConfirmed()`:

```php
$recipientIDs = []; // fill with user ids of the recipients of the notification
$fooIDs = []; // fill with ids of related foo objects
UserNotificationHandler::getInstance()->markAsConfirmed(
	'bar', // event name
	'com.woltlab.example.foo', // event object type name
	$recipientIDs,
	$fooIDs
);
```
