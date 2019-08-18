---
title: User Activity Points
sidebar: sidebar
permalink: php_api_user_activity_points.html
folder: php/api
---

Users get activity points whenever they create content to award them for their contribution.
Activity points are used to determine the rank of a user and can also be used for user conditions, for example for automatic user group assignments.

To integrate activity points into your package, you have to register an object type for the defintion `com.woltlab.wcf.user.activityPointEvent` and specify a default number of points:

```xml
<type>
	<name>com.example.foo.activityPointEvent.bar</name>
	<definitionname>com.woltlab.wcf.user.activityPointEvent</definitionname>
	<points>10</points>
</type>
```

The number of points awarded for this type of activity point event can be changed by the administrator in the admin control panel.
For this form and the user activity point list shown in the frontend, you have to provide the language item

```
wcf.user.activityPoint.objectType.com.example.foo.activityPointEvent.bar
```

that contains the name of the content for which the activity points are awarded.

If a relevant object is created, you have to use `UserActivityPointHandler::fireEvent()` which expects the name of the activity point event object type, the id of the object for which the points are awarded (though the object id is not used at the moment) and the user who gets the points:

```php
UserActivityPointHandler::getInstance()->fireEvent(
        'com.example.foo.activityPointEvent.bar',
        $bar->barID,
        $bar->userID
);
```

To remove activity points once objects are deleted, you have to use `UserActivityPointHandler::removeEvents()` which also expects the name of the activity point event object type and additionally an array mapping the id of the user whose activity points will be reduced to the number of objects that are removed for the relevant user:

```php
UserActivityPointHandler::getInstance()->removeEvents(
        'com.example.foo.activityPointEvent.bar',
       [
                1 => 1, // remove points for one object for user with id `1`
                4 => 2  // remove points for two objects for user with id `4`
        ]
);
```
