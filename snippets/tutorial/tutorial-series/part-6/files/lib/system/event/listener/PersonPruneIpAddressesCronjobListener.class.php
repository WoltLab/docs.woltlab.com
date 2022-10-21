<?php

namespace wcf\system\event\listener;

use wcf\system\cronjob\PruneIpAddressesCronjob;

/**
 * Prunes old ip addresses.
 *
 * @author  Matthias Schmidt
 * @copyright   2001-2022 WoltLab GmbH
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package WoltLabSuite\Core\System\Event\Listener
 */
final class PersonPruneIpAddressesCronjobListener extends AbstractEventListener
{
    protected function onExecute(PruneIpAddressesCronjob $cronjob): void
    {
        $cronjob->columns['wcf' . WCF_N . '_person_information']['ipAddress'] = 'time';
    }
}
