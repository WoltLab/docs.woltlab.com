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