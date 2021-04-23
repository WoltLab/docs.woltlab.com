<?php
namespace wcf\system\example;
use wcf\system\event\EventHandler;

class ExampleParser {
    public function parse($text) {
        // [some parsing done by default]

        $parameters = ['text' => $text];
        EventHandler::getInstance()->fireAction($this, 'parse', $parameters);

        return $parameters['text'];
    }
}