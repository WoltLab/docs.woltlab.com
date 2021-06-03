<?php

namespace wcf\data\person\information;

use wcf\data\DatabaseObjectList;
use wcf\system\cache\runtime\PersonRuntimeCache;
use wcf\system\cache\runtime\UserProfileRuntimeCache;

/**
 * Represents a list of person information.
 *
 * @author  Matthias Schmidt
 * @copyright   2001-2021 WoltLab GmbH
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package WoltLabSuite\Core\Data\PersonInformation
 *
 * @method      PersonInformation       current()
 * @method      PersonInformation[]     getObjects()
 * @method      PersonInformation|null  search($objectID)
 * @property    PersonInformation[]     $objects
 */
class PersonInformationList extends DatabaseObjectList
{
    public function readObjects()
    {
        parent::readObjects();

        UserProfileRuntimeCache::getInstance()->cacheObjectIDs(\array_unique(\array_filter(\array_column(
            $this->objects,
            'userID'
        ))));
        PersonRuntimeCache::getInstance()->cacheObjectIDs(\array_unique(\array_column(
            $this->objects,
            'personID'
        )));
    }
}
