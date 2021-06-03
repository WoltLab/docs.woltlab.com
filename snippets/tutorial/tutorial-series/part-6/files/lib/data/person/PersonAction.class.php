<?php

namespace wcf\data\person;

use wcf\data\AbstractDatabaseObjectAction;
use wcf\data\person\information\PersonInformationAction;
use wcf\data\person\information\PersonInformationList;

/**
 * Executes person-related actions.
 *
 * @author  Matthias Schmidt
 * @copyright   2001-2021 WoltLab GmbH
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package WoltLabSuite\Core\Data\Person
 *
 * @method  Person      create()
 * @method  PersonEditor[]  getObjects()
 * @method  PersonEditor    getSingleObject()
 */
class PersonAction extends AbstractDatabaseObjectAction
{
    /**
     * @inheritDoc
     */
    protected $permissionsDelete = ['admin.content.canManagePeople'];

    /**
     * @inheritDoc
     */
    protected $requireACP = ['delete'];

    /**
     * @inheritDoc
     */
    public function delete()
    {
        if (!empty($this->objectIDs)) {
            $informationList = new PersonInformationList();
            $informationList->getConditionBuilder()->add('personID IN (?)', [$this->objectIDs]);
            $informationList->readObjects();

            // Explicitly delete information to also update associated data like activity points.
            (new PersonInformationAction($informationList->getObjects(), 'delete', [
                // As the associated people are deleted, there is no need to update their
                // information counter.
                'ignoreInformationCount' => true,
            ]))->executeAction();
        }

        return parent::delete();
    }
}
