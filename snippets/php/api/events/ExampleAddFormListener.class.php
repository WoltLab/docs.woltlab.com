<?php
namespace wcf\system\event\listener;
use wcf\form\ExampleAddForm;
use wcf\form\ExampleEditForm;
use wcf\system\exception\UserInputException;
use wcf\system\WCF;

class ExampleAddFormListener implements IParameterizedEventListener {
    protected $var = 0;

    public function execute($eventObj, $className, $eventName, array &$parameters) {
        $this->$eventName($eventObj);
    }

    protected function assignVariables() {
        WCF::getTPL()->assign('var', $this->var);
    }

    protected function readData(ExampleEditForm $eventObj) {
        if (empty($_POST)) {
            $this->var = $eventObj->example->var;
        }
    }

    protected function readFormParameters() {
        if (isset($_POST['var'])) $this->var = intval($_POST['var']);
    }

    protected function save(ExampleAddForm $eventObj) {
        $eventObj->additionalFields = array_merge($eventObj->additionalFields, ['var' => $this->var]);
    }

    protected function saved() {
        $this->var = 0;
    }

    protected function validate() {
        if ($this->var < 0) {
            throw new UserInputException('var', 'isNegative');
        }
    }
}
