<?php
namespace wcf\page;
use wcf\data\person\Person;
use wcf\system\comment\CommentHandler;
use wcf\system\comment\manager\PersonCommentManager;
use wcf\system\exception\IllegalLinkException;
use wcf\system\WCF;

/**
 * Shows the details of a certain person.
 * 
 * @author	Matthias Schmidt
 * @copyright	2001-2017 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	WoltLabSuite\Core\Page
 */
class PersonPage extends AbstractPage {
	/**
	 * list of comments
	 * @var	StructuredCommentList
	 */
	public $commentList;
	
	/**
	 * person comment manager object
	 * @var	PersonCommentManager
	 */
	public $commentManager;
	
	/**
	 * id of the person comment object type
	 * @var	integer
	 */
	public $commentObjectTypeID = 0;
	
	/**
	 * shown person
	 * @var	Person
	 */
	public $person;
	
	/**
	 * id of the shown person
	 * @var	integer
	 */
	public $personID = 0;
	
	/**
	 * @inheritDoc
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign([
			'commentCanAdd' => WCF::getSession()->getPermission('user.person.canAddComment'),
			'commentList' => $this->commentList,
			'commentObjectTypeID' => $this->commentObjectTypeID,
			'lastCommentTime' => $this->commentList ? $this->commentList->getMinCommentTime() : 0,
			'likeData' => (MODULE_LIKE && $this->commentList) ? $this->commentList->getLikeData() : [],
			'person' => $this->person
		]);
	}
	
	/**
	 * @inheritDoc
	 */
	public function readData() {
		parent::readData();
		
		if ($this->person->enableComments) {
			$this->commentObjectTypeID = CommentHandler::getInstance()->getObjectTypeID('com.woltlab.wcf.person.personComment');
			$this->commentManager = CommentHandler::getInstance()->getObjectType($this->commentObjectTypeID)->getProcessor();
			$this->commentList = CommentHandler::getInstance()->getCommentList($this->commentManager, $this->commentObjectTypeID, $this->person->personID);
		}
	}
	
	/**
	 * @inheritDoc
	 */
	public function readParameters() {
		parent::readParameters();
		
		if (isset($_REQUEST['id'])) $this->personID = intval($_REQUEST['id']);
		$this->person = new Person($this->personID);
		if (!$this->person->personID) {
			throw new IllegalLinkException();
		}
	}
}
