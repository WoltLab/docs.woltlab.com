<?php
namespace wcf\system\example;
use wcf\system\event\EventHandler;

class ExampleComponent {
    public $var = 1;

    public function getVar() {
        EventHandler::getInstance()->fireAction($this, 'getVar');

        return $this->var;
    }
}