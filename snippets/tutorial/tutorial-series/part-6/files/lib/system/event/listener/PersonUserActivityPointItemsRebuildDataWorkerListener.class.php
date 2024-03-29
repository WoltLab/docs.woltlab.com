<?php

namespace wcf\system\event\listener;

use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\user\activity\point\UserActivityPointHandler;
use wcf\system\WCF;
use wcf\system\worker\UserActivityPointItemsRebuildDataWorker;

/**
 * Updates the user activity point items counter for person information.
 *
 * @author  Matthias Schmidt
 * @copyright   2001-2022 WoltLab GmbH
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package WoltLabSuite\Core\System\Event\Listener
 */
final class PersonUserActivityPointItemsRebuildDataWorkerListener extends AbstractEventListener
{
    protected function onExecute(UserActivityPointItemsRebuildDataWorker $worker): void
    {
        $objectType = UserActivityPointHandler::getInstance()
            ->getObjectTypeByName('com.woltlab.wcf.people.information');

        $conditionBuilder = new PreparedStatementConditionBuilder();
        $conditionBuilder->add('user_activity_point.objectTypeID = ?', [$objectType->objectTypeID]);
        $conditionBuilder->add('user_activity_point.userID IN (?)', [$worker->getObjectList()->getObjectIDs()]);

        $sql = "UPDATE  wcf1_user_activity_point user_activity_point
                SET     user_activity_point.items = (
                            SELECT  COUNT(*)
                            FROM    wcf1_person_information person_information
                            WHERE   person_information.userID = user_activity_point.userID
                        ),
                        user_activity_point.activityPoints = user_activity_point.items * ?
                {$conditionBuilder}";
        $statement = WCF::getDB()->prepare($sql);
        $statement->execute([
            $objectType->points,
            ...$conditionBuilder->getParameters()
        ]);
    }
}
