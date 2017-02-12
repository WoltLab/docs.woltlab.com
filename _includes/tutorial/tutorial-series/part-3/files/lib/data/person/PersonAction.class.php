<?php
namespace wcf\data\person;
use wcf\data\AbstractDatabaseObjectAction;

/**
 * Executes person-related actions.
 * 
 * @author	Matthias Schmidt
 * @copyright	2001-2017 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	WoltLabSuite\Core\Data\Person
 *
 * @method	Person		create()
 * @method	PersonEditor[]	getObjects()
 * @method	PersonEditor	getSingleObject()
 */
class PersonAction extends AbstractDatabaseObjectAction {
	/**
	 * @inheritDoc
	 */
	protected $permissionsDelete = ['admin.content.canManagePeople'];
	
	/**
	 * @inheritDoc
	 */
	protected $requireACP = ['delete'];
}
