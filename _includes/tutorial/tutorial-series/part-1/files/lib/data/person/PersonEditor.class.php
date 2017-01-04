<?php
namespace wcf\data\person;
use wcf\data\DatabaseObjectEditor;

/**
 * Provides functions to edit people.
 * 
 * @author	Matthias Schmidt
 * @copyright	2001-2017 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	WoltLabSuite\Core\Data\Person
 * 
 * @method static	Person	create(array $parameters = [])
 * @method		Person	getDecoratedObject()
 * @mixin		Person
 */
class PersonEditor extends DatabaseObjectEditor {
	/**
	 * @inheritDoc
	 */
	protected static $baseClass = Person::class;
}
