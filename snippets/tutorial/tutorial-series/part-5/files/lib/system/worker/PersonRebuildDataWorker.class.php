<?php

namespace wcf\system\worker;

use wcf\data\person\PersonList;
use wcf\system\WCF;

/**
 * Worker implementation for updating people.
 *
 * @author  Matthias Schmidt
 * @copyright   2001-2021 WoltLab GmbH
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package WoltLabSuite\Core\System\Worker
 *
 * @method  PersonList  getObjectList()
 */
class PersonRebuildDataWorker extends AbstractRebuildDataWorker
{
    /**
     * @inheritDoc
     */
    protected $limit = 500;

    /**
     * @inheritDoc
     */
    protected $objectListClassName = PersonList::class;

    /**
     * @inheritDoc
     */
    protected function initObjectList()
    {
        parent::initObjectList();

        $this->objectList->sqlOrderBy = 'person.personID';
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        parent::execute();

        if (!\count($this->objectList)) {
            return;
        }
        
        $sql = "UPDATE  wcf" . WCF_N . "_person person
                SET     informationCount = (
                            SELECT  COUNT(*)
                            FROM    wcf" . WCF_N . "_person_information person_information
                            WHERE   person_information.personID = person.personID
                        )
                WHERE   person.personID = ?";
        $statement = WCF::getDB()->prepareStatement($sql);
        
        WCF::getDB()->beginTransaction();
        foreach ($this->getObjectList() as $person) {
            $statement->execute([$person->personID]);
        }
        WCF::getDB()->commitTransaction();
    }
}
