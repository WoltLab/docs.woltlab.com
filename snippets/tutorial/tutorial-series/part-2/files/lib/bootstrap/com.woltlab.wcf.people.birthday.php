<?php

use wcf\event\gridView\admin\PersonGridViewInitialized;
use wcf\system\event\EventHandler;
use wcf\system\gridView\GridViewColumn;

return static function (): void {
    EventHandler::getInstance()->register(
        PersonGridViewInitialized::class,
        static function (PersonGridViewInitialized $event) {
            $event->gridView->addColumn(
                GridViewColumn::for('birthday')
                    ->label('wcf.person.birthday')
                    ->sortable()
            );
        }
    );
};
