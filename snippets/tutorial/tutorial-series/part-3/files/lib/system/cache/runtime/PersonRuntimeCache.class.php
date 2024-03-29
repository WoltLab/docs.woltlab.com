<?php

namespace wcf\system\cache\runtime;

use wcf\data\person\Person;
use wcf\data\person\PersonList;

/**
 * Runtime cache implementation for people.
 *
 * @author  Matthias Schmidt
 * @copyright   2001-2022 WoltLab GmbH
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package WoltLabSuite\Core\System\Cache\Runtime
 *
 * @method  Person[]    getCachedObjects()
 * @method  Person      getObject($objectID)
 * @method  Person[]    getObjects(array $objectIDs)
 */
final class PersonRuntimeCache extends AbstractRuntimeCache
{
    /**
     * @inheritDoc
     */
    protected $listClassName = PersonList::class;
}
