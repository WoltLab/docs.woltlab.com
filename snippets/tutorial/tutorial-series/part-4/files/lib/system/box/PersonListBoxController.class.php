<?php

namespace wcf\system\box;

use wcf\data\person\PersonList;
use wcf\system\WCF;

/**
 * Dynamic box controller implementation for a list of persons.
 *
 * @author  Matthias Schmidt
 * @copyright   2001-2022 WoltLab GmbH
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package WoltLabSuite\Core\System\Box
 */
final class PersonListBoxController extends AbstractDatabaseObjectListBoxController
{
    /**
     * @inheritDoc
     */
    protected $conditionDefinition = 'com.woltlab.wcf.box.personList.condition';

    /**
     * @inheritDoc
     */
    public $defaultLimit = 5;

    /**
     * @inheritDoc
     */
    protected $sortFieldLanguageItemPrefix = 'wcf.person';

    /**
     * @inheritDoc
     */
    protected static $supportedPositions = [
        'sidebarLeft',
        'sidebarRight',
    ];

    /**
     * @inheritDoc
     */
    public $validSortFields = [
        'firstName',
        'lastName',
        'comments',
    ];

    /**
     * @inheritDoc
     */
    protected function getObjectList()
    {
        return new PersonList();
    }

    /**
     * @inheritDoc
     */
    protected function getTemplate()
    {
        return WCF::getTPL()->fetch('boxPersonList', 'wcf', [
            'boxPersonList' => $this->objectList,
            'boxSortField' => $this->sortField,
            'boxPosition' => $this->box->position,
        ], true);
    }
}
