# User Notifications

WoltLab Suite includes a powerful user notification system that supports notifications directly shown on the website and emails sent immediately or on a daily basis.


## `objectType.xml`

For any type of object related to events, you have to define an object type for the object type definition `com.woltlab.wcf.notification.objectType`:

{jinja{ codebox(
  "xml",
  "php/api/user_notifications/objectType.xml",
  "objectType.xml"
) }}

The referenced class `FooUserNotificationObjectType` has to implement the [IUserNotificationObjectType](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/user/notification/object/type/IUserNotificationObjectType.class.php) interface, which should be done by extending [AbstractUserNotificationObjectType](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/user/notification/object/type/AbstractUserNotificationObjectType.class.php).

{jinja{ codebox(
  "php",
  "php/api/user_notifications/FooUserNotificationObjectType.class.php",
  "files/lib/system/user/notification/object/type/FooUserNotificationObjectType.class.php"
) }}

You have to set the class names of the database object (`$objectClassName`) and the related list (`$objectListClassName`).
Additionally, you have to create a class that implements the [IUserNotificationObject](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/user/notification/object/IUserNotificationObject.class.php) whose name you have to set as the value of the `$decoratorClassName` property.

{jinja{ codebox(
  "php",
  "php/api/user_notifications/FooUserNotificationObject.class.php",
  "files/lib/system/user/notification/object/FooUserNotificationObject.class.php"
) }}

- The `getTitle()` method returns the title of the object.
  In this case, we assume that the `Foo` class has implemented the [ITitledObject](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/data/ITitledObject.class.php) interface so that the decorated `Foo` can handle this method call itself.
- The `getURL()` method returns the link to the object.
  As for the `getTitle()`, we assume that the `Foo` class has implemented the [ILinkableObject](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/data/ILinkableObject.class.php) interface so that the decorated `Foo` can also handle this method call itself.
- The `getAuthorID()` method returns the id of the user who created the decorated `Foo` object.
  We assume that `Foo` objects have a `userID` property that contains this id.


## `userNotificationEvent.xml`

Each event that you fire in your package needs to be registered using the [user notification event package installation plugin](../../package/pip/user-notification-event.md).
An example file might look like this:

{jinja{ codebox(
  "xml",
  "php/api/user_notifications/userNotificationEvent.xml",
  "userNotificationEvent.xml"
) }}

Here, you reference the user notification object type created via `objectType.xml`.
The referenced class in the `<classname>` element has to implement the [IUserNotificationEvent](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/user/notification/event/IUserNotificationEvent.class.php) interface by extending the [AbstractUserNotificationEvent](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/user/notification/event/AbstractUserNotificationEvent.class.php) class or the [AbstractSharedUserNotificationEvent](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/user/notification/event/AbstractSharedUserNotificationEvent.class.php) class if you want to pre-load additional data before processing notifications.
In `AbstractSharedUserNotificationEvent::prepare()`, you can, for example, tell runtime caches to prepare to load certain objects which then are loaded all at once when the objects are needed.

{jinja{ codebox(
  "php",
  "php/api/user_notifications/FooUserNotificationEvent.class.php",
  "files/lib/system/user/notification/event/FooUserNotificationEvent.class.php"
) }}

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
  This allows to tell runtime caches to prepare to load objects later on (see [Runtime Caches](caches_runtime-caches.md)).


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
