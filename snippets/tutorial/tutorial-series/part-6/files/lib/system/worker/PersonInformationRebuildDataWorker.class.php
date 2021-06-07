<?php

namespace wcf\system\worker;

use wcf\data\person\information\PersonInformationList;
use wcf\system\user\activity\point\UserActivityPointHandler;

/**
 * Worker implementation for updating person information.
 *
 * @author  Matthias Schmidt
 * @copyright   2001-2021 WoltLab GmbH
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package WoltLabSuite\Core\System\Worker
 *
 * @method  PersonInformationList   getObjectList()
 */
class PersonInformationRebuildDataWorker extends AbstractRebuildDataWorker
{
    /**
     * @inheritDoc
     */
    protected $objectListClassName = PersonInformationList::class;

    /**
     * @inheritDoc
     */
    protected $limit = 500;

    /**
     * @inheritDoc
     */
    protected function initObjectList()
    {
        parent::initObjectList();

        $this->objectList->sqlOrderBy = 'person_information.personID';
    }

    /**
     * @inheritDoc
     */
    public function execute()
    {
        parent::execute();

        if (!$this->loopCount) {
            UserActivityPointHandler::getInstance()->reset('com.woltlab.wcf.people.information');
        }

        if (!\count($this->objectList)) {
            return;
        }

        $itemsToUser = [];
        foreach ($this->getObjectList() as $personInformation) {
            if ($personInformation->userID) {
                if (!isset($itemsToUser[$personInformation->userID])) {
                    $itemsToUser[$personInformation->userID] = 0;
                }

                $itemsToUser[$personInformation->userID]++;
            }
        }

        UserActivityPointHandler::getInstance()->fireEvents(
            'com.woltlab.wcf.people.information',
            $itemsToUser,
            false
        );
    }
}
