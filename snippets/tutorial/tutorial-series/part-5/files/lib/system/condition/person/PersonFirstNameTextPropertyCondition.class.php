<?php

namespace wcf\system\condition\person;

use wcf\data\person\Person;
use wcf\system\condition\AbstractObjectTextPropertyCondition;

/**
 * Condition implementation for the first name of a person.
 *
 * @author  Matthias Schmidt
 * @copyright   2001-2021 WoltLab GmbH
 * @license WoltLab License <http://www.woltlab.com/license-agreement.html>
 * @package WoltLabSuite\Core\System\Condition
 */
class PersonFirstNameTextPropertyCondition extends AbstractObjectTextPropertyCondition
{
    /**
     * @inheritDoc
     */
    protected $className = Person::class;

    /**
     * @inheritDoc
     */
    protected $description = 'wcf.person.condition.firstName.description';

    /**
     * @inheritDoc
     */
    protected $fieldName = 'personFirstName';

    /**
     * @inheritDoc
     */
    protected $label = 'wcf.person.firstName';

    /**
     * @inheritDoc
     */
    protected $propertyName = 'firstName';

    /**
     * @inheritDoc
     */
    protected $supportsMultipleValues = true;
}
