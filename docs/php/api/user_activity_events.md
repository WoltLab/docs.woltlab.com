# User Activity Events

User activity events provide content from different sources for the list of recent activities. Entries in the last activities consist of a title, optionally a description, the author and the date.

## Registration of User Activity Events

To integrate user activity events into your package, you have to register object types for the defintion `com.woltlab.wcf.user.recentActivityEvent` and specify a class that implements the `wcf\system\user\activity\event\IUserActivityEvent` interface:

```xml
<type>
	<name>foo.bar.recentActivityEvent</name>
	<definitionname>com.woltlab.wcf.user.recentActivityEvent</definitionname>
	<classname>wcf\system\user\activity\event\FooUserActivityEvent</classname>
</type>
```

Specify multiple object types if you want to provide multiple types of user activity events.

Example of the implementation of the `wcf\system\user\activity\event\IUserActivityEvent` interface:

```php
<?php

namespace wcf\system\user\activity\event;

use wcf\data\user\activity\event\ViewableUserActivityEvent;
use wcf\system\user\activity\event\IUserActivityEvent;
use wcf\system\WCF;
use wcf\util\StringUtil;
use wcf\system\file\processor\ImageData;

final class FooUserActivityEvent extends SingletonFactory implements IUserActivityEvent
{
    public function prepare(array $events)
    {
        foreach ($events as $event) {
            $this->handleEvent($event);
        }
    }

    private function handleEvent(ViewableUserActivityEvent $event): void
    {
        $foo = new FooObject($event->objectID);
        if ($foo === null) {
            $event->setIsOrphaned();
            return;
        }

        $event->setIsAccessible();
        $event->setTitle(WCF::getLanguage()->getDynamicVariable('foo.bar.recentActivity', [
            'foo' => $foo,
            'author' => $event->getUserProfile(),
        ]));
        $event->setDescription(
            StringUtil::encodeHTML(
                StringUtil::truncate($foo->getPlainTextDescription(), 500)
            ),
            true
        );
        $event->setLink($foo->getLink());
        // Optionally set an image.
        $event->setImage(new ImageData('image_src', 800, 600));
    }
}
```

## Creating User Activity Events

If a relevant object is created, you have to use `UserActivityEventHandler::fireEvent()` which expects the name of the object type, the id of the object, the language id, the id of the user who created the object and the date.

```php
UserActivityEventHandler::getInstance()->fireEvent(
    'foo.bar.recentActivityEvent',
    1, // object id
    2, // language id
    3, // user id
    \TIME_NOW // date
);
```

The user id must always be passed explicitly, there is no fallback to the active user.
A user id of `null` denotes content created by a guest, which requires the guest's name to be passed as `username`:

```php
UserActivityEventHandler::getInstance()->fireEvent(
    'foo.bar.recentActivityEvent',
    $foo->fooID,
    null,
    $foo->userID, // `null` for guests
    $foo->time,
    username: $foo->username
);
```

If both the user id and the username are `null`, a `\BadMethodCallException` is thrown.
The same applies to `UserActivityEventHandler::fireEvents()`, which accepts an optional `username` key for each event.

`ViewableUserActivityEvent::getUserProfile()` returns a guest profile for these events, so they are rendered without any changes to your implementation of `IUserActivityEvent`.

## Removing User Activity Events

To remove user activity events once objects are deleted, you have to use `UserActivityEventHandler::removeEvents()` which also expects the name of the object type and additionally an array with object ids:

```php
UserActivityEventHandler::getInstance()->removeEvents(
    'foo.bar.recentActivityEvent',
    [1, 2]
);
```
