---
title: Runtime Caches
sidebar: sidebar
permalink: php_api_caches_runtime-caches.html
folder: php/api/cache
parent: php_api_caches
---

Runtime caches store objects created during the runtime of the script and are automatically discarded after the script terminates.
Runtime caches are especially useful when objects are fetched by different APIs, each requiring separate requests.
By using a runtime cache, you have two advantages:

1. If the API allows it, you can delay fetching the actual objects and initially only tell the runtime cache that at some point in the future of the current request, you need the objects with the given ids.
   If multiple APIs do this one after another, all objects can be fetched using only one query instead of each API querying the database on its own.
1. If an object with the same ID has already been fetched from database, this object is simply returned and can be reused instead of being fetched from database again.


## `IRuntimeCache`

Every runtime cache has to implement the [IRuntimeCache](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/cache/runtime/IRuntimeCache.class.php) interface.
It is recommended, however, that you extend [AbstractRuntimeCache](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/cache/runtime/AbstractRuntimeCache.class.php), the default implementation of the runtime cache interface.
In most instances, you only need to set the `AbstractRuntimeCache::$listClassName` property to the name of database object list class which fetches the cached objects from database (see [example](#example)).


## Usage

```php
<?php
use wcf\system\cache\runtime\UserRuntimeCache;

$userIDs = [1, 2];

// first (optional) step: tell runtime cache to remember user ids
UserRuntimeCache::getInstance()->cacheObjectIDs($userIDs);

// […]

// second step: fetch the objects from database
$users = UserRuntimeCache::getInstance()->getObjects($userIDs);

// somewhere else: fetch only one user
$userID = 1;

UserRuntimeCache::getInstance()->cacheObjectID($userID);

// […]

// get user without the cache actually fetching it from database because it has already been loaded
$user = UserRuntimeCache::getInstance()->getObject($userID);

// somewhere else: fetch users directly without caching user ids first
$users = UserRuntimeCache::getInstance()->getObjects([3, 4]);
```


## Example

```php
<?php
namespace wcf\system\cache\runtime;
use wcf\data\user\User;
use wcf\data\user\UserList;

/**
 * Runtime cache implementation for users.
 *
 * @author	Matthias Schmidt
 * @copyright	2001-2016 WoltLab GmbH
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	WoltLabSuite\Core\System\Cache\Runtime
 * @since	3.0
 * 
 * @method	User[]		getCachedObjects()
 * @method	User		getObject($objectID)
 * @method	User[]		getObjects(array $objectIDs)
 */
class UserRuntimeCache extends AbstractRuntimeCache {
	/**
	 * @inheritDoc
	 */
	protected $listClassName = UserList::class;
}
```
