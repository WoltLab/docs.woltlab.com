<?php
namespace wcf\system\comment\manager;
use wcf\data\person\Person;
use wcf\data\person\PersonEditor;
use wcf\system\cache\runtime\PersonRuntimeCache;
use wcf\system\WCF;

/**
 * Comment manager implementation for people.
 *
 * @author	Matthias Schmidt
 * @copyright	2001-2017 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	WoltLabSuite\Core\System\Comment\Manager
 */
class PersonCommentManager extends AbstractCommentManager {
	/**
	 * @inheritDoc
	 */
	protected $permissionAdd = 'user.person.canAddComment';
	
	/**
	 * @inheritDoc
	 */
	protected $permissionAddWithoutModeration = 'user.person.canAddCommentWithoutModeration';
	
	/**
	 * @inheritDoc
	 */
	protected $permissionCanModerate = 'mod.person.canModerateComment';
	
	/**
	 * @inheritDoc
	 */
	protected $permissionDelete = 'user.person.canDeleteComment';
	
	/**
	 * @inheritDoc
	 */
	protected $permissionEdit = 'user.person.canEditComment';
	
	/**
	 * @inheritDoc
	 */
	protected $permissionModDelete = 'mod.person.canDeleteComment';
	
	/**
	 * @inheritDoc
	 */
	protected $permissionModEdit = 'mod.person.canEditComment';
	
	/**
	 * @inheritDoc
	 */
	public function getLink($objectTypeID, $objectID) {
		return PersonRuntimeCache::getInstance()->getObject($objectID)->getLink();
	}
	
	/**
	 * @inheritDoc
	 */
	public function isAccessible($objectID, $validateWritePermission = false) {
		return PersonRuntimeCache::getInstance()->getObject($objectID) !== null;
	}
	
	/**
	 * @inheritDoc
	 */
	public function getTitle($objectTypeID, $objectID, $isResponse = false) {
		if ($isResponse) {
			return WCF::getLanguage()->get('wcf.person.commentResponse');
		}
		
		return WCF::getLanguage()->getDynamicVariable('wcf.person.comment');
	}
	
	/**
	 * @inheritDoc
	 */
	public function updateCounter($objectID, $value) {
		(new PersonEditor(new Person($objectID)))->updateCounters(['comments' => $value]);
	}
}
