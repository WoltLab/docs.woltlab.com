<?php

namespace wcf\data\person\information;

use wcf\data\AbstractDatabaseObjectAction;
use wcf\data\person\PersonAction;
use wcf\data\person\PersonEditor;
use wcf\system\cache\runtime\PersonRuntimeCache;
use wcf\system\event\EventHandler;
use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\exception\UserInputException;
use wcf\system\form\builder\container\wysiwyg\WysiwygFormContainer;
use wcf\system\form\builder\DialogFormDocument;
use wcf\system\html\input\HtmlInputProcessor;
use wcf\system\user\activity\event\UserActivityEventHandler;
use wcf\system\user\activity\point\UserActivityPointHandler;
use wcf\system\WCF;
use wcf\util\UserUtil;

/**
 * Executes person information-related actions.
 *
 * @author  Matthias Schmidt
 * @copyright   2001-2021 WoltLab GmbH
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package WoltLabSuite\Core\Data\Person\Information
 *
 * @method  PersonInformationEditor[]   getObjects()
 * @method  PersonInformationEditor     getSingleObject()
 */
class PersonInformationAction extends AbstractDatabaseObjectAction
{
    /**
     * @var DialogFormDocument
     */
    public $dialog;

    /**
     * @var PersonInformation
     */
    public $information;

    /**
     * @return  PersonInformation
     */
    public function create()
    {
        if (!isset($this->parameters['data']['time'])) {
            $this->parameters['data']['time'] = TIME_NOW;
        }
        if (!isset($this->parameters['data']['userID'])) {
            $this->parameters['data']['userID'] = WCF::getUser()->userID;
            $this->parameters['data']['username'] = WCF::getUser()->username;
        }

        if (LOG_IP_ADDRESS) {
            if (!isset($this->parameters['data']['ipAddress'])) {
                $this->parameters['data']['ipAddress'] = UserUtil::getIpAddress();
            }
        } else {
            unset($this->parameters['data']['ipAddress']);
        }

        if (!empty($this->parameters['information_htmlInputProcessor'])) {
            /** @var HtmlInputProcessor $htmlInputProcessor */
            $htmlInputProcessor = $this->parameters['information_htmlInputProcessor'];
            $this->parameters['data']['information'] = $htmlInputProcessor->getHtml();
        }

        /** @var PersonInformation $information */
        $information = parent::create();

        (new PersonAction([$information->personID], 'update', [
            'counters' => [
                'informationCount' => 1,
            ],
        ]))->executeAction();

        UserActivityPointHandler::getInstance()->fireEvent(
            'com.woltlab.wcf.people.information',
            $information->getObjectID(),
            $information->userID
        );

        UserActivityEventHandler::getInstance()->fireEvent(
            'com.woltlab.wcf.people.information',
            $information->getObjectID(),
            null,
            $information->userID,
            $information->time
        );

        return $information;
    }

    /**
     * @inheritDoc
     */
    public function update()
    {
        if (!empty($this->parameters['information_htmlInputProcessor'])) {
            /** @var HtmlInputProcessor $htmlInputProcessor */
            $htmlInputProcessor = $this->parameters['information_htmlInputProcessor'];
            $this->parameters['data']['information'] = $htmlInputProcessor->getHtml();
        }

        parent::update();
    }

