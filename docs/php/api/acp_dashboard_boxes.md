# ACP Dashboard Boxes

ACP Dashboard Boxes are displayed on the landing page of the admin panel and can provide the user with useful information. A box consists of an internal identifier, a name and the content of the box. The content can contain HTML code.

## Create a Custom Box

A custom box can be created with a custom PHP class that needs to implement the [`wcf\system\acp\dashboard\box\IAcpDashboardBox`](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/acp/dashboard/box/IAcpDashboardBox.class.php) interface.
It is recommended to extend the class [`wcf\system\acp\dashboard\box\AbstractAcpDashboardBox`](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/acp/dashboard/box/AbstractAcpDashboardBox.class.php), which already provides a basic implementation of the interface.

Example:

```php
<?php
namespace wcf\system\acp\dashboard\box;

final class FooBox extends AbstractAcpDashboardBox {
    public function getTitle(): string
    {
        return 'title of the box';
    }

    public function getContent(): string
    {
        return 'content of the box';
    }

    public function getName(): string
    {
        return 'com.foo.bar'; // identifier of the box; must be unique
    }
}
```

## Register a Custom Box

You can attach an event listener to the `wcf\event\acp\dashboard\box\BoxCollecting` event inside a [bootstrap script](../../package/bootstrap-scripts.md) to lazily register custom boxes.
The class name of the box is registered using the event’s `register()` method:

```php title="files/lib/bootstrap/com.example.bar.php"
<?php

use wcf\system\event\EventHandler;
use wcf\event\acp\dashboard\box\BoxCollecting;

return static function (): void {
    $eventHandler = EventHandler::getInstance();

    $eventHandler->register(BoxCollecting::class, static function (BoxCollecting $event) {
        $event->register(new \wcf\system\acp\dashboard\box\FooBox());
    });
};
```
