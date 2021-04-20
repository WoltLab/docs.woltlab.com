<?php

namespace wcf\system\event\listener;

use wcf\acp\form\PersonAddForm;
use wcf\form\AbstractFormBuilderForm;
use wcf\system\form\builder\container\FormContainer;
use wcf\system\form\builder\field\DateFormField;

/**
 * Handles setting the birthday when adding and editing people.
 *
 * @author  Matthias Schmidt
 * @copyright   2001-2021 WoltLab GmbH
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package WoltLabSuite\Core\System\Event\Listener
 */
class BirthdayPersonAddFormListener extends AbstractEventListener
{
    /**
     * @see AbstractFormBuilderForm::createForm()
     */
    protected function onCreateForm(PersonAddForm $form): void
    {
        /** @var FormContainer $dataContainer */
        $dataContainer = $form->form->getNodeById('data');
        $dataContainer->appendChild(
            DateFormField::create('birthday')
                ->label('wcf.person.birthday')
                ->saveValueFormat('Y-m-d')
                ->nullable()
        );
    }
}