    /**
     * @inheritDoc
     */
    public function validateDelete()
    {
        if (empty($this->objects)) {
            $this->readObjects();

            if (empty($this->objects)) {
                throw new UserInputException('objectIDs');
            }
        }

        foreach ($this->getObjects() as $informationEditor) {
            if (!$informationEditor->canDelete()) {
                throw new PermissionDeniedException();
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function delete()
    {
        $deleteCount = parent::delete();

        if (!$deleteCount) {
            return $deleteCount;
        }

        $informationCountPerUser = [];
        $counterUpdates = [];
        foreach ($this->getObjects() as $informationEditor) {
            if (!isset($counterUpdates[$informationEditor->personID])) {
                $counterUpdates[$informationEditor->personID] = 0;
            }

            $counterUpdates[$informationEditor->personID]--;

            if ($informationEditor->userID) {
                if (!isset($informationCountPerUser[$informationEditor->userID])) {
                    $informationCountPerUser[$informationEditor->userID] = 0;
                }

                $informationCountPerUser[$informationEditor->userID]++;
            }
        }

        if (!empty($this->parameters['ignoreInformationCount'])) {
            WCF::getDB()->beginTransaction();
            foreach ($counterUpdates as $personID => $counterUpdate) {
                (new PersonEditor(PersonRuntimeCache::getInstance()->getObject($personID)))
                    ->updateCounters([
                        'informationCount' => $counterUpdate,
                    ]);
            }
            WCF::getDB()->commitTransaction();
        }

        UserActivityPointHandler::getInstance()->removeEvents(
            'com.woltlab.wcf.people.information',
            $informationCountPerUser
        );

        UserActivityEventHandler::getInstance()->removeEvents(
            'com.woltlab.wcf.people.information',
            $this->objectIDs
        );

        return $deleteCount;
    }

    /**
     * Validates the `getAddDialog` action.
     */
    public function validateGetAddDialog(): void
    {
        WCF::getSession()->checkPermissions(['user.person.canAddInformation']);

        $this->readInteger('personID');
        if (PersonRuntimeCache::getInstance()->getObject($this->parameters['personID']) === null) {
            throw new UserInputException('personID');
        }
    }

    /**
     * Returns the data to show the dialog to add a new piece of information on a person.
     *
     * @return  string[]
     */
    public function getAddDialog(): array
    {
        $this->buildDialog();

        return [
            'dialog' => $this->dialog->getHtml(),
            'formId' => $this->dialog->getId(),
        ];
    }

    /**
     * Validates the `submitAddDialog` action.
     */
    public function validateSubmitAddDialog(): void
    {
        $this->validateGetAddDialog();

        $this->buildDialog();
        $this->dialog->requestData($_POST['parameters']['data'] ?? []);
        $this->dialog->readValues();
        $this->dialog->validate();
    }

    /**
     * Creates a new piece of information on a person after submitting the dialog.
     *
     * @return  string[]
     */
    public function submitAddDialog(): array
    {
        // If there are any validation errors, show the form again.
        if ($this->dialog->hasValidationErrors()) {
            return [
                'dialog' => $this->dialog->getHtml(),
                'formId' => $this->dialog->getId(),
            ];
        }

        (new static([], 'create', \array_merge($this->dialog->getData(), [
            'data' => [
                'personID' => $this->parameters['personID'],
            ],
        ])))->executeAction();

        return [];
    }

    /**
     * Validates the `getEditDialog` action.
     */
    public function validateGetEditDialog(): void
    {
        WCF::getSession()->checkPermissions(['user.person.canAddInformation']);

        $this->readInteger('informationID');
        $this->information = new PersonInformation($this->parameters['informationID']);
        if (!$this->information->getObjectID()) {
            throw new UserInputException('informationID');
        }
        if (!$this->information->canEdit()) {
            throw new IllegalLinkException();
        }
    }

    /**
     * Returns the data to show the dialog to edit a piece of information on a person.
     *
     * @return  string[]
     */
    public function getEditDialog(): array
    {
        $this->buildDialog();
        $this->dialog->updatedObject($this->information);

        return [
            'dialog' => $this->dialog->getHtml(),
            'formId' => $this->dialog->getId(),
        ];
    }

    /**
     * Validates the `submitEditDialog` action.
     */
    public function validateSubmitEditDialog(): void
    {
        $this->validateGetEditDialog();

        $this->buildDialog();
        $this->dialog->updatedObject($this->information, false);
        $this->dialog->requestData($_POST['parameters']['data'] ?? []);
        $this->dialog->readValues();
        $this->dialog->validate();
    }

    /**
     * Updates a piece of information on a person after submitting the edit dialog.
     *
     * @return  string[]
     */
    public function submitEditDialog(): array
    {
        // If there are any validation errors, show the form again.
        if ($this->dialog->hasValidationErrors()) {
            return [
                'dialog' => $this->dialog->getHtml(),
                'formId' => $this->dialog->getId(),
            ];
        }

        (new static([$this->information], 'update', $this->dialog->getData()))->executeAction();

        // Reload the information with the updated data.
        $information = new PersonInformation($this->information->getObjectID());

        return [
            'formattedInformation' => $information->getFormattedInformation(),
            'informationID' => $this->information->getObjectID(),
        ];
    }

    /**
     * Builds the dialog to create or edit person information.
     */
    protected function buildDialog(): void
    {
        if ($this->dialog !== null) {
            return;
        }

        $this->dialog = DialogFormDocument::create('personInformationAddDialog')
            ->appendChild(
                WysiwygFormContainer::create('information')
                    ->messageObjectType('com.woltlab.wcf.people.information')
                    ->required()
            );

        EventHandler::getInstance()->fireAction($this, 'buildDialog');

        $this->dialog->build();
    }
}
