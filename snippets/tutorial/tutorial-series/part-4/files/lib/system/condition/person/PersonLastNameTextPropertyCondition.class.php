<?php

namespace wcf\system\condition\person;

use wcf\data\person\Person;
use wcf\system\condition\AbstractObjectTextPropertyCondition;

/**
 * Condition implementation for the last name of a person.
 *
 * @author  Matthias Schmidt
 * @copyright   2001-2021 WoltLab GmbH
 * @license WoltLab License <http://www.woltlab.com/license-agreement.html>
 * @package WoltLabSuite\Core\System\Condition
 */
class PersonLastNameTextPropertyCondition extends AbstractObjectTextPropertyCondition
{
    /**
     * @inheritDoc
     */
    protected $className = Person::class;

    /**
     * @inheritDoc
     */
    protected $description = 'wcf.person.condition.lastName.description';

    /**
     * @inheritDoc
     */
    protected $fieldName = 'personLastName';

    /**
     * @inheritDoc
     */
    protected $label = 'wcf.person.lastName';

    /**
     * @inheritDoc
     */
    protected $propertyName = 'lastName';

    /**
     * @inheritDoc
     */
    protected $supportsMultipleValues = true;
}
