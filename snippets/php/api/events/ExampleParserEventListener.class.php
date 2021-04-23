<?php
namespace wcf\system\event\listener;

class ExampleParserEventListener implements IParameterizedEventListener {
    public function execute($eventObj, $className, $eventName, array &$parameters) {
        $text = $parameters['text'];

        // [some additional parsing which changes $text]

        $parameters['text'] = $text;
    }
}