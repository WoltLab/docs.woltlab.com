<?php

namespace wcf\data\person;

use wcf\data\DatabaseObject;
use wcf\data\ITitledLinkObject;
use wcf\data\person\information\PersonInformation;
use wcf\data\person\information\PersonInformationList;
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
 * @property-read   int     $informationCount   number of pieces of information added for the person
 * @property-read   int     $enableComments     is `1` if comments are enabled for the person, otherwise `0`
 */
class Person extends DatabaseObject implements ITitledLinkObject
{
    /**
     * all pieces of information added for the person.
     * @var PersonInformation[]
     */
    protected $information;

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
    public function getLink()
    {
        return LinkHandler::getInstance()->getControllerLink(PersonPage::class, [
            'object' => $this,
        ]);
    }

    /**
     * Returns all pieces of information added for the person.
     *
     * @return  PersonInformation[]
     */
    public function getInformation(): array
    {
        if ($this->information === null) {
            $this->information = [];

            if ($this->informationCount) {
                $list = new PersonInformationList();
                $list->getConditionBuilder()->add('personID = ?', [$this->getObjectID()]);
                $list->sqlOrderBy = 'time DESC';
                $list->readObjects();

                $this->information = $list->getObjects();
            }
        }

        return $this->information;
    }

    /**
     * @inheritDoc
     */
    public function getTitle()
    {
        return $this->firstName . ' ' . $this->lastName;
    }
}
