# Interactions

The interaction system enables users to perform context menu actions on specific `DatabaseObject` instances.
These interactions are designed to be reusable and universally available, ensuring consistency across the application wherever database objects appear – whether in lists, detail views, or other components.

Interactions are registered centrally using a provider class dedicated to each object type.
This architecture allows for modular extensibility, where plugins can contribute additional interactions by subscribing to specialized registration events.
Once registered, these plugin-defined interactions are seamlessly integrated and become available across all relevant UI contexts.

This system provides a clean, scalable way to define and extend user actions without duplicating logic or UI elements.

# Providers

Each interaction provider is dedicated to a specific type of `DatabaseObject` and allows the configuration of the interactions that are available for this type of object.

Example: 

```php
final class ExampleInteractions extends AbstractInteractionProvider
{
    public function __construct()
    {
        $this->addInteractions([
            new SoftDeleteInteraction('examples/%s/soft-delete'),
            new RestoreInteraction('examples/%s/restore'),
            new DeleteInteraction('examples/%s'),
            new Divider(),
            new EditInteraction(ExampleEditForm::class)
        ]);

        EventHandler::getInstance()->fire(
            new ExampleInteractionCollecting($this)
        );
    }

    #[\Override]
    public function getObjectClassName(): string
    {
        return Example::class;
    }
}
```

# Bulk Interactions

Bulk interaction providers are a special type of interaction provider that contain interactions that can be applied to multiple objects simultaneously.
Bulk interactions are the successor to the “Clipboard” feature and are applied to multiple items sequentually.

## `isAvailableCallback` (optional)

**Type:** `(object) => boolean`

Interactions allow the configuration of callback function that determines whether a specific interaction can be applied to the given object.
This function is responsible for:

* Verifying that the user has the necessary permissions to perform the interaction.
* Preventing invalid state transitions, for example, attempting to set an item as default when it is already the default.

If omitted, the interaction is considered to be unconditionally available.

Example: 

```php
new RpcInteraction(
    'id',
    'endpoint/path/%s',
    'label',
    isAvailableCallback: fn(DatabaseObject $object) => $object->canInteract(),
)
```

## Confirmation Types

`RpcInteraction` allows the configuration of a confirmation type, that determines whether the user must additionally confirm the interaction before it is executed.

| Name                                                | Description |
| --------------------------------------------------- | ----------- |
| `InteractionConfirmationType::None`                 | No confirmation |
| `InteractionConfirmationType::SoftDelete`           | Predetermined confirmation message asking for a soft-delete. |
| `InteractionConfirmationType::SoftDeleteWithReason` | Predetermined confirmation message asking for a soft-delete with an optional field for the reason. |
| `InteractionConfirmationType::Restore`              | Predetermined confirmation message asking for a restore. |
| `InteractionConfirmationType::Delete`               | Predetermined confirmation message asking for the permanent deletion. |
| `InteractionConfirmationType::Disable`              | Predetermined confirmation message asking to disable the item. |
| `InteractionConfirmationType::Custom`               | Allows you to specify a custom confirmation message. |

Example:

```php
new RpcInteraction(
    'id',
    'endpoint/path/%s',
    'label',
    confirmationType: InteractionConfirmationType::Custom,
    confirmationMessage: 'Custom Message'
)
```


## Interaction Effects

Interaction effects determine what should happen with the UI after an interaction has been executed.

| Name                            | Effect in List & Grid Views | Effect on Detail Page |
| ------------------------------- | --------------------------- | --------------------- |
| `InteractionEffect::ReloadItem` | Reloads the item/row.       | Reloads the context menu and optionally the content header title. |
| `InteractionEffect::ReloadList` | Reloads all items/rows.     | Same as `ReloadItem` |
| `InteractionEffect::ReloadPage` | Same as `ReloadItem`.       | Reloads the page. |
| `InteractionEffect::RemoveItem` | Removes the item/row        | Redirects to a given list page. |

Example:

```php
new RpcInteraction(
    'id',
    'endpoint/path/%s',
    'label',
    interactionEffect: InteractionEffect::ReloadList
)
```

The default interaction effect for `RpcInteraction` is `InteractionEffect::ReloadItem`.

## Available Interactions

### `DeleteInteraction`

Uses an RPC endpoint to permanently delete an object after a confirmation prompt.

Example:

```php
new DeleteInteraction("endpoint/path/%s")
```

### `DisableInteraction`

Uses an RPC endpoint to disable an object after a confirmation prompt.

Example:

```php
new DisableInteraction("endpoint/path/%s")
```

### `EditInteraction`

Shows a links to a given controller with the label `Edit`.

Example:

```php
new EditInteraction(ExampleEditForm::class)
```

