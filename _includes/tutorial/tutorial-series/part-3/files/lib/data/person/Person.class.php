<?php
namespace wcf\data\person;
use wcf\data\DatabaseObject;
use wcf\data\ILinkableObject;
use wcf\system\request\IRouteController;
use wcf\system\request\LinkHandler;

/**
 * Represents a person.
 * 
 * @author	Matthias Schmidt
 * @copyright	2001-2017 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	WoltLabSuite\Core\Data\Person
 * 
 * @property-read	integer		$personID		unique id of the person
 * @property-read	string		$firstName		first name of the person
 * @property-read	string		$lastName		last name of the person
 * @property-read	integer		$comments		number of comments on the person
 * @property-read	integer		$enableComments		is `1` if comments are enabled for the person, otherwise `0`
 */
class Person extends DatabaseObject implements ILinkableObject, IRouteController {
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
	public function getLink() {
		return LinkHandler::getInstance()->getLink('Person', [
			'forceFrontend' => true,
			'object' => $this
		]);
	}
	
	/**
	 * @inheritDoc
	 */
	public function getTitle() {
		return $this->firstName . ' ' . $this->lastName;
	}
}
