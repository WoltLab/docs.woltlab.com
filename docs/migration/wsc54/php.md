# Migrating from WSC 5.4 - PHP

## File Deletion Package Installation Plugin

Three new package installation plugins have been added to delete ACP templates with [acpTemplateDelete](../../package/pip/acp-template-delete.md), files with [fileDelete](../../package/pip/file-delete.md), and templates with [templateDelete](../../package/pip/template-delete.md).


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

Event classes should not have an `Event` suffix and should be stored in an `event` namespace in a matching location.
Thus, the `UserLoggedIn` example might have a FQCN of `\wcf\system\user\authentication\event\UserLoggedIn`.

Event listeners for events implementing `IEvent` need to follow [PSR-14](https://www.php-fig.org/psr/psr-14/), i.e. they need to be callable.
In practice, this means that the event listener class needs to implement `__invoke()`.
No interface has to be implemented in this case.

Previously:

```php
$parameters = [
    'value' => \random_int(1, 1024),
];

EventHandler::getInstance()->fireAction($this, 'valueAvailable', $parameters);
```

{jinja{ codebox(
    language="php",
    title="lib/system/event/listener/ValueDumpListener.class.php",
    contents="""
<?php

namespace wcf\system\event\listener;

use wcf\form\ValueForm;

final class ValueDumpListener implements IParameterizedEventListener
{
    /**
     * @inheritDoc
     * @param ValueForm $eventObj
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
EventHandler::getInstance()->fire(new \wcf\system\foo\event\ValueAvailable(\random_int(1, 1024)));
```

{jinja{ codebox(
    language="php",
    title="lib/system/foo/event/ValueAvailable.class.php",
    contents="""
<?php

namespace wcf\system\foo\event;

use wcf\system\event\IEvent;

final class ValueAvailable implements IEvent
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
    title="lib/system/event/listener/ValueDumpListener.class.php",
    contents="""
<?php

namespace wcf\system\event\listener;

use wcf\system\foo\event\ValueAvailable;

final class ValueDumpListener
{
    public function __invoke(ValueAvailable $event)
    {
        var_dump($event->getValue());
    }
}
"""
) }}

See [WoltLab/WCF#4000](https://github.com/WoltLab/WCF/pull/4000) and [WoltLab/WCF#4265](https://github.com/WoltLab/WCF/pull/4265) for details.


## Embedded Objects in Comments

[WoltLab/WCF#4275](https://github.com/WoltLab/WCF/pull/4275) added support for embedded objects like mentions for comments and comment responses.
To properly render embedded objects whenever you are using comments in your packages, you have to use `ViewableCommentList`/`ViewableCommentResponseList` in these places or `ViewableCommentRuntimeCache`/`ViewableCommentResponseRuntimeCache`.
While these runtime caches are only available since version 5.5, the viewable list classes have always been available so that changing `CommentList` to `ViewableCommentList`, for example, is a backwards-compatible change.
