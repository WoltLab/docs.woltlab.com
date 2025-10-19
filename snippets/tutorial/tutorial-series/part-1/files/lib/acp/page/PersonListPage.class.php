<?php

namespace wcf\acp\page;

use wcf\page\AbstractGridViewPage;
use wcf\system\gridView\admin\PersonGridView;

/**
 * Shows the list of people.
 *
 * @author      Matthias Schmidt
 * @copyright   2001-2025 WoltLab GmbH
 * @license     GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 *
 * @extends AbstractGridViewPage<PersonGridView>
 */
class PersonListPage extends AbstractGridViewPage
{
    /**
     * @inheritDoc
     */
    public $activeMenuItem = 'wcf.acp.menu.link.person.list';

    /**
     * @inheritDoc
     */
    public $neededPermissions = ['admin.content.canManagePeople'];

    #[\Override]
    protected function createGridView(): PersonGridView
    {
        return new PersonGridView();
    }
}
