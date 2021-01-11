<?php
namespace wcf\acp\form;
use wcf\data\person\Person;
use wcf\system\exception\IllegalLinkException;

/**
 * Shows the form to edit an existing person.
 * 
 * @author	Matthias Schmidt
 * @copyright	2001-2019 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	WoltLabSuite\Core\Acp\Form
 */
class PersonEditForm extends PersonAddForm {
	/**
	 * @inheritDoc
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.person';
	
	/**
	 * @inheritDoc
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['id'])) {
			$this->formObject = new Person(intval($_REQUEST['id']));
			if (!$this->formObject->personID) {
				throw new IllegalLinkException();
			}
		}
	}
}
