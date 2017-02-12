<?php
namespace wcf\acp\form;
use wcf\data\person\PersonAction;
use wcf\form\AbstractForm;
use wcf\system\exception\UserInputException;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * Shows the form to create a new person.
 * 
 * @author	Matthias Schmidt
 * @copyright	2001-2017 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	WoltLabSuite\Core\Acp\Form
 */
class PersonAddForm extends AbstractForm {
	/**
	 * @inheritDoc
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.person.add';
	
	/**
	 * is `1` if comments are enabled for the person, otherwise `0`
	 * @var	integer
	 */
	public $enableComments = 1;
	
	/**
	 * first name of the person
	 * @var	string
	 */
	public $firstName = '';
	
	/**
	 * last name of the person
	 * @var	string
	 */
	public $lastName = '';
	
	/**
	 * @inheritDoc
	 */
	public $neededPermissions = ['admin.content.canManagePeople'];
	
	/**
	 * @inheritDoc
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign([
			'action' => 'add',
			'enableComments' => $this->enableComments,
			'firstName' => $this->firstName,
			'lastName' => $this->lastName
		]);
	}
	
	/**
	 * @inheritDoc
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		$this->enableComments = isset($_POST['enableComments']) ? 1 : 0;
		if (isset($_POST['firstName'])) $this->firstName = StringUtil::trim($_POST['firstName']);
		if (isset($_POST['lastName'])) $this->lastName = StringUtil::trim($_POST['lastName']);
	}
	
	/**
	 * @inheritDoc
	 */
	public function save() {
		parent::save();
		
		$this->objectAction = new PersonAction([], 'create', [
			'data' => array_merge($this->additionalFields, [
				'enableComments' => $this->enableComments,
				'firstName' => $this->firstName,
				'lastName' => $this->lastName
			])
		]);
		$this->objectAction->executeAction();
		
		$this->saved();
		
		// reset values
		$this->enableComments = 1;
		$this->firstName = '';
		$this->lastName = '';
		
		// show success message
		WCF::getTPL()->assign('success', true);
	}
	
	/**
	 * @inheritDoc
	 */
	public function validate() {
		parent::validate();
		
		// validate first name
		if (empty($this->firstName)) {
			throw new UserInputException('firstName');
		}
		if (mb_strlen($this->firstName) > 255) {
			throw new UserInputException('firstName', 'tooLong');
		}
		
		// validate last name
		if (empty($this->lastName)) {
			throw new UserInputException('lastName');
		}
		if (mb_strlen($this->lastName) > 255) {
			throw new UserInputException('lastName', 'tooLong');
		}
	}
}
