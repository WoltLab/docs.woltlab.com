<?php
namespace wcf\acp\page;
use wcf\data\person\PersonList;
use wcf\page\SortablePage;

/**
 * Shows the list of people.
 * 
 * @author	Matthias Schmidt
 * @copyright	2001-2017 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	WoltLabSuite\Core\Acp\Page
 */
class PersonListPage extends SortablePage {
	/**
	 * @inheritDoc
	 */
	public $activeMenuItem = 'wcf.acp.menu.link.person.list';
	
	/**
	 * @inheritDoc
	 */
	public $neededPermissions = ['admin.content.canManagePeople'];
	
	/**
	 * @inheritDoc
	 */
	public $objectListClassName = PersonList::class;
	
	/**
	 * @inheritDoc
	 */
	public $validSortFields = ['personID', 'firstName', 'lastName'];
}
