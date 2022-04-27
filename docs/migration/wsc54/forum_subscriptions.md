# Migrating from WSC 5.4 - WoltLab Suite Forum

## Subscriptions

With WoltLab Suite Forum 5.5 we introduced a new system to subscribe threads and boards including the possibility to ignore threads and boards.
[You can read more about this feature in our blog](https://www.woltlab.com/article/260-new-features-in-woltlab-suite-5-5-revision-of-buttons-and-ignoring-threads/).
The new system uses an own system to manage the subscribed forums as well as the subscribed threads.
This has made the previously used object type `com.woltlab.wcf.user.objectWatch` obsolete, as it no longer meets the requirements we need for the new more flexible system.
In addition, having our own implementation also makes it much easier to use, as we work with our own tables and we can thus create correct foreign keys.
Therefore, we had to create a new API to manage subscriptions.

### Subscribe to threads

#### Previously

```php
$action = new UserObjectWatchAction([], 'subscribe', [
    'data' => [
        'objectID' => $threadID,
        'objectType' => 'com.woltlab.wbb.thread',
    ]
]);
$action->executeAction();
```

#### Now
```php
ThreadStatusHandler::saveSubscriptionStatus(
    $threadID,
    ThreadStatusHandler::SUBSCRIPTION_MODE_WATCHING
);
```

### Filter Ignored Threads

To filter ignored threads from a given `ThreadList`, you can use the method `ThreadStatusHandler::addFilterForIgnoredThreads()` to append the filter for ignored threads.
The `ViewableThreadList` filters ignored threads by default.

As an example:

```php
$threadList = new ThreadList();
ThreadStatusHandler::addFilterForIgnoredThreads(
    $threadList,
    // This parameter is optional. If null, the current user will be used. Otherwise, the filter is executed
    // for the given user.
    WCF::getUser()
);
$threadList->readObjects();
```

### Filter Ignored Users

Ignoring threads should surpress the notifications for the user.
Therefore we ship also a method, which can filter the `userIDs`, which are ignoring a specific thread.

```php
$userIDs = [1, 2, 3];
$users = ThreadStatusHandler::filterIgnoredUserIDs(
    $userIDs,
    $thread->threadID
);
```

### Subscribe to boards

#### Previously

```php
$action = new UserObjectWatchAction([], 'subscribe', [
    'data' => [
        'objectID' => $boardID,
        'objectType' => 'com.woltlab.wbb.board',
    ]
]);
$action->executeAction();
```

#### Now
```php
BoardStatusHandler::saveSubscriptionStatus(
    $boardID,
    ThreadStatusHandler::SUBSCRIPTION_MODE_WATCHING
);
```

### Filter Ignored Boards

With the new system, notifications from ignored boards should be surpressed.
Therefore, we introduced a method, which filters userIDs which ignoring a specific board.

```php
$userIDs = [1, 2, 3];
$users = BoardStatusHandler::filterIgnoredUserIDs(
    $userIDs,
    $board->boardID
);
```