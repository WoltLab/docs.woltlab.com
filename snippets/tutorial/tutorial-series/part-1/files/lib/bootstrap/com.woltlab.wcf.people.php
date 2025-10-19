<?php

use wcf\acp\form\PersonAddForm;
use wcf\acp\page\PersonListPage;
use wcf\event\acp\menu\item\ItemCollecting;
use wcf\system\event\EventHandler;
use wcf\system\menu\acp\AcpMenuItem;
use wcf\system\request\LinkHandler;
use wcf\system\style\FontAwesomeIcon;
use wcf\system\WCF;

return static function (): void {
    EventHandler::getInstance()->register(
        ItemCollecting::class,
        static function (ItemCollecting $event) {
            if (!WCF::getSession()->getPermission('admin.content.canManagePeople')) {
                return;
            }

            $event->register(new AcpMenuItem(
                'wcf.acp.menu.link.person',
                parentMenuItem: 'wcf.acp.menu.link.content'
            ));
            $event->register(new AcpMenuItem(
                'wcf.acp.menu.link.person.list',
                parentMenuItem: 'wcf.acp.menu.link.person',
                link: LinkHandler::getInstance()->getControllerLink(PersonListPage::class),
            ));
            $event->register(new AcpMenuItem(
                'wcf.acp.menu.link.person.add',
                parentMenuItem: 'wcf.acp.menu.link.person.list',
                link: LinkHandler::getInstance()->getControllerLink(PersonAddForm::class),
                icon: FontAwesomeIcon::fromValues('plus')
            ));
        }
    );
};
