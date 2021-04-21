<?php

namespace wcf\data\person\information;

use wcf\data\DatabaseObjectEditor;

/**
 * Provides functions to edit person information.
 *
 * @author  Matthias Schmidt
 * @copyright   2001-2021 WoltLab GmbH
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package WoltLabSuite\Core\Data\Person\Informtion
 *
 * @method static   PersonInformation   create(array $parameters = [])
 * @method          PersonInformation   getDecoratedObject()
 * @mixin           PersonInformation
 */
class PersonInformationEditor extends DatabaseObjectEditor
{
    /**
     * @inheritDoc
     */
    protected static $baseClass = PersonInformation::class;
}
