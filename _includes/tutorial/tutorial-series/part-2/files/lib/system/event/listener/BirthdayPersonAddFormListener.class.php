<?php
namespace wcf\system\event\listener;
use wcf\acp\form\PersonAddForm;
use wcf\acp\form\PersonEditForm;
use wcf\form\IForm;
use wcf\page\IPage;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * Handles setting the birthday when adding and editing people.
 *
 * @author	Matthias Schmidt
 * @copyright	2001-2017 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	WoltLabSuite\Core\System\Event\Listener
 */
class BirthdayPersonAddFormListener implements IParameterizedEventListener {
	/**
	 * birthday of the created or edited person
	 * @var	string
	 */
	protected $birthday = '';
	
	/**
	 * @see	IPage::assignVariables()
	 */
	protected function assignVariables() {
		WCF::getTPL()->assign('birthday', $this->birthday);
	}
	
	/**
	 * @inheritDoc
	 */
	public function execute($eventObj, $className, $eventName, array &$parameters) {
		if (method_exists($this, $eventName) && $eventName !== 'execute') {
			$this->$eventName($eventObj);
		}
		else {
			throw new \LogicException('Unreachable');
		}
	}
	
	/**
	 * @see	IPage::readData()
	 */
	protected function readData(PersonEditForm $form) {
		if (empty($_POST)) {
			$this->birthday = $form->person->birthday;
			
			if ($this->birthday === '0000-00-00') {
				$this->birthday = '';
			}
		}
	}
	
	/**
	 * @see	IForm::readFormParameters()
	 */
	protected function readFormParameters() {
		if (isset($_POST['birthday'])) {
			$this->birthday = StringUtil::trim($_POST['birthday']);
		}
	}
	
	/**
	 * @see	IForm::save()
	 */
	protected function save(PersonAddForm $form) {
		if ($this->birthday) {
			$form->additionalFields['birthday'] = $this->birthday;
		}
		else {
			$form->additionalFields['birthday'] = '0000-00-00';
		}
	}
	
	/**
	 * @see	IForm::saved()
	 */
	protected function saved() {
		$this->birthday = '';
	}
	
	/**
	 * @see	IForm::validate()
	 */
	protected function validate() {
		if (empty($this->birthday)) {
			return;
		}
		
		if (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $this->birthday, $match)) {
			throw new UserInputException('birthday', 'noValidSelection');
		}
		
		if (!checkdate(intval($match[2]), intval($match[3]), intval($match[1]))) {
			throw new UserInputException('birthday', 'noValidSelection');
		}
	}
}
