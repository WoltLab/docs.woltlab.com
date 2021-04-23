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