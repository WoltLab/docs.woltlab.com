<?php
namespace wcf\data\person;
use wcf\data\DatabaseObjectList;

/**
 * Represents a list of people.
 * 
 * @author	Matthias Schmidt
 * @copyright	2001-2017 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	WoltLabSuite\Core\Data\Person
 * 
 * @method	Person		current()
 * @method	Person[]	getObjects()
 * @method	Person|null	search($objectID)
 * @property	Person[]	$objects
 */
class PersonList extends DatabaseObjectList {}
