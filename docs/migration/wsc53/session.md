# Migrating from WSC 5.3 - Session Handling and Authentication

WoltLab Suite 5.4 includes a completely refactored session handling.
As long as you only interact with sessions via `WCF::getSession()`, especially when you perform read-only accesses, you should not notice any breaking changes.

You might appreciate some of the new session methods if you process security sensitive data.

## Summary and Concepts

Most of the changes revolve around the removal of the legacy persistent login functionality and the assumption that every user has a single session only.
Both aspects are related to each other.

### Legacy Persistent Login

The legacy persistent login was rather an automated login.
Upon bootstrapping a session, it was checked whether the user had a cookie pair storing the user’s `userID` and (a single BCrypt hash of) the user’s password.
If such a cookie pair exists and the BCrypt hash within the cookie matches the user’s password hash when hashed again, the session would immediately `changeUser()` to the respective user.

This legacy persistent login was completely removed.
Instead, any sessions that belong to an authenticated user will automatically be long-lived.
These long-lived sessions expire no sooner than 14 days after the last activity, ensuring that the user continously stays logged in, provided that they visit the page at least once per fortnight.

### Multiple Sessions

To allow for a proper separation of these long-lived user sessions, WoltLab Suite now allows for multiple sessions per user.
These sessions are completely unrelated to each other.
Specifically, they do not share session variables and they expire independently.

As the existing `wcf1_session` table is also used for the online lists and location tracking, it will be maintained on a best effort basis.
It no longer stores any private session data.

The actual sessions storing security sensitive information are in an unrelated location.
They must only be accessed via the PHP API exposed by the `SessionHandler`.

### Merged ACP and Frontend Sessions

WoltLab Suite 5.4 shares a single session across both the frontend, as well as the ACP.
When a user logs in to the frontend, they will also be logged into the ACP and vice versa.

