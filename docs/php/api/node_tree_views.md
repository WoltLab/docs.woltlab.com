# Node Tree Views

Node tree views are a generic solution for the rendering of hierarchical, tree-shaped data structures such as categories or menu items.
In addition to rendering, the node tree view also takes care of drag and drop sorting and integrates with the [Interactions](interactions.md) system.

The implementation essentially offers the following advantages:

1. A uniform appearance and usability for the user.
2. An easy way for developers to create their own node tree views.
3. An easy way for developers to extend existing node tree views with interactions.

## Usage

### AbstractNodeTreeView

A node tree view obtains its data from a `\RecursiveIteratorIterator` of `IObjectTreeNode` instances and renders them as a nested list.

Example:

```php
<?php

namespace wcf\system\nodeTreeView\admin;

use wcf\data\IObjectTreeNode;
use wcf\system\nodeTreeView\AbstractNodeTreeView;

final class ExampleNodeTreeView extends AbstractNodeTreeView
{
    public function __construct(public readonly int $menuID)
    {
        $this->setSetPositionsEndpoint("core/menus/{$this->menuID}/items/positions");
    }

    #[\Override]
    protected function createNodeIterator(): \RecursiveIteratorIterator
    {
        $nodeTree = new ExampleNodeTree($this->menuID);

        return $nodeTree->getIterator();
    }

    #[\Override]
    public function getNodeLink(IObjectTreeNode $node): string
    {
        \assert($node instanceof ExampleNode);

        return LinkHandler::getInstance()->getControllerLink(
            ExampleEditForm::class,
            ['id' => $node->getObjectID()]
        );
    }

    #[\Override]
    public function isAccessible(): bool
    {
        return WCF::getSession()->hasPermission('admin.example.canManage');
    }

    #[\Override]
    public function getParameters(): array
    {
        return ['menuID' => $this->menuID];
    }
}
```

The following methods must be implemented:

- `createNodeIterator(): \RecursiveIteratorIterator` returns the iterator that traverses the nodes of the tree.
  The traversed objects have to implement `IObjectTreeNode` and have to extend `DatabaseObject` (or decorate one via `DatabaseObjectDecorator`).
- `getNodeLink(IObjectTreeNode $node): string` returns the link to the edit form of the given node.

The following methods can be overridden:

- `isAccessible(): bool` defines whether the node tree view is accessible for the active user. Defaults to `true`.
- `getNodeIcon(IObjectTreeNode $node): string` returns the icon of the given node, or an empty string if the node has no icon.
- `getParameters(): array` returns additional parameters which uniquely identify this view instance (see [Additional Parameters](#additional-parameters)).

### AbstractNodeTreeViewPage

A node tree view can be displayed on a page by inheriting from `AbstractNodeTreeViewPage`.

Example:

```php
<?php

namespace wcf\acp\page;

use wcf\page\AbstractNodeTreeViewPage;
use wcf\system\nodeTreeView\AbstractNodeTreeView;
use wcf\system\nodeTreeView\admin\ExampleNodeTreeView;

/**
 * @extends AbstractNodeTreeViewPage<ExampleNodeTreeView>
 */
final class ExampleListPage extends AbstractNodeTreeViewPage
{
    #[\Override]
    protected function createNodeTreeView(): AbstractNodeTreeView
    {
        return new ExampleNodeTreeView();
    }
}
```

```smarty
{include file='header'}

<div class="section">
    {unsafe:$nodeTreeView->render()}
</div>

{include file='footer'}
```

## Nodes

Every node in the tree must implement `wcf\data\IObjectTreeNode`.
The interface extends `\RecursiveIterator`, `\Countable` and `IIDObject` and provides methods like `getDepth()`, `getParentNode()` and `isLastSibling()` that are required for rendering.

Typical implementations either extend an existing tree node class such as `CategoryNode` or `MenuItemNode`, or decorate a `DatabaseObject` via `DatabaseObjectDecorator` and implement the interface on top.

## Customization

### Node Icon

An icon can be assigned to each node by overriding `getNodeIcon()`.
The method receives the current node and is expected to return the HTML for the icon, or an empty string if no icon should be displayed.

```php
#[\Override]
public function getNodeIcon(IObjectTreeNode $node): string
{
    \assert($node instanceof ExampleNode);

    return $node->getDecoratedObject()->getIcon();
}
```

### Additional Parameters

A node tree view can be provided with additional parameters, e.g. to render the items of a specific menu.
Parameters returned by `getParameters()` are passed to the JavaScript component and are used to derive a unique id for the view (see `getID()`), which is required so that drag and drop and interactions can be attached to the correct DOM element.

```php
final class ExampleNodeTreeView extends AbstractNodeTreeView
{
    public function __construct(public readonly int $menuID)
    {
    }

    #[\Override]
    public function getParameters(): array
    {
        return ['menuID' => $this->menuID];
    }
}
```

```php
/**
 * @extends AbstractNodeTreeViewPage<ExampleNodeTreeView>
 */
final class ExampleListPage extends AbstractNodeTreeViewPage
{
    public int $menuID = 0;

    #[\Override]
    public function readParameters()
    {
        parent::readParameters();

        if (isset($_REQUEST['menuID'])) {
            $this->menuID = \intval($_REQUEST['menuID']);
        }
    }

    #[\Override]
    protected function createNodeTreeView(): AbstractNodeTreeView
    {
        return new ExampleNodeTreeView($this->menuID);
    }

    #[\Override]
    protected function getBaseUrlParameters(): array
    {
        return ['menuID' => $this->menuID];
    }
}
```

## Drag and Drop

Nodes can be reordered and re-parented via drag and drop.
The new positions are sent to an RPC API endpoint that is configured via `setSetPositionsEndpoint()`.

```php
$this->setSetPositionsEndpoint("core/menus/{$this->menuID}/items/positions");
```

The endpoint receives the new order of nodes including their parent ids and is responsible for persisting the change.
If no endpoint is set, drag and drop is disabled and the items are rendered in a static order.

## Interactions

Node tree views integrate with the [Interactions](interactions.md) system in two ways: an interaction provider, which is rendered as a context menu next to each node, and quick interactions, which are rendered inline.

### Interaction Provider

An interaction provider can be set via `setInteractionProvider()`.
The interactions defined by the provider are rendered as a context menu next to each node.

```php
final class ExampleNodeTreeView extends AbstractNodeTreeView
{
    public function __construct()
    {
        $provider = new ExampleInteractions();
        $provider->addInteractions([
            new Divider(),
            new EditInteraction(ExampleEditForm::class),
        ]);
        $this->setInteractionProvider($provider);
    }
}
```

### Quick Interactions

Quick interactions are rendered inline next to each node and are intended for frequently used actions such as toggling the enabled state of an item.
They are added individually via `addQuickInteraction()`.

```php
final class ExampleNodeTreeView extends AbstractNodeTreeView
{
    public function __construct()
    {
        $this->addQuickInteraction(
            new ToggleInteraction(
                'enable',
                'core/examples/%s/enable',
                'core/examples/%s/disable'
            )
        );
    }
}
```
