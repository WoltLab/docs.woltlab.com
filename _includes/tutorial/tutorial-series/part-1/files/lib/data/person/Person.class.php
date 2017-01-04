<?php
namespace wcf\data\person;
use wcf\data\DatabaseObject;
use wcf\system\request\IRouteController;

/**
 * Represents a person.
 * 
 * @author	Matthias Schmidt
 * @copyright	2001-2017 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	WoltLabSuite\Core\Data\Person
 * 
 * @property-read	integer		$personID	unique id of the person
 * @property-read	string		$firstName	first name of the person
 * @property-read	string		$lastName	last name of the person
 */
class Person extends DatabaseObject implements IRouteController {
	/**
	 * Returns the first and last name of the person if a person object is treated as a string.
	 * 
	 * @return	string
	 */
	public function __toString() {
		return $this->getTitle();
	}
	
	/**
	 * @inheritDoc
	 */
	public function getTitle() {
		return $this->firstName . ' ' . $this->lastName;
	}
}
