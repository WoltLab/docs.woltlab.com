<?php
namespace wcf\page;
use wcf\data\person\PersonList;

/**
 * Shows the list of people.
 * 
 * @author	Matthias Schmidt
 * @copyright	2001-2017 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	WoltLabSuite\Core\Page
 */
class PersonListPage extends SortablePage {
	/**
	 * @inheritDoc
	 */
	public $defaultSortField = 'lastName';
	
	/**
	 * @inheritDoc
	 */
	public $objectListClassName = PersonList::class;
	
	/**
	 * @inheritDoc
	 */
	public $validSortFields = ['personID', 'firstName', 'lastName'];
}
