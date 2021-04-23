<?php
namespace wcf\system\event\listener;

class ExampleEventListener implements IParameterizedEventListener {
    public function execute($eventObj, $className, $eventName, array &$parameters) {
        $eventObj->var = 2;
    }
}
