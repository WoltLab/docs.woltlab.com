# Bootstrap Scripts

Package-specific bootstrap scripts allow a package to execute logic during the application boot to prepare the environment before the request is passed through the middleware pipeline into the controller in `RequestHandler`.

Bootstrap scripts are stored in the `lib/bootstrap/` directory of WoltLab Suite Core with the package identifier as the file name.
They do not need to be registered explicitly, as one future goal of the bootstrap scripts is reducing the amount of system state that needs to be stored within the database.
Instead WoltLab Suite Core will automatically create a bootstrap loader that includes all installed bootstrap scripts as part of the package installation and uninstallation process.

Bootstrap scripts will be loaded and the bootstrap functions will executed based on a topological sorting of all installed packages.
A package can rely on all bootstrap scripts of its dependencies being loaded before its own bootstrap script is loaded.
It can also rely on all bootstrap functions of its dependencies having executed before its own bootstrap functions is executed.
However it cannot rely on any specific loading and execution order of non-dependencies.

As hinted at in the previous paragraph, executing the bootstrap scripts happens in two phases:

1. All bootstrap scripts will be `include()`d in topological order. The script is expected to return a `Closure` that is executed in phase 2.
2. Once all bootstrap scripts have been included, the returned `Closure`s will be executed in the same order the bootstrap scripts were loaded.

```php title="files/lib/bootstrap/com.example.foo.php"
<?php

// Phase (1).

return static function (): void {
    // Phase (2).
};
```

For the vast majority of packages it is expected that the phase (1) bootstrapping is not used, except to return the `Closure`.
Instead the logic should reside in the `Closure`s body that is executed in phase (2).

## Registering `IEvent` listeners

An example use case for bootstrap scripts is registering event listeners for `IEvent`-based events, instead of using the eventListener PIP.
Registering event listeners within the bootstrap script allows you to leverage your IDE’s autocompletion for class names and and prevents forgetting the explicit uninstallation of old event listeners during a package upgrade.

```php title="files/lib/bootstrap/com.example.bar.php"
<?php

use wcf\system\event\EventHandler;
use wcf\system\event\listener\ValueDumpListener;
use wcf\system\foo\event\ValueAvailable;

return static function (): void {
    EventHandler::getInstance()->register(
        ValueAvailable::class,
        ValueDumpListener::class
    );

    EventHandler::getInstance()->register(
        ValueAvailable::class,
        static function (ValueAvailable $event): void {
            // For simple use cases a `Closure` instead of a class name may be used.
            \var_dump($event->getValue());
        }
    );
};
```
