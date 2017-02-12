<?php
namespace wcf\system\page\handler;
use wcf\data\page\Page;
use wcf\data\person\PersonList;
use wcf\data\user\online\UserOnline;
use wcf\system\cache\runtime\PersonRuntimeCache;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\WCF;

/**
 * Page handler implementation for person page.
 * 
 * @author	Matthias Schmidt
 * @copyright	2001-2017 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	WoltLabSuite\Core\System\Page\Handler
 */
class PersonPageHandler extends AbstractLookupPageHandler implements IOnlineLocationPageHandler {
	use TOnlineLocationPageHandler;
	
	/**
	 * @inheritDoc
	 */
	public function getLink($objectID) {
		return PersonRuntimeCache::getInstance()->getObject($objectID)->getLink();
	}
	
	/**
	 * Returns the textual description if a user is currently online viewing this page.
	 *
	 * @see	IOnlineLocationPageHandler::getOnlineLocation()
	 *
	 * @param	Page		$page		visited page
	 * @param	UserOnline	$user		user online object with request data
	 * @return	string
	 */
	public function getOnlineLocation(Page $page, UserOnline $user) {
		if ($user->pageObjectID === null) {
			return '';
		}
		
		$person = PersonRuntimeCache::getInstance()->getObject($user->pageObjectID);
		if ($person === null) {
			return '';
		}
		
		return WCF::getLanguage()->getDynamicVariable('wcf.page.onlineLocation.'.$page->identifier, ['person' => $person]);
	}
	
	/**
	 * @inheritDoc
	 */
	public function isValid($objectID = null) {
		return PersonRuntimeCache::getInstance()->getObject($objectID) !== null;
	}
	
	/**
	 * @inheritDoc
	 */
	public function lookup($searchString) {
		$conditionBuilder = new PreparedStatementConditionBuilder(false, 'OR');
		$conditionBuilder->add('person.firstName LIKE ?', ['%' . $searchString . '%']);
		$conditionBuilder->add('person.lastName LIKE ?', ['%' . $searchString . '%']);
		
		$personList = new PersonList();
		$personList->getConditionBuilder()->add($conditionBuilder, $conditionBuilder->getParameters());
		$personList->readObjects();
		
		$results = [];
		foreach ($personList as $person) {
			$results[] = [
				'image' => 'fa-user',
				'link' => $person->getLink(),
				'objectID' => $person->personID,
				'title' => $person->getTitle()
			];
		}
		
		return $results;
	}
	
	/**
	 * Prepares fetching all necessary data for the textual description if a user is currently online
	 * viewing this page.
	 * 
	 * @see	IOnlineLocationPageHandler::prepareOnlineLocation()
	 *
	 * @param	Page		$page		visited page
	 * @param	UserOnline	$user		user online object with request data
	 */
	public function prepareOnlineLocation(/** @noinspection PhpUnusedParameterInspection */Page $page, UserOnline $user) {
		if ($user->pageObjectID !== null) {
			PersonRuntimeCache::getInstance()->cacheObjectID($user->pageObjectID);
		}
	}
}
