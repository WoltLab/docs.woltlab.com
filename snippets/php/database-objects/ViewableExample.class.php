<?php
namespace wcf\data\example;
use wcf\data\DatabaseObjectDecorator;

class ViewableExample extends DatabaseObjectDecorator {
    protected static $baseClass = Example::class;

    public function getOutput() {
        $output = '';

        // [determine output]

        return $output;
    }
}
