<?php

use wcf\system\database\table\column\DefaultTrueBooleanDatabaseTableColumn;
use wcf\system\database\table\column\IntDatabaseTableColumn;
use wcf\system\database\table\column\NotNullInt10DatabaseTableColumn;
use wcf\system\database\table\column\NotNullVarchar255DatabaseTableColumn;
use wcf\system\database\table\column\ObjectIdDatabaseTableColumn;
use wcf\system\database\table\column\SmallintDatabaseTableColumn;
use wcf\system\database\table\column\TextDatabaseTableColumn;
use wcf\system\database\table\column\VarcharDatabaseTableColumn;
use wcf\system\database\table\DatabaseTable;
use wcf\system\database\table\index\DatabaseTableForeignKey;
use wcf\system\database\table\index\DatabaseTablePrimaryIndex;

return [
    DatabaseTable::create('wcf1_person')
        ->columns([
            ObjectIdDatabaseTableColumn::create('personID'),
            NotNullVarchar255DatabaseTableColumn::create('firstName'),
            NotNullVarchar255DatabaseTableColumn::create('lastName'),
            NotNullInt10DatabaseTableColumn::create('informationCount')
                ->defaultValue(0),
            SmallintDatabaseTableColumn::create('comments')
                ->length(5)
                ->notNull()
                ->defaultValue(0),
            DefaultTrueBooleanDatabaseTableColumn::create('enableComments'),
        ])
        ->indices([
            DatabaseTablePrimaryIndex::create()
                ->columns(['personID']),
        ]),

    DatabaseTable::create('wcf1_person_information')
        ->columns([
            ObjectIdDatabaseTableColumn::create('informationID'),
            NotNullInt10DatabaseTableColumn::create('personID'),
            TextDatabaseTableColumn::create('information'),
            IntDatabaseTableColumn::create('userID')
                ->length(10),
            NotNullVarchar255DatabaseTableColumn::create('username'),
            VarcharDatabaseTableColumn::create('ipAddress')
                ->length(39)
                ->notNull(true)
                ->defaultValue(''),
            NotNullInt10DatabaseTableColumn::create('time'),
        ])
        ->indices([
            DatabaseTablePrimaryIndex::create()
                ->columns(['informationID']),
        ])
        ->foreignKeys([
            DatabaseTableForeignKey::create()
                ->columns(['personID'])
                ->referencedTable('wcf1_person')
                ->referencedColumns(['personID'])
                ->onDelete('CASCADE'),
            DatabaseTableForeignKey::create()
                ->columns(['userID'])
                ->referencedTable('wcf1_user')
                ->referencedColumns(['userID'])
                ->onDelete('SET NULL'),
        ]),
];
