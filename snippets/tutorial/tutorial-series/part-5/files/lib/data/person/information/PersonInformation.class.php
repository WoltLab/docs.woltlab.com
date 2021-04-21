<?php

namespace wcf\data\person\information;

use wcf\data\DatabaseObject;
use wcf\data\person\Person;
use wcf\data\user\UserProfile;
use wcf\system\cache\runtime\PersonRuntimeCache;
use wcf\system\cache\runtime\UserProfileRuntimeCache;
use wcf\system\html\output\HtmlOutputProcessor;
use wcf\system\WCF;

/**
 * Represents a piece of information for a person.
 *
 * @author  Matthias Schmidt
 * @copyright   2001-2021 WoltLab GmbH
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package WoltLabSuite\Core\Data\Person\Information
 *
 * @property-read   int         $informationID  unique id of the information
 * @property-read   int         $personID       id of the person the information belongs to
 * @property-read   string      $information    information text
 * @property-read   int|null    $userID         id of the user who added the information or `null` if the user no longer exists
 * @property-read   string      $username       name of the user who added the information
 * @property-read   int         $time           timestamp at which the information was created
 */
class PersonInformation extends DatabaseObject
{
    /**
     * Returns `true` if the active user can delete this piece of information and `false` otherwise.
     */
    public function canDelete(): bool
    {
        if (
            WCF::getUser()->userID
            && WCF::getUser()->userID == $this->userID
            && WCF::getSession()->getPermission('user.person.canDeleteInformation')
        ) {
            return true;
        }

        return WCF::getSession()->getPermission('mod.person.canDeleteInformation');
    }

    /**
     * Returns `true` if the active user can edit this piece of information and `false` otherwise.
     */
    public function canEdit(): bool
    {
        if (
            WCF::getUser()->userID
            && WCF::getUser()->userID == $this->userID
            && WCF::getSession()->getPermission('user.person.canEditInformation')
        ) {
            return true;
        }

        return WCF::getSession()->getPermission('mod.person.canEditInformation');
    }

    /**
     * Returns the formatted information.
     */
    public function getFormattedInformation(): string
    {
        $processor = new HtmlOutputProcessor();
        $processor->process(
            $this->information,
            'com.woltlab.wcf.people.information',
            $this->informationID
        );

        return $processor->getHtml();
    }

    /**
     * Returns the person the information belongs to.
     */
    public function getPerson(): Person
    {
        return PersonRuntimeCache::getInstance()->getObject($this->personID);
    }

    /**
     * Returns the user profile of the user who added the information.
     */
    public function getUserProfile(): UserProfile
    {
        if ($this->userID) {
            return UserProfileRuntimeCache::getInstance()->getObject($this->userID);
        } else {
            return UserProfile::getGuestUserProfile($this->username);
        }
    }
}
