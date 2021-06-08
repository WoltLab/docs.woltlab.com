# Migrating from WSC 5.4 - PHP

## Language Package Installation Plugin

[WoltLab/WCF#4261](https://github.com/WoltLab/WCF/pull/4261) has added support for deleting existing phrases with the `language` package installation plugin.

The current structure of the language XML files

{jinja{ codebox(
    language="xml",
    title="language/en.xml",
    filepath="migration/wsc54/en_old.xml",
) }}

is deprecated and should be replaced with the new structure with an explicit `<import>` element like in the other package installation plugins:

{jinja{ codebox(
    language="xml",
    title="language/en.xml",
    filepath="migration/wsc54/en_new.xml",
) }}

Additionally, to now also support deleting phrases with this package installation plugin, support for a `<delete>` element has been added: 

{jinja{ codebox(
    language="xml",
    title="language/en.xml",
    filepath="migration/wsc54/en_new_with_delete.xml",
) }}

Note that when deleting phrases, the category does not have to be specified because phrase identifiers are unique globally.

!!! warning "Mixing the old structure and the new structure is not supported and will result in an error message during the import!"

## Events

Historically, events were tightly coupled with a single class, with the event object being an object of this class, expecting the event listener to consume public properties and method of the event object.
The `$parameters` array was introduced due to limitations of this pattern, avoiding moving all the values that might be of interest to the event listener into the state of the object.
Events were still tightly coupled with the class that fired the event and using the opaque parameters array prevented IDEs from assisting with autocompletion and typing.

WoltLab Suite 5.5 introduces the concept of dedicated, reusable event classes.
Any newly introduced event will receive a dedicated class, implementing the `wcf\system\event\IEvent` interface.
These event classes may be fired from multiple locations, making them reusable to convey that a conceptual action happened, instead of a specific class doing something.
An example for using the new event system could be a user logging in:
Instead of listening on a the login form being submitted and the Facebook login action successfully running, an event `UserLoggedIn` might be fired whenever a user logs in, no matter how the login is performed.

Additionally, these dedicated event classes will benefit from full IDE support.
All the relevant values may be stored as real properties on the event object.

Previously:

```php
$parameters = [
    'value' => \random_int(1, 1024),
];

EventHandler::getInstance()->fireAction($this, 'foo', $parameters);
```

{jinja{ codebox(
    language="php",
    title="lib/system/event/listener/FooDumpListener.class.php",
    contents="""
<?php

namespace wcf\system\event\listener;

use wcf\form\FooForm;

final class FooDumpListener implements IParameterizedEventListener
{
    /**
     * @inheritDoc
     * @param FooForm $eventObj
     */
    public function execute($eventObj, $className, $eventName, array &$parameters)
    {
        var_dump($parameters['value']);
    }
}
"""
) }}

Now:

```
EventHandler::getInstance()->fire(new \wcf\system\foo\Event(\random_int(1, 1024)));
```

{jinja{ codebox(
    language="php",
    title="lib/system/foo/Event.class.php",
    contents="""
<?php

namespace wcf\system\foo;

use wcf\system\event\IEvent;

final class Event implements IEvent
{
    /**
     * @var int
     */
    private $value;

    public function __construct(int $value)
    {
        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }
}
"""
) }}

{jinja{ codebox(
    language="php",
    title="lib/system/event/listener/FooDumpListener.class.php",
    contents="""
<?php

namespace wcf\system\event\listener;

use wcf\system\foo\Event as FooEvent;

final class FooDumpListener implements IParameterizedEventListener
{
    /**
     * @inheritDoc
     * @param FooEvent $eventObj
     */
    public function execute($eventObj, $className, $eventName, array &$parameters)
    {
        var_dump($eventObj->getValue());
    }
}
"""
) }}

See [WoltLab/WCF#4000](https://github.com/WoltLab/WCF/pull/4000) and [WoltLab/WCF#4265](https://github.com/WoltLab/WCF/pull/4265) for details.
