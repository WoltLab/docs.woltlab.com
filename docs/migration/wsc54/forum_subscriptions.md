# Migrating from WSC 5.4 - WoltLab Suite Forum

## Subscriptions

With WoltLab Suite Forum 5.5 we have introduced a new system for subscribing to threads and boards, which also offers the possibility to ignore threads and boards.
[You can learn more about this feature in our blog](https://www.woltlab.com/article/260-new-features-in-woltlab-suite-5-5-revision-of-buttons-and-ignoring-threads/).
The new system uses a separate mechanism to track the subscribed forums as well as the subscribed threads.
The previously used object type `com.woltlab.wcf.user.objectWatch` is now discontinued, because the object watch system turned out to be too limited for the complex logic behind thread and forum subscriptions.

### Subscribe to Threads

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
The `ViewableThreadList` filters out ignored threads by default.

Example:

```php
$user = new User(123);
$threadList = new ThreadList();
ThreadStatusHandler::addFilterForIgnoredThreads(
    $threadList,
    // This parameter specifies the target user. Defaults to the current user if the parameter
    // is omitted or `null`.
    $user
);
$threadList->readObjects();
```

### Filter Ignored Users

Avoid issuing notifications to users that have ignored the target thread by filtering those out.

```php
$userIDs = [1, 2, 3];
$users = ThreadStatusHandler::filterIgnoredUserIDs(
    $userIDs,
    $thread->threadID
);
```

### Subscribe to Boards

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

Similar to ignored threads you will also have to avoid issuing notifications for boards that a user has ignored.

```php
$userIDs = [1, 2, 3];
$users = BoardStatusHandler::filterIgnoredUserIDs(
    $userIDs,
    $board->boardID
);
```