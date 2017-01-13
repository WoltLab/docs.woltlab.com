<?php
namespace wcf\system\event\listener;
use wcf\page\SortablePage;

/**
 * Makes people's birthday a valid sort field in the ACP and the front end.
 * 
 * @author	Matthias Schmidt
 * @copyright	2001-2017 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	WoltLabSuite\Core\System\Event\Listener
 */
class BirthdaySortFieldPersonListPageListener implements IParameterizedEventListener {
	/**
	 * @inheritDoc
	 */
	public function execute($eventObj, $className, $eventName, array &$parameters) {
		/** @var SortablePage $eventObj */
		
		$eventObj->validSortFields[] = 'birthday';
	}
}
