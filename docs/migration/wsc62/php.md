# Migrating from WoltLab Suite 6.2 - PHP

## Category List Pages

The legacy category list pages based on `AbstractCategoryListPage` have been replaced by [Node Tree Views](../../php/api/node_tree_views.md).
The new implementation renders the categories using `CategoryNodeTreeView` and integrates with the [Interactions](../../php/api/interactions.md) system, which provides drag and drop sorting, an interaction context menu and inline quick interactions out of the box.

To migrate an existing category list page to the new infrastructure, the following steps are required.

### Extend `AbstractCategoryNodeTreeViewPage`

Change the parent class of the list page from `AbstractCategoryListPage` to `AbstractCategoryNodeTreeViewPage` and update the type declaration of `$objectTypeName` from untyped to `string`:

```php
class ArticleCategoryListPage extends AbstractCategoryNodeTreeViewPage
{
    public $activeMenuItem = 'wcf.acp.menu.link.article.category.list';

    public string $objectTypeName = 'com.woltlab.wcf.article.category';
}
```

The previously used template, action handlers and read methods are no longer required and can be removed.
`AbstractCategoryNodeTreeViewPage` uses the `categoryNodeTreeView` template and creates the matching `CategoryNodeTreeView` instance automatically.

### Implement `getEditControllerClass()` and `getAddControllerClass()`

Two new methods have been added to `ICategoryType`:

- `getEditControllerClass(): string` returns the class name of the controller used to edit categories of this type.
- `getAddControllerClass(): string` returns the class name of the controller used to add categories of this type.

Both methods must be implemented by every concrete category type so that the node tree view and the interactions can link to the correct edit and add forms.

```php
class ArticleCategoryType extends AbstractCategoryType
{
    #[\Override]
    public function getEditControllerClass(): string
    {
        return ArticleCategoryEditForm::class;
    }

    #[\Override]
    public function getAddControllerClass(): string
    {
        return ArticleCategoryAddForm::class;
    }
}
```

`AbstractCategoryType` provides empty default implementations to avoid breaking existing third-party category types. Categories of types that do not provide these methods will not be editable through the node tree view.

### Pre-Selecting the Parent Category in the Add Form

`CategoryAddFormBuilderForm` now reads the optional `parentCategoryID` GET parameter and pre-selects the corresponding parent category in the add form.
This is used by the “Add Child Category” interaction in the node tree view to allow users to directly create a child category for a given node.

Custom subclasses of `CategoryAddFormBuilderForm` do not require any changes for this to work.

### Implement `getAffectedObjects()`

