<?php
namespace wcf\acp\form;
use wcf\data\person\PersonAction;
use wcf\form\AbstractFormBuilderForm;
use wcf\system\form\builder\container\FormContainer;
use wcf\system\form\builder\field\TextFormField;

/**
 * Shows the form to create a new person.
 * 
 * @author	Matthias Schmidt
 * @copyright	2001-2019 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	WoltLabSuite\Core\Acp\Form
 */
class PersonAddForm extends AbstractFormBuilderForm {
	/**
	 * @inheritDoc
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.person.add';
	
	/**
	 * @inheritDoc
	 */
	public $formAction = 'create';
	
	/**
	 * @inheritDoc
	 */
	public $neededPermissions = ['admin.content.canManagePeople'];
	
	/**
	 * @inheritDoc
	 */
	public $objectActionClass = PersonAction::class;
	
	/**
	 * @inheritDoc
	 */
	protected function createForm() {
		parent::createForm();
		
		$dataContainer = FormContainer::create('data')
			->appendChildren([
				TextFormField::create('firstName')
					->label('wcf.person.firstName')
					->required()
					->maximumLength(255),
				
				TextFormField::create('lastName')
					->label('wcf.person.lastName')
					->required()
					->maximumLength(255)
			]);
		
		$this->form->appendChild($dataContainer);
	}
}