### `FormBuilderDialogInteraction`

Opens a form builder dialog using the given controller link that is expected to provide a `Psr15DialogForm`.

Example:

```php
new FormBuilderDialogInteraction(
    'id',
    LinkHandler::getInstance()->getControllerLink(
        ExampleAction::class,
        ['id' => '%s']
    ),
    'label',
)
```

### `LegacyDboInteraction` (deprecated)

Generic interaction for invoking a legacy database object action.

Example:

```php
new LegacyDboInteraction(
    'id',
    'class-name',
    'action-name',
    'label',
)
```

### `LinkableObjectInteraction`

Links to the url returned by `ILinkableObject::getLink()`.

Example:

```php
new LinkableObjectInteraction(
    'id',
    'label'
)
```

### `LinkInteraction`

Links to the given controller.

Example:

```php
new LinkInteraction(
    'id',
    ExampleForm::class,
    'label'
)
```

### `RestoreInteraction`

Uses an RPC endpoint to restore an object after a confirmation prompt.

Example:

```php
new RestoreInteraction("endpoint/path/%s")
```

### `RpcInteraction`

Generic interaction for invoking a given RPC endpoint.

Example:

```php
new RpcInteraction(
    'id',
    'endpoint/path/%s',
    'label',
)
```

### `SoftDeleteInteraction`

Uses an RPC endpoint to soft-delete an object after a confirmation prompt.

Example:

```php
new SoftDeleteInteraction("endpoint/path/%s")
```

### `SoftDeleteWithReasonInteraction`

Uses an RPC endpoint to soft-delete an object after a confirmation prompt with optional input of a reason.

Example:

```php
new SoftDeleteWithReasonInteraction("endpoint/path/%s")
```

### `ToggleInteraction`

Uses an RPC endpoint to toggle the disable state of an object.

Example:

```php
new ToggleInteraction(
    'id',
    'enable/endpoint/%s',
    'disable/endpoint/%s'
)
```

### `Divider`

Divider is not an interaction itself, but can be added to the list of interactions to create a dividing line in the context menu.

Example:

```php
final class ExampleInteractions extends AbstractInteractionProvider
{
    public function __construct()
    {
        $this->addInteractions([
            new DeleteInteraction("core/example/%s")
            new Divider(),
            new EditInteraction(ExampleEditForm::class),
        ]);
    }
}
```

## Events

Each interaction provider exposes a unique event to register additional interactions.

Example: Adding an additional interaction in the user profile:

```php
EventHandler::getInstance()->register(
    \wcf\event\interaction\user\UserProfileInteractionCollecting::class,
    static function (\wcf\event\interaction\user\UserProfileInteractionCollecting $event) {
        $event->provider->addInteraction(
            new class(
                'start-conversation',
                isAvailableCallback: static fn(UserProfile $user) => WCF::getUser()->userID !== $user->userID
            ) extends \wcf\system\interaction\AbstractInteraction {
                #[\Override]
                public function render(DatabaseObject $object): string
                {
                    \assert($object instanceof UserProfile);

                    return \sprintf(
                        '<a href="%s">%s</a>',
                        StringUtil::encodeHTML(
                            LinkHandler::getInstance()->getControllerLink(
                                \wcf\form\ConversationAddForm::class,
                                ['userID' => $object->userID]
                            )
                        ),
                        WCF::getLanguage()->get('wcf.conversation.button.add')
                    );
                }
            }
        );
    }
);
```

## Integrating Interactions

Interactions can be integrated as a standalone button on detail pages or edit forms.
In addition, they are intended for use in grid and list views.
For the usage in GridView and ListView see their respective documentation.

### Standalone Button

An integration on detail pages can be achieved using the `StandaloneInteractionContextMenuComponent` component.
The component supports templates for rendering as a content interaction button and as a content header button.

Example:

```php
class ExamplePage extends AbstractPage
{
    private DatabaseObject $exampleObject;
    
    ...

    #[\Override]
    public function assignVariables()
    {
        parent::assignVariables();

        WCF::getTPL()->assign([
            'interactionContextMenu' => StandaloneInteractionContextMenuComponent::forContentInteractionButton(
                provider: new ExampleInteractions(),
                object: $this->exampleObject,
                redirectUrl: LinkHandler::getInstance()->getControllerLink(ExampleListPage::class),
                label: 'example label',
                reloadHeaderEndpoint: "example/{$this->exampleObject->getObjectID()}/content-header-title"
            ),
        ]);
    }
}
```

```smarty
{capture assign='contentInteractionButtons'}
	{unsafe:$interactionContextMenu->render()}
{/capture}

{include file='header'}

{* … *}

{include file='footer'}
```
