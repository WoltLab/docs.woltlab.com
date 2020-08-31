<?php
namespace wcf\system\event\listener;
use wcf\acp\form\PersonAddForm;
use wcf\acp\form\PersonEditForm;
use wcf\form\IForm;
use wcf\page\IPage;
use wcf\system\exception\UserInputException;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * Handles setting the birthday when adding and editing people.
 *
 * @author	Matthias Schmidt
 * @copyright	2001-2020 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	WoltLabSuite\Core\System\Event\Listener
 */
class BirthdayPersonAddFormListener extends AbstractEventListener {
	/**
	 * birthday of the created or edited person
	 * @var	string
	 */
	protected $birthday = '';
	
	/**
	 * @see	IPage::assignVariables()
	 */
	protected function onAssignVariables() {
		WCF::getTPL()->assign('birthday', $this->birthday);
	}
	
	/**
	 * @see	IPage::readData()
	 */
	protected function onReadData(PersonEditForm $form) {
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
	protected function onReadFormParameters() {
		if (isset($_POST['birthday'])) {
			$this->birthday = StringUtil::trim($_POST['birthday']);
		}
	}
	
	/**
	 * @see	IForm::save()
	 */
	protected function onSave(PersonAddForm $form) {
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
	protected function onSaved() {
		$this->birthday = '';
	}
	
	/**
	 * @see	IForm::validate()
	 */
	protected function onValidate() {
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
