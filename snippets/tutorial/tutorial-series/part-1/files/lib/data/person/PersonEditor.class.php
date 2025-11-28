<?php

namespace wcf\data\person;

use wcf\data\DatabaseObjectEditor;

/**
 * Provides functions to edit people.
 *
 * @author      Matthias Schmidt
 * @copyright   2001-2021 WoltLab GmbH
 * @license     GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 *
 * @mixin   Person
 * @extends DatabaseObjectEditor<Person>
 */
class PersonEditor extends DatabaseObjectEditor
{
    /**
     * @inheritDoc
     */
    protected static $baseClass = Person::class;
}
