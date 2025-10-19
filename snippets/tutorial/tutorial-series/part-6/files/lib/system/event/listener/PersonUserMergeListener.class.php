<?php

namespace wcf\system\event\listener;

/**
 * Updates person information during user merging.
 *
 * @author  Matthias Schmidt
 * @copyright   2001-2022 WoltLab GmbH
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
final class PersonUserMergeListener extends AbstractUserMergeListener
{
    /**
     * @inheritDoc
     */
    protected $databaseTables = [
        'wcf{WCF_N}_person_information',
    ];
}