Deleting a category can also delete the objects assigned to it.
The new method `ICategoryType::getAffectedObjects(): array` returns the names of these objects, which are
then listed in the [confirmation prompt](../../php/api/interactions.md#associated-objects) of the delete
interaction.
The names are expected to be already localized.

```php
class ArticleCategoryType extends AbstractCategoryType
{
    #[\Override]
    public function getAffectedObjects(): array
    {
        return [WCF::getLanguage()->get('wcf.article.articles')];
    }
}
```

`AbstractCategoryType` provides a default implementation that returns an empty array, therefore existing
third-party category types do not require any changes.
Child categories should not be listed, because they are moved to the parent category instead of being deleted.

## ACP Search Providers

The registration of ACP search providers has been overhauled ([WoltLab/WCF#6681](https://github.com/WoltLab/WCF/issues/6681)).

### Registering Providers Through an Event

Providers are now registered through the PSR-14 event `wcf\event\acp\search\provider\ProviderCollecting`.
See the [ACP search providers](../../package/acp-search-providers.md) page for the full API reference.
The `acpSearchProvider` package installation plugin and the underlying database object remain available but are deprecated and should no longer be used for new providers.

A bootstrap script registers an event listener that adds the provider instances:

```php
EventHandler::getInstance()->register(
    \wcf\event\acp\search\provider\ProviderCollecting::class,
    static function (\wcf\event\acp\search\provider\ProviderCollecting $event) {
        $event->register(
            'com.example.myProvider',
            new \example\system\search\acp\MyAcpSearchResultProvider()
        );
    }
);
```

The provider class must still implement `wcf\system\search\acp\IACPSearchResultProvider`.
The first argument of `register()` is the textual identifier that is also used as the language item key for the result group title (`wcf.acp.search.provider.{providerName}`).

Providers are sorted by their translated label; the explicit `showOrder` from the deprecated PIP is no longer evaluated for event-registered providers.

Providers that are still registered through the deprecated `acpSearchProvider` PIP continue to work and are merged with the event-registered providers, but they are shadowed by event-registered providers using the same `providerName`.

### Searching Through the RPC API

The legacy AJAX action `wcf\data\acp\search\provider\ACPSearchProviderAction::getSearchResultList()` has been replaced by the RPC endpoint `GET /core/acp/search`.

| Parameter | Type | Description |
|-----------|------|-------------|
| `query` | `non-empty-string` | The search query. |
| `provider` | `string` (optional) | Restrict the search to a single provider identified by its `providerName`. |

The response payload has the following shape:

```json
{
    "results": [
        {
            "title": "Translated provider title",
            "items": [
                { "title": "…", "link": "…", "subtitle": "…" }
            ]
        }
    ]
}
```

The endpoint requires the `admin.general.canUseAcp` permission.
Each registered provider remains responsible for enforcing its own admin permissions inside `IACPSearchResultProvider::search()`.

## PSR-7 Responses in Pages and Forms

Since WoltLab Suite 5.5 a page or form could abort request processing in two different ways: by returning a PSR-7 `ResponseInterface` from one of its lifecycle methods, or by emitting the response manually using `HeaderUtil::redirect()` followed by `exit`.
Both approaches are problematic.
Returning a response forces every intermediate override to forward the return value of its parent call, and a manually emitted redirect bypasses the PSR-15 middleware pipeline entirely, causing it to miss the cache-control headers that are applied to every regular response.

WoltLab Suite 6.3 therefore standardizes on `setPsr7Response()`, which exists since WoltLab Suite 5.5, as the only supported way to define the response of a page or a form.

### Changed Return Types

The following methods are now declared as `@return void` and no longer accept a `ResponseInterface` as their return value:

| Interface | Methods |
|-----------|---------|
| `wcf\page\IPage` | `readParameters()`, `readData()`, `show()` |
| `wcf\form\IForm` | `readFormParameters()`, `validate()`, `save()` |

`IPage::__run()` is not affected and continues to be declared as `@return void|ResponseInterface`.

Returning a response from one of the methods listed above keeps working at runtime, because `AbstractPage::__run()` and `AbstractForm::submit()` still pass the return value through `maybeSetPsr7Response()`.
The behavior is retained for backwards compatibility only and should not be relied upon for new code; static analysis will report the return value as unused.

### Migrating a Returned Response

Previously:

```php
#[\Override]
public function readParameters()
{
    parent::readParameters();

    if ($this->shouldRedirect()) {
        return new RedirectResponse(
            LinkHandler::getInstance()->getControllerLink(ExampleListPage::class)
        );
    }

    // …
}
```

Now:

```php
#[\Override]
public function readParameters()
{
    parent::readParameters();

    if ($this->shouldRedirect()) {
        $this->setPsr7Response(new RedirectResponse(
            LinkHandler::getInstance()->getControllerLink(ExampleListPage::class),
            303
        ));

        return;
    }

    // …
}
```

`setPsr7Response()` only stores the response, it does not abort the current method.
The explicit `return` is required whenever the call is not the last statement of the method.

Redirects that follow a successful form submission should use the status code 303 (See Other) instead of relying on the default of 302, so that the browser performs the follow-up request using `GET`.

### Migrating `HeaderUtil::redirect()`

Redirects that were emitted manually are migrated the same way:

```php
// previously
HeaderUtil::redirect(LinkHandler::getInstance()->getControllerLink(ExampleListPage::class));

exit;

// now
$this->setPsr7Response(new RedirectResponse(
    LinkHandler::getInstance()->getControllerLink(ExampleListPage::class),
    303
));

return;
```

Take care when replacing an `exit`: it terminated the whole request, whereas the `return` only leaves the current method.
Every caller up the chain must be able to cope with the aborted method and must not continue as if the method had completed successfully.
Use the `hasPsr7Response()` method to guard the remaining logic:

```php
#[\Override]
public function readParameters()
{
    parent::readParameters();

    $this->readObjectType();
    if ($this->hasPsr7Response()) {
        return;
    }

    $this->readObjectList();
}
```

### `AbstractForm::readData()`

`AbstractForm::readData()` now aborts after `submit()` if a response was set, meaning that `AbstractPage::readData()` is no longer called in that case:

```php
#[\Override]
public function readData()
{
    if ($_POST !== [] || $_FILES !== []) {
        $this->submit();
        if ($this->hasPsr7Response()) {
            return;
        }
    }

    parent::readData();
}
```

Forms that set a response inside `save()` and override `readData()` must add the same guard, because the code following the `parent::readData()` call was previously unreachable due to the `exit` in `save()`:

```php
#[\Override]
public function readData()
{
    parent::readData();

    if ($this->hasPsr7Response()) {
        return;
    }

    $this->readOptionTree();
}
```

As a consequence of the early return, the `readData` event is no longer fired if a response was set during the submission of the form.
Event listeners that relied on being called in this situation must be moved to the `saved` event.

## Access Checks for Views

[Grid views](../../php/api/grid_views.md) and [list views](../../php/api/list_views.md) are exposed through RPC endpoints that instantiate the view by its class name.
The only protection against unauthorized access is `isAccessible()`, whose default implementation grants access to everybody.
Views that did not override this method were therefore accessible to any user, regardless of the permissions of the page they were embedded in.

`isAccessible()` must now be implemented by every view:

```php
final class ExampleGridView extends AbstractGridView
{
    #[\Override]
    public function isAccessible(): bool
    {
        return WCF::getSession()->hasPermission('admin.example.canManageExample');
    }
}
```

The returned value should match the requirements of the page that renders the view, including the checks for the required modules.

`AbstractGridView::isAccessible()` and `AbstractListView::isAccessible()` are deprecated and will become abstract with WoltLab Suite 6.4.
Until then they keep returning `true`, but throw a `BadMethodCallException` if both the debug mode and the developer tools are enabled, in order to surface views that have not been updated yet.

!!! warning "Do not call `parent::isAccessible()` when the parent class does not implement it itself, the deprecated default implementation throws in debug mode. Views that extend another concrete view, for example `ArticleListView`, may continue to delegate to their parent."

## Sitemaps

The registration of sitemap objects has been overhauled.

### Registering Sitemap Objects Through an Event

Sitemap objects are now registered through the PSR-14 event `wcf\event\sitemap\SitemapObjectCollecting`.
See the [sitemaps](../../php/api/sitemaps.md) page for the full API reference.
The object type definition `com.woltlab.wcf.sitemap.object` remains available but is deprecated and should no longer be used for new sitemap objects.

A bootstrap script registers an event listener that adds the sitemap objects:

```php
EventHandler::getInstance()->register(
    \wcf\event\sitemap\SitemapObjectCollecting::class,
    static function (\wcf\event\sitemap\SitemapObjectCollecting $event) {
        $event->register(new \wcf\system\sitemap\object\RegisteredSitemapObject(
            'com.example.plugin.sitemap.object.foo',
            new \example\system\sitemap\object\FooSitemapObject(),
            rebuildTime: 259200,
            packageID: $packageID,
        ));
    }
);
```

The processor class must still implement `wcf\system\sitemap\object\ISitemapObjectObjectType`.
The first argument of the `RegisteredSitemapObject` constructor is the name of the sitemap object that was previously the name of the object type.
It is used as the file name of the generated sitemap, as the key of the settings made by the administrator and as the suffix of the language item `wcf.acp.sitemap.objectType.{objectName}`.
Keeping the previous object type name therefore preserves the settings of existing installations.

Sitemap objects that are still registered through the deprecated object type definition continue to work and are merged with the objects registered through the event, but they are shadowed by an object registered through the event using the same name.

### Sitemap List in the ACP

`wcf\acp\page\SitemapListPage` has been converted into a grid view page and now renders `wcf\system\gridView\admin\SitemapGridView`.
The template events `headColumns`, `columns` and `contentFooterNavigation` of the template `sitemapList` have been removed.
Additional columns are added through the PSR-14 event `wcf\event\gridView\admin\SitemapGridViewInitialized` instead.

## Recent Activity Events of Guests

Recent activity events now support content created by guests.
The `userID` column of `wcf1_user_activity_event` is nullable and the new `username` column stores the name of the guest.

A user id of `null` passed to `UserActivityEventHandler::fireEvent()` now denotes a guest and requires the guest's name to be passed as `username`.
Pass the guest's name instead of skipping guests:

```php
// Previously
if ($foo->userID !== null) {
    UserActivityEventHandler::getInstance()->fireEvent(
        'foo.bar.recentActivityEvent',
        $foo->fooID,
        null,
        $foo->userID,
        $foo->time
    );
}

// Since 6.3
UserActivityEventHandler::getInstance()->fireEvent(
    'foo.bar.recentActivityEvent',
    $foo->fooID,
    null,
    $foo->userID,
    $foo->time,
    username: $foo->username
);
```

`UserActivityEventHandler::fireEvents()` accepts an optional `username` key for each event.

There is no longer a fallback to the active user.
Previously, omitting the user id or passing `null` attributed the event to the active user.
`fireEvent()` and `fireEvents()` now throw a `\BadMethodCallException` if neither a user id nor a username is given, and `removeEvent()` no longer defaults to the active user.
Always pass the user id explicitly, for example `WCF::getUser()->userID` if the event is triggered by the active user.

Custom queries on `wcf1_user_activity_event` must take `NULL` values into account.
A condition like `userID NOT IN (?)` excludes all events of guests and must be written as `(userID IS NULL OR userID NOT IN (?))`.

Implementations of `IUserActivityEvent` that require a registered user, for example to look up the author of a comment, must handle guests.
The `TCommentResponseUserActivityEvent` trait provides `getCommentAuthor()` that returns a guest profile for comments written by guests.

## User Merging

The ACP form `wcf\acp\form\UserMergeForm` has been replaced by a dialog of the bulk interactions in the user list, the users are merged by the command `wcf\command\user\MergeUsers`.
`UserMergeForm` and `wcf\system\event\listener\AbstractUserMergeListener` are deprecated.
Event listeners for the `save` event of `UserMergeForm` are still executed, but should be migrated.

The PSR-14 event `wcf\event\user\UsersMerging` is fired before the merged users are deleted.
Instead of extending `AbstractUserMergeListener`, a bootstrap script registers the database columns that reference users through `UsersMerging::reassignUserColumn()`.
Unlike `AbstractUserMergeListener`, the column containing the username is not updated unless it is explicitly passed as `usernameColumn`.

```php
// Previously
class FooUserMergeListener extends AbstractUserMergeListener
{
    protected $databaseTables = [
        'foo1_foo',
        [
            'name' => 'foo1_foo',
            'userID' => 'editorID',
            'username' => 'editor',
        ],
        [
            'name' => 'foo1_bar',
            'username' => null,
            'ignore' => true,
        ],
    ];
}

// Since 6.3
EventHandler::getInstance()->register(
    \wcf\event\user\UsersMerging::class,
    static function (\wcf\event\user\UsersMerging $event) {
        $event->reassignUserColumn('foo1_foo', usernameColumn: 'username');
        $event->reassignUserColumn('foo1_foo', 'editorID', 'editor');
        $event->reassignUserColumn('foo1_bar', ignore: true);
    }
);
```

The registered columns are updated after all event listeners have been executed.
Listeners that need to perform other changes can access the destination user and the merged users through the properties `destinationUser` and `mergedUsers` of the event.

## User Renaming

Users are renamed through the command `wcf\command\user\UpdateUser`, which fires the PSR-14 event `wcf\event\user\UserRenamed`.
`wcf\system\event\listener\AbstractUserActionRenameListener` is deprecated.
Event listeners for the `rename` event of `wcf\data\user\UserAction` are still executed, but should be migrated.

Instead of extending `AbstractUserActionRenameListener`, a bootstrap script registers the database columns containing the username through `UserRenamed::updateUsernameColumn()`.

```php
// Previously
class FooUserActionRenameListener extends AbstractUserActionRenameListener
{
    protected $databaseTables = [
        'foo1_foo',
        [
            'name' => 'foo1_foo',
            'userID' => 'editorID',
            'username' => 'editor',
        ],
    ];
}

// Since 6.3
EventHandler::getInstance()->register(
    \wcf\event\user\UserRenamed::class,
    static function (\wcf\event\user\UserRenamed $event) {
        $event->updateUsernameColumn('foo1_foo');
        $event->updateUsernameColumn('foo1_foo', 'editorID', 'editor');
    }
);
```

The registered columns are updated after all event listeners have been executed.
Listeners that need to perform other changes can access the renamed user and the previous username through the properties `user` and `oldUsername` of the event.

## Creating and Updating Users

`wcf\data\user\UserAction` and `wcf\data\user\UserEditor` are deprecated, users are created and modified through the [database object builder](../../php/database-object-builder.md) `wcf\data\user\UserBuilder` and the commands in the `wcf\command\user` namespace.
The commands take care of all side effects, such as updating the user rank, the user online marking and the automatic user group assignments.

```php
// Previously
$action = new UserAction([], 'create', [
    'data' => [
        'username' => 'Foo',
        'email' => 'foo@example.com',
        'password' => $password,
    ],
    'groups' => [$groupID],
    'options' => [
        User::getUserOptionID('location') => 'Berlin',
    ],
]);
$user = $action->executeAction()['returnValues'];

// Since 6.3
$user = (new CreateUser(
    UserBuilder::forCreate()
        ->setUsername('Foo')
        ->setEmail('foo@example.com')
        ->setPassword($password)
        ->setRegistrationDate(\TIME_NOW)
        ->setGroupIDs([
            ...UserGroup::getGroupIDsByType([UserGroup::EVERYONE, UserGroup::USERS]),
            $groupID,
        ])
        ->setUserOption(User::getUserOptionID('location'), 'Berlin')
))();
```

```php
// Previously
$action = new UserAction([$user], 'update', [
    'data' => [
        'userTitle' => 'Bar',
        'fooColumn' => 1,
    ],
]);
$action->executeAction();

// Since 6.3
$user = (new UpdateUser(
    UserBuilder::forUpdate($user)
        ->setUserTitle('Bar')
        ->setCustomProperty('fooColumn', 1)
))();
```

The user options, the user groups and the content languages are stored in separate tables that are written after the user itself.
The object returned by `UserBuilder::create()` and `UserBuilder::update()` is therefore loaded before these tables have been written, always use the `CreateUser` and `UpdateUser` commands which return an up-to-date object.
`UserBuilder::delete()` only removes the database row, use the `DeleteUser` command to delete a user including the associated data.

The methods of `UserAction` and `UserProfileAction` have been replaced as follows:

| Method | Replacement |
| ------ | ----------- |
| `UserAction::create()` | `wcf\command\user\CreateUser` |
| `UserAction::update()` | `wcf\command\user\UpdateUser` |
| `UserAction::delete()` | `wcf\command\user\DeleteUser` |
| `UserAction::addToGroups()` | `wcf\command\user\AddUserToGroups` |
| `UserAction::removeFromGroups()` | `wcf\command\user\RemoveUserFromGroups` |
| `UserAction::ban()` / `unban()` | `wcf\command\user\BanUser` / `UnbanUser` |
| `UserAction::enable()` / `disable()` | `wcf\command\user\EnableUser` / `DisableUser` |
| `UserAction::confirmEmail()` / `unconfirmEmail()` | `wcf\command\user\ConfirmUserEmail` / `UnconfirmUserEmail` |
| `UserAction::enableAvatar()` / `disableAvatar()` | `wcf\command\user\EnableUserAvatar` / `DisableUserAvatar` |
| `UserAction::enableCoverPhoto()` / `disableCoverPhoto()` | `wcf\command\user\EnableUserCoverPhoto` / `DisableUserCoverPhoto` |
| `UserAction::enableSignature()` / `disableSignature()` | `wcf\command\user\EnableUserSignature` / `DisableUserSignature` |
| `UserAction::resendActivationMail()` | `wcf\command\user\ResendActivationMail` |
| `UserAction::saveUserConsent()` | RPC endpoint `wcf\system\endpoint\controller\core\users\SaveUserConsent` |
| `UserAction::devtoolsSetLanguage()` | RPC endpoint `wcf\system\endpoint\controller\core\devtools\SetLanguage` |
| `UserProfileAction::getMessagePreview()` | RPC endpoint `wcf\system\endpoint\controller\core\users\RenderSignaturePreview` |
| `UserProfileAction::getDetailedActivityPointList()` | RPC endpoint `wcf\system\endpoint\controller\core\users\GetUserActivityPointList` |
| `UserProfileAction::updateUserRank()` | `wcf\command\user\RefreshUserRanks` |
| `UserProfileAction::updateUserOnlineMarking()` | `wcf\command\user\RefreshUserOnlineMarkings` |
| `UserProfileAction::updateSpecialTrophies()` | `wcf\command\user\SetSpecialTrophies` |
| `SendNewPasswordWorker` | `wcf\command\user\SendNewPassword` |

Additionally, the commands `DisableUserMultifactor`, `DisconnectThirdPartyLogin` and `MergeUsers` have been added.

!!! warning "WoltLab Suite no longer uses `UserAction` to create, update or delete users. Event listeners for the `initializeAction` and `finalizeAction` events of `UserAction` are no longer executed for these operations and must be migrated to the PSR-14 events below. Conversely, the deprecated methods `UserAction::create()`, `UserAction::update()` and `UserAction::delete()` do not fire the new events."

### Events

The commands fire the following PSR-14 events:

| Event | Description |
| ----- | ----------- |
| `wcf\event\user\UserCreated` | Fired after a user has been created, provides the user and the builder. |
| `wcf\event\user\UserUpdated` | Fired after a user has been updated, provides the updated user and the builder. |
| `wcf\event\user\UserDeleted` | Fired after a user has been deleted. |

All commands that modify a user, such as `BanUser` or `AddUserToGroups`, use `UpdateUser` internally and therefore fire `UserUpdated`.
The builder exposes the changes, `getUserOptions()`, `getGroupIDs()` and `getLanguageIDs()` return `null` if the respective data has not been changed.
The user prior to the update is available through `$event->builder->getObject()`.

```php
EventHandler::getInstance()->register(
    \wcf\event\user\UserUpdated::class,
    static function (\wcf\event\user\UserUpdated $event) {
        if ($event->builder->getGroupIDs() === null) {
            return;
        }

        // The user groups have been changed.
    }
);
```

### Legacy Events of `UserProfile`

The legacy events `getAvatar` and `isAccessible` of `wcf\data\user\UserProfile` have been removed in favor of PSR-14 events.

```php
// Previously
final class FooUserProfileListener implements IParameterizedEventListener
{
    public function execute($eventObj, $className, $eventName, array &$parameters)
    {
        if ($eventName === 'getAvatar') {
            $parameters['avatar'] = new FooAvatar($eventObj->getDecoratedObject());
        } elseif ($eventName === 'isAccessible' && $parameters['name'] === 'fooPrivacySetting') {
            $parameters['result'] = FooPrivacy::isAccessible($eventObj->userID, $parameters['userID']);
        }
    }
}

// Since 6.3
EventHandler::getInstance()->register(
    \wcf\event\user\UserAvatarLoading::class,
    static function (\wcf\event\user\UserAvatarLoading $event) {
        $event->setAvatar(new FooAvatar($event->user));
    }
);

EventHandler::getInstance()->register(
    \wcf\event\user\UserPrivacySettingChecking::class,
    static function (\wcf\event\user\UserPrivacySettingChecking $event) {
        if ($event->name === 'fooPrivacySetting') {
            $event->setAccessible(FooPrivacy::isAccessible($event->user->userID, $event->viewerUserID));
        }
    }
);
```

## Permissions and Profile Data of Users

The permission checks for the administration of users are available as methods of `wcf\data\user\User`, which are used by the interactions, the RPC endpoints and the forms:

- `isManageable(bool $allowSelf = false)` returns `true` if all user groups of the user are accessible to the active user and, unless `$allowSelf` is set, the user is not the active user.
- `canEnable()`, `canConfirmEmail()`, `canBan()`, `canDisableAvatar()`, `canDisableCoverPhoto()`, `canDisableSignature()`, `canSendNewPassword()`, `canDisconnectThirdPartyLogin()`, `canDisableMultifactor()`, `canResendActivationMail()`, `canDelete()`, `canExportGdpr()` and `canMail()` check the permission for the respective action.

The result of `canConfirmEmail()` can be modified through the PSR-14 event `wcf\event\user\UserEmailConfirmationChecking`.
Do not confuse this method with the existing `canEmailConfirm()`, which checks whether the user can confirm the email address themselves.

The avatar, the rank, the user title, the online status and the privacy settings are also available on `User` through `getAvatar()`, `getRank()`, `getUserTitle()`, `isOnline()` and `isAccessible()`, it is no longer necessary to create a `UserProfile` for these.
The data required for these methods is loaded for all users of the same collection at once (`wcf\data\user\UserCollection`).
The corresponding methods of `UserProfile` remain available.
The constants `UserProfile::ACCESS_*` are aliases of the new constants `User::ACCESS_*`.

## ACP User Add and Edit Form

`wcf\acp\form\UserAddForm` and `wcf\acp\form\UserEditForm` have been migrated to the form builder and are based on `AbstractDatabaseObjectBuilderForm` using `UserBuilder`.
All properties representing the form values and all template events of the `userAdd` template except for `contentHeaderNavigation` have been removed.
The options to ban a user, to disable the avatar, cover photo and signature of a user, to disconnect a third party login and to disable the multi-factor authentication have been moved to the interactions on the edit page.

Additional form fields can be added through the `createForm` event of `UserAddForm`, which is inherited by `UserEditForm`.
The values are written into the builder using `saveValueCallback()` and read from the user using `loadValueCallback()`.

```php
final class FooUserAddFormListener implements IParameterizedEventListener
{
    public function execute($eventObj, $className, $eventName, array &$parameters)
    {
        $eventObj->form->getNodeById('general')->appendChild(
            BooleanFormField::create('fooEnabled')
                ->label('wcf.acp.user.fooEnabled')
                ->saveValueCallback(static function (UserBuilder $builder, BooleanFormField $field) {
                    $builder->setCustomProperty('fooEnabled', $field->getSaveValue());
                })
                ->loadValueCallback(static function (User $user, BooleanFormField $field) {
                    $field->value($user->fooEnabled);
                })
        );
    }
}
```

```xml
<eventlistener name="fooUserAddForm">
	<environment>admin</environment>
	<eventclassname>wcf\acp\form\UserAddForm</eventclassname>
	<eventname>createForm</eventname>
	<listenerclassname>wcf\system\event\listener\FooUserAddFormListener</listenerclassname>
	<inherit>1</inherit>
</eventlistener>
```

The form consists of the tab menu `userTabs` with the tabs `generalTab`, `profileTab`, `settingsTab` and `signatureTab`.
The general data is contained in the form container `general`.
Data that is stored in other tables can be saved using the `UserCreated` and `UserUpdated` events.

`wcf\form\RegisterForm` and `wcf\form\SettingsForm` have also been migrated to the form builder and are now final.
The template events `generalFields`, `honeyPotFields` and `sections` of the `register` template and `languageFields`, `styleFields`, `trophyFields` and `sections` of the `settings` template have been removed.

## User List

The user list in the ACP (`wcf\acp\page\UserListPage`) has been migrated to a [grid view](../../php/api/grid_views.md) (`wcf\system\gridView\admin\UserGridView`) and is now final.
The bulk actions of the user list are provided as [bulk interactions](../../php/api/interactions.md#bulk-interactions) instead of the clipboard.
The grid view, the interactions and the bulk interactions can be extended through the events `wcf\event\gridView\admin\UserGridViewInitialized`, `wcf\event\interaction\admin\UserInteractionCollecting` and `wcf\event\interaction\bulk\admin\UserBulkInteractionCollecting`.

```php
EventHandler::getInstance()->register(
    \wcf\event\gridView\admin\UserGridViewInitialized::class,
    static function (\wcf\event\gridView\admin\UserGridViewInitialized $event) {
        $event->gridView->addColumnAfter(
            GridViewColumn::for('fooCount')
                ->label('wcf.acp.user.fooCount')
                ->renderer(new NumberColumnRenderer())
                ->sortable()
                ->hidden(),
            'lastActivityTime'
        );
        $event->gridView->addAvailableFilters([
            new BooleanFilter('fooEnabled', 'wcf.acp.user.fooEnabled'),
        ]);
    }
);

EventHandler::getInstance()->register(
    \wcf\event\interaction\bulk\admin\UserBulkInteractionCollecting::class,
    static function (\wcf\event\interaction\bulk\admin\UserBulkInteractionCollecting $event) {
        $event->provider->addInteraction(
            new BulkLinkInteraction('foo', FooForm::class, 'wcf.acp.user.foo')
        );
    }
);
```

The user search in the ACP (`wcf\acp\form\UserSearchForm`) and in the frontend (`wcf\form\UserSearchForm`) has been removed in favor of the filters of the user list and of the members list.
Links to a filtered user list can be generated using the `filters` parameter, e.g. `UserList?filters[banned]=1`.
The object type definition `com.woltlab.wcf.condition.userSearch` is deprecated and its object types are no longer used, use the `UserGridViewInitialized` event to add custom filters instead.

The forms to assign users to groups, to export email addresses, to merge users and to revert content changes have been replaced by dialogs of the bulk interactions.

## User Profile Header in the ACP

The edit page of a user in the ACP shows the profile header of the user (`wcf\system\view\user\profile\AdminUserProfileHeaderView`), which provides the interactions of the user.
The header fires the existing events `wcf\event\user\profile\UserProfileStatItemCollecting` and `wcf\event\user\profile\UserProfileHeaderSearchContentLinkCollecting`, which means that their listeners are also executed in the ACP.
Links of stat items must be absolute, e.g. `$user->getLink() . '#foo'` instead of `#foo`.

## User Options

The user options have been migrated to [form options](../../php/api/form_builder/form_options.md), which provide the input field, the formatted output, the database column and the filter of an option.
`wcf\data\user\option\UserOption` no longer extends `wcf\data\option\Option`.

### Package Installation Plugin

The value of `<optiontype>` in the [userOption PIP](../../package/pip/user-option.md) is the id of a form option.
The legacy option types `aboutMe`, `message`, `multiSelect`, `URL` and `labeledUrl` are converted automatically, the fields `outputclass`, `enableoptions`, `contentpattern` and `messageObjectType` as well as arbitrary additional fields are ignored.
Values of `checkboxes` options, including the default value, are separated by commas instead of newlines.

The new option type `socialMedia` replaces `labeledUrl` and plain text options that contain the profile name on a social media platform:

```xml
<!-- Previously -->
<option name="exampleMastodon">
	<categoryname>profile.contact</categoryname>
	<optiontype>text</optiontype>
	<validationpattern>^$|^[A-Za-z0-9_]+$</validationpattern>
	<outputclass>wcf\system\option\user\ExampleMastodonUserOptionOutput</outputclass>
	<visible>15</visible>
	<editable>3</editable>
</option>

<!-- Since 6.3 -->
<option name="exampleMastodon">
	<categoryname>profile.contact</categoryname>
	<optiontype>socialMedia</optiontype>
	<profileurlpattern>https://mastodon.social/@%s</profileurlpattern>
	<profilenamepattern>^[A-Za-z0-9_]+$</profilenamepattern>
	<visible>15</visible>
	<editable>3</editable>
</option>
```

Existing user options are converted during the upgrade to 6.3.

### Custom Option Types

Custom option types (`wcf\system\option\IOptionType`) and output classes (`wcf\system\option\user\IUserOptionOutput`) are no longer used for user options, options with an unknown type are treated as `text` options.
Custom types must be implemented as a form option (`wcf\system\form\option\IFormOption`) and registered through the `wcf\event\form\option\FormOptionCollecting` event.
The column of the option in `wcf1_user_option_value` is created based on `IFormOption::getDatabaseTableColumn()`.

The new method `IFormOption::matchesFilterValue()` must be implemented by form options that do not extend `AbstractFormOption`.

### Formatted Values

The output classes have been replaced by `UserOption::getFormattedValue()`, `wcf\data\user\option\ViewableUserOption` is deprecated.

```php
// Previously
$option = new ViewableUserOption($userOption);
$option->setOptionValue($userProfile->getDecoratedObject());
$output = $option->optionValue;

// Since 6.3
$output = $userProfile->getFormattedUserOption('foo');
// or
$output = $userOption->getFormattedValue($user->getUserOption('foo'), $user);
```

`UserProfile::getFormattedUserOption()` returns an empty string if the option has no value or is not visible to the active user, while `UserOption::getFormattedValue()` does not check the visibility.

### Visibility and Editability

`UserOption::setUser()` has been removed, the user is passed to `isVisible()` and `isEditable()` instead.
This avoids side effects caused by modifying the shared option objects of the cache.

```php
// Previously
$userOption->setUser($user);
if ($userOption->isVisible()) {
    // …
}

// Since 6.3
if ($userOption->isVisible($user)) {
    // …
}
```

### Forms and Lists

`wcf\system\option\user\UserOptionHandler`, `wcf\acp\form\UserOptionListForm` and the templates `userOptionFieldList` and `userProfileOptionFieldList` have been removed.
The following traits provide the building blocks to integrate user options:

- `wcf\system\option\user\TUserOptionsByCategory` returns the options of a category tree grouped by the subcategories (`getUserOptionsByCategory()`) and the title of a category (`getUserOptionCategoryTitle()`).
- `wcf\system\form\option\user\TUserOptionFormFields` creates form containers and form fields for user options in forms based on `AbstractDatabaseObjectBuilderForm` using `UserBuilder`.
- `wcf\system\option\user\TJoinUserOptionValues` joins the values of the user options to a database object list of users.

```php
final class FooForm extends AbstractDatabaseObjectBuilderForm
{
    use TUserOptionFormFields;

    /**
     * @var array<string, UserOption[]>
     */
    private array $options;

    #[\Override]
    public function readParameters(): void
    {
        parent::readParameters();

        $this->formObject = new User(WCF::getUser()->userID);
        $this->options = $this->getUserOptionsByCategory(
            'settings.foo',
            static fn(UserOption $option) => $option->validateOptions()
                && $option->validatePermissions()
                && $option->isEditable()
        );
    }

    #[\Override]
    protected function createForm(): void
    {
        parent::createForm();

        $this->form->appendChildren($this->getUserOptionFormContainers($this->options));
    }

    #[\Override]
    protected function getDatabaseObjectBuilder(): UserBuilder
    {
        return UserBuilder::forUpdate($this->formObject);
    }

    #[\Override]
    protected function getCommand(DatabaseObjectBuilder $builder): callable
    {
        return new UpdateUser($builder);
    }
}
```

### Builders and Commands

`UserOptionAction` and `UserOptionEditor` are deprecated in favor of `wcf\data\user\option\UserOptionBuilder` and the commands `CreateUserOption`, `UpdateUserOption` and `DeleteOption` in the `wcf\command\user\option` namespace.
`UserOptionCategoryAction` and `UserOptionCategoryEditor` are deprecated in favor of `wcf\data\user\option\category\UserOptionCategoryBuilder` and the commands `CreateUserOptionCategory` and `UpdateUserOptionCategory`, which fire the events `UserOptionCategoryCreated` and `UserOptionCategoryUpdated`.

The titles of user option categories are stored in the localized storage, use `UserOptionCategory::getTitle()` instead of the language item `wcf.user.option.category.{categoryName}`.
The descriptions of user option categories are no longer displayed.

### Filters

User options of the `profile` category tree are automatically available as filters, which replace the user search and the `com.woltlab.wcf.userOptions` conditions:

- the filters of the user list in the ACP, which also provides a hidden column for each option,
- the filters of the members list for options that are `searchable` and visible to other users than the owner,
- the object filters of ads, notices and automatic user group assignments (`wcf\system\object\filter\user\UserOptionObjectFilter`).

No additional code is required, the filter is provided by the form option of the user option.

## Grid View Column Visibility

Grid views can allow users to select the visible columns, see [Column Visibility](../../php/api/grid_views.md#column-visibility).
`AbstractGridViewPage` reads the selected columns from the `columns` parameter into the new property `$columns`, which may conflict with an existing property of the same name in a subclass.
