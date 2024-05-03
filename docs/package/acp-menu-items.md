# ACP Menu Items

The API for the ACP menu items allows you to add your own menu items in the admin panel for your package.

Since WoltLab Suite 6.1 you can attach an event listener to the `wcf\system\menu\acp\event\AcpMenuCollecting` event inside a bootstrap script to lazily register your ACP menu items.

The `register` method of the event expects an object of the type `wcf\system\menu\acp\AcpMenuItem` as a parameter. An `AcpMenuItem` object consists of the following parameters:

| Name | Type | Description |
|------|------|-------------|
| `$menuItem` | string | Identifier of the item; must be unique |
| `$title` | string | (Optional) title of the item; if omitted `$menuItem` is used as language variable |
| `$parentMenuItem` | string | (Optional) identifier of the parent item |
| `$link` | string | (Optional) URL of the linked page |
| `$icon` | ?FontAwesomeIcon | (Optional) icon of the item |

Example:

```php
<?php

use wcf\system\event\EventHandler;
use wcf\system\menu\acp\AcpMenuItem;
use wcf\system\menu\acp\event\AcpMenuCollecting;
use wcf\system\request\LinkHandler;
use wcf\system\style\FontAwesomeIcon;

return static function (): void {
    EventHandler::getInstance()->register(AcpMenuCollecting::class, static function (AcpMenuCollecting $event) {
        $event->register(new AcpMenuItem(
            "com.woltlab.foor.bar",
            'Example Title',
            'wcf.acp.menu.link.application'
        ));

        if ($currentUserHasSomeSpecificPermission) {
            $event->register(new AcpMenuItem(
                "com.woltlab.foor.bar.add",
                'Example Add Title',
                "com.woltlab.foor.bar",
                LinkHandler::getInstance()->getControllerLink(FooAddForm::class),
                FontAwesomeIcon::fromString('plus;false')
            ));
        }
    });
};
```
