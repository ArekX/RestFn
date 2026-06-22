# Authentication

RestFn comes with token-based authentication built in. A client sends a bearer
token, the framework verifies it and turns it into an identity, and actions that
require authentication are only run when an identity is present.

By default it uses JWT, but every part is swappable.

## How it works

Authentication is one middleware and a few services working together:

1. The **authentication middleware** reads the `Authorization: Bearer <token>`
   header from the request.
2. It passes the raw token to the **token parser**, which verifies it and returns
   its payload. The default is a JWT parser.
3. It passes the payload to the **authenticator**, which turns it into an
   **identity**. The default reads the identity straight from the token claims.
4. The identity is stored on the **identity service** for the rest of the request.
5. When an operation runs an action that is marked as needing authentication, it
   checks the identity service. If there is no identity, the request is rejected.

The middleware does not reject requests without a token. A request with no token
just stays unauthenticated, and public actions still run. Authentication is
enforced per action, not on the whole request.

## Setting it up

The authentication middleware is part of the default stack `createDefault()`
wires up, so all you need to do is configure the JWT secret:

```php
WebApp::createDefault([
    'config' => [
        'global' => [
            'auth' => ['jwt' => ['secret' => getenv('JWT_SECRET')]],
            // ...actions
        ],
    ],
])->run();
```

If you set `runner.middleware` yourself you replace the default stack, so include
`ErrorMiddleware` and `AuthenticationMiddleware` in your list to keep them.

## Protecting an action

Mark an action as requiring authentication by implementing
`AuthenticatedActionInterface`. It is a marker — there are no methods to add:

```php
use ArekX\RestFn\Parser\Contracts\ActionInterface;
use ArekX\RestFn\Services\Auth\Contracts\AuthenticatedActionInterface;

class GetProfileAction implements ActionInterface, AuthenticatedActionInterface
{
    public function run(mixed $data): array
    {
        return ['email' => 'me@example.com'];
    }
}
```

When the `run` (or `list`) operation is about to run this action and no identity
is present, it throws an `AuthenticationRequiredException`. An action without the
marker runs whether or not the request is authenticated.

## Reading the identity

Your action usually needs to know who is calling. Inject the
`IdentityServiceInterface` and read the current identity:

```php
use ArekX\RestFn\Services\Auth\Contracts\IdentityServiceInterface;

class GetProfileAction implements ActionInterface, AuthenticatedActionInterface
{
    public function __construct(
        public IdentityServiceInterface $identity,
    ) {}

    public function run(mixed $data): array
    {
        $userId = $this->identity->getIdentity()->getId();
        // ...load and return the profile for $userId
    }
}
```

The identity service is shared, so the same instance the middleware writes to is
the one your action reads from.

## Configuration

All settings are read from configuration. Put them under `config.global` so every
auth service sees them:

| Key | Default | What it does |
|-----|---------|--------------|
| `auth.jwt.secret` | `''` | Secret used to verify the JWT signature. Required. |
| `auth.jwt.algorithm` | `HS256` | Algorithm the token must be signed with. |
| `auth.header` | `Authorization` | Header the token is read from. |
| `auth.scheme` | `Bearer` | Scheme prefix before the token. |
| `auth.identity.idClaim` | `sub` | Claim used as the identity id. |
| `auth.identity.claims` | `[]` | Extra claims copied into the identity data. |

The JWT secret must be long enough for the algorithm (at least 32 bytes for
HS256).

```php
'global' => [
    'auth' => [
        'jwt' => [
            'secret'    => getenv('JWT_SECRET'),
            'algorithm' => 'HS256',
        ],
        'identity' => [
            'idClaim' => 'sub',
            'claims'  => ['email', 'role'],
        ],
    ],
],
```

With the `claims` above, the identity carries `email` and `role`, which you read
with `$identity->get('email')`.

## The default authenticator

The default `ClaimsAuthenticator` builds the identity from the token claims: it
reads the id from `auth.identity.idClaim` and copies the claims listed in
`auth.identity.claims` into the identity data. This is enough when everything you
need is already in the token.

If you need to load a user from your database, write your own authenticator. It
implements `AuthenticatorInterface` and returns your own identity:

```php
use ArekX\RestFn\Services\Auth\Contracts\AuthenticatorInterface;
use ArekX\RestFn\Services\Auth\Contracts\IdentityInterface;

class UserAuthenticator implements AuthenticatorInterface
{
    public function __construct(public UserRepository $users) {}

    public function authenticate(mixed $payload): ?IdentityInterface
    {
        return $this->users->find($payload['sub']); // your IdentityInterface
    }
}
```

Bind it when you create the app:

```php
'aliases' => [
    AuthenticatorInterface::class => App\UserAuthenticator::class,
],
```

## Swapping the token format

The token parser is also swappable. To use something other than JWT, bind your own
`TokenParserInterface`. It takes the raw token string and returns a verified
payload, or throws `InvalidTokenException` if the token is bad.
