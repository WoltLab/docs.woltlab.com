<?php

namespace wcf\system\endpoint\controller\core\persons;

use Laminas\Diactoros\Response\JsonResponse;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use wcf\data\person\Person;
use wcf\data\person\PersonAction;
use wcf\http\Helper;
use wcf\system\endpoint\DeleteRequest;
use wcf\system\endpoint\IController;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\WCF;

/**
 * Deletes the person with the given ID.
 *
 * @author      Marcel Werk
 * @copyright   2001-2025 WoltLab GmbH
 * @license     GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 */
#[DeleteRequest('/core/persons/{id:\d+}')]
final class DeletePerson implements IController
{
    #[\Override]
    public function __invoke(ServerRequestInterface $request, array $variables): ResponseInterface
    {
        if (!WCF::getSession()->getPermission('admin.content.canManagePeople')) {
            throw new PermissionDeniedException();
        }

        $person = Helper::fetchObjectFromRequestParameter($variables['id'], Person::class);
        $action = new PersonAction([$person], 'delete');
        $action->executeAction();

        return new JsonResponse([]);
    }
}
