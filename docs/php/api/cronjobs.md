# Cronjobs

Cronjobs offer an easy way to execute actions periodically, like cleaning up the database.

!!! warning "The execution of cronjobs is not guaranteed but requires someone to access the page with JavaScript enabled."

This page focuses on the technical aspects of cronjobs, [the cronjob package installation plugin page](../../package/pip/cronjob.md) covers how you can actually register a cronjob.


## Example

{jinja{ codebox(
  title="files/lib/system/cronjob/LastActivityCronjob.class.php",
  language="php",
  filepath="php/api/cronjobs/LastActivityCronjob.class.php"
) }}


## `ICronjob` Interface

Every cronjob needs to implement the `wcf\system\cronjob\ICronjob` interface which requires the `execute(Cronjob $cronjob)` method to be implemented.
This method is called by [wcf\system\cronjob\CronjobScheduler](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/cronjob/CronjobScheduler.class.php) when executing the cronjobs.

In practice, however, you should extend the `AbstractCronjob` class and also call the `AbstractCronjob::execute()` method as it fires an event which makes cronjobs extendable by plugins (see [event documentation](events.md)).
