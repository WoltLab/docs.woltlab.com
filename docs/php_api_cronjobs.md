---
title: Cronjobs
sidebar: sidebar
permalink: php_api_cronjobs.html
folder: php/api
---

Cronjobs offer an easy way to execute actions periodically, like cleaning up the database.

{% include callout.html content="The execution of cronjobs is not guaranteed but requires someone to access the page with JavaScript enabled." type="warning" %}

This page focuses on the technical aspects of cronjobs, [the cronjob package installation plugin page](package_pip_cronjob.html) covers how you can actually register a cronjob.


## Example

```php
<?php
namespace wcf\system\cronjob;
use wcf\data\cronjob\Cronjob;
use wcf\system\WCF;

/**
 * Updates the last activity timestamp in the user table.
 * 
 * @author	Marcel Werk
 * @copyright	2001-2016 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	WoltLabSuite\Core\System\Cronjob
 */
class LastActivityCronjob extends AbstractCronjob {
	/**
	 * @inheritDoc
	 */
	public function execute(Cronjob $cronjob) {
		parent::execute($cronjob);
		
		$sql = "UPDATE	wcf".WCF_N."_user user_table,
				wcf".WCF_N."_session session
			SET	user_table.lastActivityTime = session.lastActivityTime
			WHERE	user_table.userID = session.userID
				AND session.userID <> 0";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute();
	}
}

```


## `ICronjob` Interface

Every cronjob needs to implement the `wcf\system\cronjob\ICronjob` interface which requires the `execute(Cronjob $cronjob)` method to be implemented.
This method is called by [wcf\system\cronjob\CronjobScheduler](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/cronjob/CronjobScheduler.class.php) when executing the cronjobs.

In practice, however, you should extend the `AbstractCronjob` class and also call the `AbstractCronjob::execute()` method as it fires an event which makes cronjobs extendable by plugins (see [event documentation](php_event.html)).


## Executing Cronjobs Through CLI

Cronjobs can be executed through the command-line interface (CLI):

```
php /path/to/wcf/cli.php << 'EOT'
USERNAME
PASSWORD
cronjob execute
EOT
```