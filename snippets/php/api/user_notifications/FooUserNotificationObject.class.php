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