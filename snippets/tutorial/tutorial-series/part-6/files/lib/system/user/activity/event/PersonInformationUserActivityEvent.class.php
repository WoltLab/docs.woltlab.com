<?php

namespace wcf\system\user\activity\event;

use wcf\data\person\information\PersonInformationList;
use wcf\system\SingletonFactory;
use wcf\system\WCF;

/**
 * User activity event implementation for person information.
 *
 * @author  Matthias Schmidt
 * @copyright   2001-2022 WoltLab GmbH
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package WoltLabSuite\Core\System\User\Activity\Event
 */
final class PersonInformationUserActivityEvent extends SingletonFactory implements IUserActivityEvent
{
    /**
     * @inheritDoc
     */
    public function prepare(array $events)
    {
        $objectIDs = \array_column($events, 'objectID');

        $informationList = new PersonInformationList();
        $informationList->setObjectIDs($objectIDs);
        $informationList->readObjects();
        $information = $informationList->getObjects();

        foreach ($events as $event) {
            if (isset($information[$event->objectID])) {
                $personInformation = $information[$event->objectID];

                $event->setIsAccessible();
                $event->setTitle(
                    WCF::getLanguage()->getDynamicVariable(
                        'wcf.user.profile.recentActivity.personInformation',
                        [
                            'person' => $personInformation->getPerson(),
                            'personInformation' => $personInformation,
                        ]
                    )
                );
                $event->setDescription($personInformation->getFormattedExcerpt());
            }
        }
    }
}
