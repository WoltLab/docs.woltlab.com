# Guest Token

Guest token is a generic implementation to handle the authorization of a guest (entering a user name and filling out a captcha) that can be used in various places.

The token must be transmitted to the backend and validated there. A corresponding API is available on the PHP backend.

## Client-side Example

```ts
import { prepareRequest } from "WoltLabSuite/Core/Ajax/Backend";
import User from "WoltLabSuite/Core/User";
import { getGuestToken } from "WoltLabSuite/Core/Component/GuestTokenDialog";

let token: string | undefined = "";
if (!User.userId) {
  token = await getGuestToken();
}

await prepareRequest("your_backend_url").post({
  token,
}).fetchAsJson();
```

## Backend Example

```php
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use wcf\http\Helper;
use wcf\system\endpoint\IController;
use wcf\system\endpoint\PostRequest;
use wcf\system\exception\UserInputException;
use wcf\system\WCF;
use wcf\util\UserUtil;

#[PostRequest('/core/foo')]
final class CreateFoo implements IController
{
    public function __invoke(ServerRequestInterface $request, array $variables): ResponseInterface
    {
        $parameters = Helper::mapApiParameters($request, CreateFooParameters::class);
        
        $username = '';
        if (!WCF::getUser()->userID) {
            $username = UserUtil::verifyGuestToken($parameters->guestToken);
            if ($username === null) {
                throw new UserInputException('guestToken');
            }
        }

        // ...
    }
}

/** @internal */
final class CreateFooParameters
{
    public function __construct(
        public readonly string $guestToken,
    ) {
    }
}
```
