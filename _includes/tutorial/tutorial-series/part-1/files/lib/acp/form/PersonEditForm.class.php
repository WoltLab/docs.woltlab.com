<?php
namespace wcf\acp\form;
use wcf\data\person\Person;
use wcf\data\person\PersonAction;
use wcf\form\AbstractForm;
use wcf\system\exception\IllegalLinkException;
use wcf\system\WCF;

/**
 * Shows the form to edit an existing person.
 * 
 * @author	Matthias Schmidt
 * @copyright	2001-2017 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	WoltLabSuite\Core\Acp\Form
 */
class PersonEditForm extends PersonAddForm {
	/**
	 * @inheritDoc
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.person';
	
	/**
	 * edited person object
	 * @var	Person
	 */
	public $person = null;
	
	/**
	 * id of the edited person
	 * @var	integer
	 */
	public $personID = 0;
	
	/**
	 * @inheritDoc
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign([
			'action' => 'edit',
			'person' => $this->person
		]);
	}
	
	/**
	 * @inheritDoc
	 */
	public function readData() {
		parent::readData();
		
		if (empty($_POST)) {
			$this->firstName = $this->person->firstName;
			$this->lastName = $this->person->lastName;
		}
	}
	
	/**
	 * @inheritDoc
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['id'])) $this->personID = intval($_REQUEST['id']);
		$this->person = new Person($this->personID);
		if (!$this->person->personID) {
			throw new IllegalLinkException();
		}
	}
	
	/**
	 * @inheritDoc
	 */
	public function save() {
		AbstractForm::save();
		
		$this->objectAction = new PersonAction([$this->person], 'update', [
			'data' => array_merge($this->additionalFields, [
				'firstName' => $this->firstName,
				'lastName' => $this->lastName
			])
		]);
		$this->objectAction->executeAction();
		
		$this->saved();
		
		// show success message
		WCF::getTPL()->assign('success', true);
	}
}
