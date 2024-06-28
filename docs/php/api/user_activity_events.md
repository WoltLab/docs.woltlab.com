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

final class FooUserActivityEvent implements IUserActivityEvent
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
    }
}
```

## Creating User Activity Events

If a relevant object is created, you have to use `UserActivityEventHandler::fireEvent()` which expects the name of the object type, the id of the object and optionally the language id, user id and the date.

```php
UserActivityEventHandler::getInstance()->fireEvent(
    'foo.bar.recentActivityEvent',
    1, // object id
    2, // language id
    3, // user id
    \TIME_NOW // date
);
```

## Removing User Activity Events

To remove user activity events once objects are deleted, you have to use `UserActivityEventHandler::removeEvents()` which also expects the name of the object type and additionally an array with object ids:

```php
UserActivityEventHandler::getInstance()->removeEvents(
    'foo.bar.recentActivityEvent',
    [1, 2]
);
```
