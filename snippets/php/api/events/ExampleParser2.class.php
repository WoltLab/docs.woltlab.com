<?php
namespace wcf\system\example;
use wcf\system\event\EventHandler;

class ExampleParser {
    public function parse($text) {
        $parameters = ['text' => $text];
        EventHandler::getInstance()->fireAction($this, 'beforeParsing', $parameters);
        $text = $parameters['text'];

        // [some parsing done by default]

        $parameters = ['text' => $text];
        EventHandler::getInstance()->fireAction($this, 'afterParsing', $parameters);

        return $parameters['text'];
    }
}
