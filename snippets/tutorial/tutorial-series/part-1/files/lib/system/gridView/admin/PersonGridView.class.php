<?php

namespace wcf\system\gridView\admin;

use wcf\acp\form\PersonEditForm;
use wcf\data\person\PersonList;
use wcf\event\gridView\admin\PersonGridViewInitialized;
use wcf\system\gridView\AbstractGridView;
use wcf\system\gridView\GridViewColumn;
use wcf\system\gridView\GridViewRowLink;
use wcf\system\gridView\renderer\ObjectIdColumnRenderer;
use wcf\system\interaction\admin\PersonInteractions;
use wcf\system\interaction\Divider;
use wcf\system\interaction\EditInteraction;
use wcf\system\WCF;

/**
 * Grid view for the list of persons.
 *
 * @author      Marcel Werk
 * @copyright   2001-2025 WoltLab GmbH
 * @license     GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 *
 * @extends AbstractGridView<Person, PersonList>
 */
final class PersonGridView extends AbstractGridView
{
    public function __construct()
    {
        $this->addColumns([
            GridViewColumn::for('personID')
                ->label('wcf.global.objectID')
                ->renderer(new ObjectIdColumnRenderer())
                ->sortable(),
            GridViewColumn::for('firstName')
                ->label('wcf.person.firstName')
                ->sortable(),
            GridViewColumn::for('lastName')
                ->label('wcf.person.lastName')
                ->titleColumn()
                ->sortable(),
        ]);

        $provider = new PersonInteractions();
        $provider->addInteractions([
            new Divider(),
            new EditInteraction(PersonEditForm::class)
        ]);
        $this->setInteractionProvider($provider);

        $this->setDefaultSortField('personID');
        $this->addRowLink(new GridViewRowLink(PersonEditForm::class));
    }

    #[\Override]
    public function isAccessible(): bool
    {
        return WCF::getSession()->getPermission('admin.content.canManagePeople');
    }

    #[\Override]
    protected function createObjectList(): PersonList
    {
        return new PersonList();
    }

    #[\Override]
    protected function getInitializedEvent(): PersonGridViewInitialized
    {
        return new PersonGridViewInitialized($this);
    }
}