Actual access to the ACP is controlled via the new [reauthentication mechanism](#reauthentication).

The session variable store is scoped:
Session variables set within the frontend are not available within the ACP and vice versa.

### Improved Authentication and Reauthentication

WoltLab Suite 5.4 ships with multi-factor authentication support and a generic re-authentication implementation that can be used to verify the account owner’s presence.

## Additions and Changes

### Password Hashing

WoltLab Suite 5.4 includes a new object-oriented password hashing framework that is modeled after PHP’s `password_*` API.
Check [`PasswordAlgorithmManager`](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/user/authentication/password/PasswordAlgorithmManager.class.php) and [`IPasswordAlgorithm`](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/user/authentication/password/IPasswordAlgorithm.class.php) for details.

The new default password hash is a standard BCrypt hash.
All newly generated hashes in `wcf1_user.password` will now include a type prefix, instead of just passwords imported from other systems.

### Session Storage

The `wcf1_session` table will no longer be used for session storage.
Instead, it is maintained for compatibility with existing online lists.

The actual session storage is considered an implementation detail and you *must not* directly interact with the session tables.
Future versions might support alternative session backends, such as Redis.

!!! warning "Do not interact directly with the session database tables but only via the `SessionHandler` class!"

### Reauthentication

For security sensitive processing, you might want to ensure that the account owner is actually present instead of a third party accessing a session that was accidentally left logged in.

WoltLab Suite 5.4 ships with a generic reauthentication framework.
To request reauthentication within your controller you need to:

1. Use the `wcf\system\user\authentication\TReauthenticationCheck` trait.
2. Call:
   ```php
   $this->requestReauthentication(LinkHandler::getInstance()->getControllerLink(static::class, [
   	/* additional parameters */
   ]));
   ```

`requestReauthentication()` will check if the user has recently authenticated themselves.
If they did, the request proceeds as usual.
Otherwise, they will be asked to reauthenticate themselves.
After the successful authentication, they will be redirected to the URL that was passed as the first parameter (the current controller within the example).

Details can be found in [WoltLab/WCF#3775](https://github.com/WoltLab/WCF/pull/3775).

### Multi-factor Authentication

To implement multi-factor authentication securely, WoltLab Suite 5.4 implements the concept of a “pending user change”.
The user will not be logged in (i.e. `WCF::getUser()->userID` returns `null`) until they authenticate themselves with their second factor.

Requesting multi-factor authentication is done on an opt-in basis for compatibility reasons.
If you perform authentication yourself and do not trust the authentication source to perform multi-factor authentication itself, you will need to adjust your logic to request multi-factor authentication from WoltLab Suite:

Previously:

```php
WCF::getSession()->changeUser($targetUser);
```

Now:

```php
$isPending = WCF::getSession()->changeUserAfterMultifactorAuthentication($targetUser);
if ($isPending) {
	// Redirect to the authentication form. The user will not be logged in.
	// Note: Do not use `getControllerLink` to support both the frontend as well as the ACP.
	HeaderUtil::redirect(LinkHandler::getInstance()->getLink('MultifactorAuthentication', [
		'url' => /* Return To */,
	]));
	exit;
}
// Proceed as usual. The user will be logged in.
```

#### Adding Multi-factor Methods

Adding your own multi-factor method requires the implementation of a single object type:

{jinja{ codebox(
   language="xml",
   title="objectType.xml",
   contents="""
<type>
   <name>com.example.multifactor.foobar</name>
   <definitionname>com.woltlab.wcf.multifactor</definitionname>
   <icon><!-- Font Awesome 4 Icon Name goes here. --></icon>
   <priority><!-- Determines the sort order, higher priority will be preferred for authentication. --></priority>
   <classname>wcf\system\\user\multifactor\FoobarMultifactorMethod</classname>
</type>
"""
)}}

The given classname must implement the [`IMultifactorMethod`](https://github.com/WoltLab/WCF/blob/master/wcfsetup/install/files/lib/system/user/multifactor/IMultifactorMethod.class.php) interface.

As a self-contained example, you can find the initial implementation of the email multi-factor method in [WoltLab/WCF#3729](https://github.com/WoltLab/WCF/pull/3729).
Please check [the version history](https://github.com/WoltLab/WCF/commits/master/wcfsetup/install/files/lib/system/user/multifactor/EmailMultifactorMethod.class.php) of the PHP class to make sure you do not miss important changes that were added later.

!!! warning "Multi-factor authentication is security sensitive. Make sure to carefully read the remarks in `IMultifactorMethod` for possible issues. Also make sure to carefully test your implementation against all sorts of incorrect input and consider attack vectors such as race conditions. It is strongly recommended to generously check the current state by leveraging assertions and exceptions."

#### Enforcing Multi-factor Authentication

To enforce Multi-factor Authentication within your controller you need to:

1. Use the `wcf\system\user\multifactor\TMultifactorRequirementEnforcer` trait.
2. Call: `$this->enforceMultifactorAuthentication();`

`enforceMultifactorAuthentication()` will check if the user is in a group that requires multi-factor authentication, but does not yet have multi-factor authentication enabled.
If they did, the request proceeds as usual.
Otherwise, a `NamedUserException` is thrown.


## Deprecations and Removals

### SessionHandler

Most of the changes with regard to the new session handling happened in `SessionHandler`.
Most notably, `SessionHandler` now is marked `final` to ensure proper encapsulation of data.

A number of methods in `SessionHandler` are now deprecated and result in a noop.
This change mostly affects methods that have been used to bootstrap the session, such as `setHasValidCookie()`.

Additionally, accessing the following keys on the session is deprecated.
They directly map to an existing method in another class and any uses can easily be updated:
- `ipAddress`
- `userAgent`
- `requestURI`
- `requestMethod`
- `lastActivityTime`

Refer to [the implementation](https://github.com/WoltLab/WCF/blob/439de4963c947c3569a0c584f795245f693155b0/wcfsetup/install/files/lib/system/session/SessionHandler.class.php#L168-L178) for details.

### ACP Sessions

The database tables related to ACP sessions have been removed.
The PHP classes have been preserved due to being used within the class hierarchy of the legacy sessions.

### Cookies

The `_userID`, `_password`, `_cookieHash` and `_cookieHash_acp` cookies will no longer be created nor consumed.

### Virtual Sessions

The virtual session logic existed to support multiple devices per single session in `wcf1_session`.
Virtual sessions are no longer required with the refactored session handling.

Anything related to virtual sessions has been completely removed as they are considered an implementation detail.
This removal includes PHP classes and database tables.

### Security Token Constants

The security token constants are deprecated.
Instead, the methods of `SessionHandler` should be used (e.g. `->getSecurityToken()`).
Within templates, you should migrate to the `{csrfToken}` tag in place of `{@SECURITY_TOKEN_INPUT_TAG}`.
The `{csrfToken}` tag is a drop-in replacement and was backported to WoltLab Suite 5.2+, allowing you to maintain compatibility across a broad range of versions.

### PasswordUtil and Double BCrypt Hashes

Most of the methods in PasswordUtil are deprecated in favor of the new password hashing framework.
