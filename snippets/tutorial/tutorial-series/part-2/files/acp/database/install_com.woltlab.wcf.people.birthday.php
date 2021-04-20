<?php

use wcf\system\database\table\column\DateDatabaseTableColumn;
use wcf\system\database\table\PartialDatabaseTable;

return [
    PartialDatabaseTable::create('wcf1_person')
        ->columns([
            DateDatabaseTableColumn::create('birthday'),
        ]),
];
