<?php
namespace wcf\system\event\listener;
use wcf\acp\action\UserExportGdprAction;
use wcf\data\user\UserProfile;

class MyUserExportGdprActionListener implements IParameterizedEventListener {
    public function execute(/** @var UserExportGdprAction $eventObj */$eventObj, $className, $eventName, array &$parameters) {
        /** @var UserProfile $user */
        $user = $eventObj->user;

        $eventObj->data['my.fancy.plugin'] = [
            'superPersonalData' => "This text is super personal and should be included in the output",
            'weirdIpAddresses' => $eventObj->exportIpAddresses('app'.WCF_N.'_non_standard_column_names_for_ip_addresses', 'ipAddressColumnName', 'timeColumnName', 'userIDColumnName')
        ];
        $eventObj->exportUserProperties[] = 'shouldAlwaysExportThisField';
        $eventObj->exportUserPropertiesIfNotEmpty[] = 'myFancyField';
        $eventObj->exportUserOptionSettings[] = 'thisSettingIsAlwaysExported';
        $eventObj->exportUserOptionSettingsIfNotEmpty[] = 'someSettingContainingPersonalData';
        $eventObj->ipAddresses['my.fancy.plugin'] = ['wcf'.WCF_N.'_my_fancy_table', 'wcf'.WCF_N.'_i_also_store_ipaddresses_here'];
        $eventObj->skipUserOptions[] = 'thisLooksLikePersonalDataButItIsNot';
        $eventObj->skipUserOptions[] = 'thisIsAlsoNotPersonalDataPleaseIgnoreIt';
    }
}