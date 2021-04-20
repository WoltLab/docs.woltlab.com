<?php

use wcf\system\database\table\column\NotNullVarchar255DatabaseTableColumn;
use wcf\system\database\table\column\ObjectIdDatabaseTableColumn;
use wcf\system\database\table\DatabaseTable;

return [
    DatabaseTable::create('wcf1_person')
        ->columns([
            ObjectIdDatabaseTableColumn::create('personID'),
            NotNullVarchar255DatabaseTableColumn::create('firstName'),
            NotNullVarchar255DatabaseTableColumn::create('lastName'),
        ]),
];
