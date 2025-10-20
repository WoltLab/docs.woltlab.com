<?php

namespace wcf\system\interaction\admin;

use wcf\data\person\Person;
use wcf\system\interaction\AbstractInteractionProvider;
use wcf\system\interaction\DeleteInteraction;

/**
 * Interaction provider for persons.
 *
 * @author      Marcel Werk
 * @copyright   2001-2025 WoltLab GmbH
 * @license     GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
final class PersonInteractions extends AbstractInteractionProvider
{
    public function __construct()
    {
        $this->addInteractions([
            new DeleteInteraction('core/persons/%s'),
        ]);
    }

    #[\Override]
    public function getObjectClassName(): string
    {
        return Person::class;
    }
}
