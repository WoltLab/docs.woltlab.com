<?php

namespace wcf\system\event\listener;

use wcf\acp\action\UserExportGdprAction;

/**
 * Adds the ip addresses stored with the person information during user data export.
 *
 * @author  Matthias Schmidt
 * @copyright   2001-2021 WoltLab GmbH
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package WoltLabSuite\Core\System\Event\Listener
 */
class PersonUserExportGdprListener extends AbstractEventListener
{
    protected function onExport(UserExportGdprAction $action): void
    {
        $action->ipAddresses['com.woltlab.wcf.people'] = ['wcf' . WCF_N . '_person_information'];
    }
}
