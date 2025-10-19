<?php

namespace wcf\event\gridView\admin;

use wcf\event\IPsr14Event;
use wcf\system\gridView\admin\PersonGridView;

/**
 * Indicates that the person grid view has been initialized.
 *
 * @author      Marcel Werk
 * @copyright   2001-2025 WoltLab GmbH
 * @license     GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
final class PersonGridViewInitialized implements IPsr14Event
{
    public function __construct(public readonly PersonGridView $gridView) {}
}
