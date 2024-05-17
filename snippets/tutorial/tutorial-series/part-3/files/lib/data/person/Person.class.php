<?php

namespace wcf\data\person;

use wcf\data\DatabaseObject;
use wcf\data\ITitledLinkObject;
use wcf\page\PersonPage;
use wcf\system\request\LinkHandler;

/**
 * Represents a person.
 *
 * @author  Matthias Schmidt
 * @copyright   2001-2021 WoltLab GmbH
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package WoltLabSuite\Core\Data\Person
 *
 * @property-read   int     $personID   unique id of the person
 * @property-read   string  $firstName  first name of the person
 * @property-read   string  $lastName   last name of the person
 * @property-read   int     $enableComments     is `1` if comments are enabled for the person, otherwise `0`
 */
class Person extends DatabaseObject implements ITitledLinkObject
{
    /**
     * Returns the first and last name of the person if a person object is treated as a string.
     *
     * @return  string
     */
    public function __toString()
    {
        return $this->getTitle();
    }

    /**
     * @inheritDoc
     */
    public function getLink(): string
    {
        return LinkHandler::getInstance()->getControllerLink(PersonPage::class, [
            'object' => $this,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function getTitle(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}
