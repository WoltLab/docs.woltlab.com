
# Show Order

Based on the generic show order implementation, users can be given the option of reordering entries using drag & drop.

## Backend Implementation

The backend must provide two RPC endpoints.

The first endpoint returns the list of entries according to the current show order:

```php
<?php

namespace wcf\system\endpoint\controller\foo;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use wcf\system\endpoint\GetRequest;
use wcf\system\endpoint\IController;
use wcf\system\exception\IllegalLinkException;
use wcf\system\showOrder\ShowOrderHandler;
use wcf\system\showOrder\ShowOrderItem;
use wcf\system\WCF;

#[GetRequest('/your-endpoint/show-order')]
final class GetShowOrder implements IController
{
    public function __invoke(ServerRequestInterface $request, array $variables): ResponseInterface
    {
        WCF::getSession()->checkPermissions(["admin.foo.canManageFoo"]);
        
        $list = new FooList();
        $list->sqlOrderBy = 'showOrder ASC';
        $list->readObjects();

        $items = \array_map(
            static fn($object) => new ShowOrderItem(
                $object->getObjectID(),
                $object->getName()
            ),
            $list->getObjects()
        );

        return (new ShowOrderHandler($items))->toJsonResponse();
    }
}
```

The second endpoint accepts the new show order and saves it.

```php
<?php

namespace wcf\system\endpoint\controller\foo;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use wcf\system\endpoint\IController;
use wcf\system\endpoint\PostRequest;
use wcf\system\exception\IllegalLinkException;
use wcf\system\showOrder\ShowOrderHandler;
use wcf\system\showOrder\ShowOrderItem;
use wcf\system\WCF;

#[PostRequest('/your-endpoint/show-order')]
final class ChangeShowOrder implements IController
{
    public function __invoke(ServerRequestInterface $request, array $variables): ResponseInterface
    {
        WCF::getSession()->checkPermissions(["admin.foo.canManageFoo"]);

        $list = new FooList();
        $list->sqlOrderBy = 'showOrder ASC';
        $list->readObjects();

        $items = \array_map(
            static fn($object) => new ShowOrderItem(
                $object->getObjectID(),
                $object->getName()
            ),
            $list->getObjects()
        );

        $sortedItems = (new ShowOrderHandler($items))->getSortedItemsFromRequest($request);
        $this->saveShowOrder($sortedItems);

        return new JsonResponse([]);
    }

    /**
     * @param list<ShowOrderItem> $items
     */
    private function saveShowOrder(array $items): void
    {
        WCF::getDB()->beginTransaction();
        $sql = "UPDATE  wcf1_foo
                SET     showOrder = ?
                WHERE   id = ?";
        $statement = WCF::getDB()->prepare($sql);
        for ($i = 0, $length = \count($items); $i < $length; $i++) {
            $statement->execute([
                $i + 1,
                $items[$i]->id,
            ]);
        }
        WCF::getDB()->commitTransaction();
    }
}
```

## Frontend Implementation

```smarty
<button type="button" class="button jsChangeShowOrder">{icon name='up-down'} <span>{lang}wcf.global.changeShowOrder{/lang}</span></button>

<script data-relocate="true">
	require(["WoltLabSuite/Core/Component/ChangeShowOrder"], ({ setup }) => {
		{jsphrase name='wcf.global.changeShowOrder'}

		setup(
			document.querySelector('.jsChangeShowOrder'),
			'your-endpoint/show-order',
		);
	});
</script>
```
