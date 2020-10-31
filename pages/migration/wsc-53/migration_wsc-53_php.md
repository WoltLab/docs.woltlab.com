---
title: Migrating from WSC 5.3 - PHP
sidebar: sidebar
permalink: migration_wsc-53_php.html
folder: migration/wsc-53
---

## Flood Control

To prevent users from creating massive amounts of contents in short periods of time, i.e., spam, existing systems already use flood control mechanisms to prevent the amount of contents created within a certain period of time.
With version 5.4, we have added a general API that handles flood control and requires the following steps to use it:

1. Register an object type for the definition `com.woltlab.wcf.floodControl`.
2. Whenever the active user creates content of this type, call
   ```php
   FloodControl::getInstance()->registerContent('<your object type>');
   ```
   You should only call this method if the users manually creates the content and not if the content is created by the system, for examples when copying/duplicating existing content.
3. To check the last time when the active user created content of the relevant type, use
   ```php
   FloodControl::getInstance()->getLastTime('<your object type>');
   ```
   If you limit the number of content items creates within a certain period of time, for example within one day, use
   ```php
   $data = FloodControl::getInstance()->countContent('<your object type>', new \DateInterval('P1D'));
   // number of content items created within the last day
   $count = $data['count'];
   // timestamp when the earliest content item was created within the last day
   $earliestTime = $data['earliestTime'];
   ```
   The method also returns `earliestTime` so that you can tell the user in the error message when they are able again to create new content of the relevant type.
   {% include callout.html content="Flood control entries are only stored for 31 days and older entries are cleaned up daily." type="info" %}

The previously mentioned methods of `FloodControl` use the active user and the current timestamp as reference point.
`FloodControl` also provides methods to register content or check flood control for other registered users or for guests via their IP address.
For further details on these methods, refer to the [class](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/flood/FloodControl.class.php) directly.

{% include callout.html content="Do not interact directly with the flood control database table but only via the `FloodControl` class!" type="warning" %}
