# Part 6: Activity Points and Activity Events

In this part of our tutorial series, we use the person information added in the [previous part](part_5.md) to award activity points to users adding new pieces of information and to also create activity events for these pieces of information.


## Package Functionality

In addition to the existing functions from [part 5](part_5.md), the package will provide the following functionality after this part of the tutorial:

- Users are awarded activity points for adding new pieces of information for people.
- If users add new pieces of information for people, activity events are added which are then shown in the list of recent activities.


## Used Components

In addition to the components used in previous parts, we will use the [user activity points API](../../php/api/user_activity_points.md) and the user activity events API.


## Package Structure

The package will have the following file structure _excluding_ unchanged files from previous parts:

```
├── files
│   └── lib
│       ├── data
│       │   └── person
│       │       ├── PersonAction.class.php
│       │       └── information
│       │           ├── PersonInformation.class.php
│       │           └── PersonInformationAction.class.php
│       └── system
│           ├── user
│           │   └── activity
│           │       └── event
│           │           └── PersonInformationUserActivityEvent.class.php
│           └── worker
│               ├── PersonInformationRebuildDataWorker.class.php
│               └── PersonRebuildDataWorker.class.php
├── eventListener.xml
├── language
│   ├── de.xml
│   └── en.xml
└── objectType.xml
```

For all changes, please refer to the [source code on GitHub]({jinja{ config.repo_url }}tree/{jinja{ config.edit_uri.split("/")[1] }}/snippets/tutorial/tutorial-series/part-6).


## User Activity Points

The first step to support activity points is to register an object type for the `com.woltlab.wcf.user.activityPointEvent` object type definition for created person information and specify the default number of points awarded per piece of information:

```xml title="objectType.xml"
<type>
    <name>com.woltlab.wcf.people.information</name>
    <definitionname>com.woltlab.wcf.user.activityPointEvent</definitionname>
    <points>2</points>
</type>
```

Additionally, the phrase `wcf.user.activityPoint.objectType.com.woltlab.wcf.people.information` (in general: `wcf.user.activityPoint.objectType.{objectType}`) has to be added.

The activity points are awarded when new pieces are created via `PersonInformation::create()` using `UserActivityPointHandler::fireEvent()` and removed in `PersonInformation::create()` via `UserActivityPointHandler::removeEvents()` if pieces of information are deleted.

Lastly, we have to add two components for updating data:
First, we register a new rebuild data worker

```xml title="objectType.xml"
<type>
    <name>com.woltlab.wcf.people.information</name>
    <definitionname>com.woltlab.wcf.rebuildData</definitionname>
    <classname>wcf\system\worker\PersonInformationRebuildDataWorker</classname>
</type>
```

{jinja{ codebox(
    title="files/lib/system/worker/PersonInformationRebuildDataWorker.class.php",
    language="php",
    filepath="tutorial/tutorial-series/part-6/files/lib/system/worker/PersonInformationRebuildDataWorker.class.php"
) }}

which updates the number of instances for which any user received person information activity points.
(This data worker also requires the phrases `wcf.acp.rebuildData.com.woltlab.wcf.people.information` and `wcf.acp.rebuildData.com.woltlab.wcf.people.information.description`).

Second, we add an event listener for `UserActivityPointItemsRebuildDataWorker` to update the total user activity points awarded for person information:

```xml title="eventListener.xml"
<eventlistener name="execute@wcf\system\worker\UserActivityPointItemsRebuildDataWorker">
    <eventclassname>wcf\system\worker\UserActivityPointItemsRebuildDataWorker</eventclassname>
    <eventname>execute</eventname>
    <listenerclassname>wcf\system\event\listener\PersonUserActivityPointItemsRebuildDataWorkerListener</listenerclassname>
    <environment>admin</environment>
</eventlistener>
```

{jinja{ codebox(
    title="files/lib/system/event/listener/PersonUserActivityPointItemsRebuildDataWorkerListener.class.php",
    language="php",
    filepath="tutorial/tutorial-series/part-6/files/lib/system/event/listener/PersonUserActivityPointItemsRebuildDataWorkerListener.class.php"
) }}


## User Activity Events

To support user activity events, an object type for `com.woltlab.wcf.user.recentActivityEvent` has to be registered with a class implementing `wcf\system\user\activity\event\IUserActivityEvent`:

```xml title="objectType.xml"
<type>
    <name>com.woltlab.wcf.people.information</name>
    <definitionname>com.woltlab.wcf.user.recentActivityEvent</definitionname>
    <classname>wcf\system\user\activity\event\PersonInformationUserActivityEvent</classname>
</type>
```

{jinja{ codebox(
    title="files/lib/system/user/activity/event/PersonInformationUserActivityEvent.class.php",
    language="php",
    filepath="tutorial/tutorial-series/part-6/files/lib/system/user/activity/event/PersonInformationUserActivityEvent.class.php"
) }}

`PersonInformationUserActivityEvent::prepare()` must check for all events whether the associated piece of information still exists and if it is the case, mark the event as accessible via the `setIsAccessible()` method, set the title of the activity event via `setTitle()`, and set a description of the event via `setDescription()` for which we use the newly added `PersonInformation::getFormattedExcerpt()` method.

Lastly, we have to add the phrase `wcf.user.recentActivity.com.woltlab.wcf.people.information`, which is shown in the list of activity events as the type of activity event.
