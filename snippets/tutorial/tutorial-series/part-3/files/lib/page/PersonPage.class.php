<?php

namespace wcf\page;

use wcf\data\person\Person;
use wcf\system\exception\IllegalLinkException;
use wcf\system\view\CommentsView;
use wcf\system\WCF;

/**
 * Shows the details of a certain person.
 *
 * @author      Matthias Schmidt
 * @copyright   2001-2021 WoltLab GmbH
 * @license     GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
class PersonPage extends AbstractPage
{
    public Person $person;
    public int $personID = 0;
    public ?CommentsView $commentsView = null;

    /**
     * @inheritDoc
     */
    public function assignVariables()
    {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'commentsView' => $this->commentsView,
            'person' => $this->person,
        ]);
    }

    /**
     * @inheritDoc
     */
    public function readData()
    {
        parent::readData();

        if ($this->person->enableComments) {
            $this->commentsView = new CommentsView(
                'com.woltlab.wcf.person.personComment',
                $this->person->personID,
                'personCommentList',
                WCF::getSession()->getPermission('user.person.canAddComment')
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function readParameters()
    {
        parent::readParameters();

        if (isset($_REQUEST['id'])) {
            $this->personID = \intval($_REQUEST['id']);
        }
        $this->person = new Person($this->personID);
        if (!$this->person->personID) {
            throw new IllegalLinkException();
        }
    }
}
