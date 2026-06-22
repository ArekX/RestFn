# Actions

An action is your code. It's the part of a request that talks to your database,
calls another service, or does any real work. Operations like `value`, `get` and
`map` shape data, but they can't do anything on their own. Actions are how the
client reaches your logic.

Actions are like endpoints in a normal REST server, except that several of them can
run in a single request.

## Defining an action

An action implements `ActionInterface`. It has one method, `run`, which receives the
data the client passed and returns an array:

```php
use ArekX\RestFn\Parser\Contracts\ActionInterface;

class GetUserAction implements ActionInterface
{
    public function run(mixed $data): array
    {
        // $data is whatever the client passed to the run operation.
        return [
            'id'    => $data,
            'email' => 'user@example.com',
        ];
    }
}
```

The result has to be an array. That array is what the client gets back, or what the
surrounding operations work on.

## Registering an action

Actions are called by name. You register the names the `run` operation can use under
`actions`:

```php
'global' => ['actions' => [
    'getUser'    => App\Actions\GetUserAction::class,
    'createUser' => App\Actions\CreateUserAction::class,
]],
```

Only registered actions can run. If a client asks for an action that isn't
registered, the request fails.

## Calling an action

The client calls an action with the `run` operation, passing the action name and the
data:

```json
["run", "getUser", 1]
```

The second parameter is the data passed to `run($data)`. If it's an operation it
gets evaluated first, otherwise it's passed through as is. To pass an array or object
directly, so it isn't treated as an operation, wrap it in `value`:

```json
["run", "createUser", ["value", {"email": "user@example.com"}]]
```

## Dependencies

Actions are created through the container, so they can declare their dependencies in
the constructor and the container will inject them:

```php
class GetUserAction implements ActionInterface
{
    public function __construct(
        protected UserRepository $users,
    ) {}

    public function run(mixed $data): array
    {
        return $this->users->find($data)->toArray();
    }
}
```

## Requiring authentication

To require an authenticated identity for an action, also implement
`AuthenticatedActionInterface`. It's a marker with no methods:

```php
use ArekX\RestFn\Services\Auth\Contracts\AuthenticatedActionInterface;

class GetProfileAction implements ActionInterface, AuthenticatedActionInterface
{
    public function run(mixed $data): array { /* ... */ }
}
```

If this action runs without an identity, the request is rejected. See
[Authentication](authentication.md).

## List actions

When you need paginated lists, use a list action instead. It implements
`ListActionInterface` and is called by the `list` operation. Where a normal action
receives raw data, a list action receives a `ListRequest` and returns a `ListResult`:

```php
use ArekX\RestFn\Parser\Contracts\ListActionInterface;
use ArekX\RestFn\Parser\Data\ListRequest;
use ArekX\RestFn\Parser\Data\ListResult;

class ListUsersAction implements ListActionInterface
{
    public function run(ListRequest $request): ListResult
    {
        $page       = $request->getPage();
        $pageSize   = $request->getPageSize();
        $filters    = $request->getFilters();
        $properties = $request->getProperties();

        // ...run your query and count the total
        return new ListResult($total, $rows);
    }
}
```

`ListRequest` gives you the page, page size, filters, and the properties the client
asked for. `ListResult` carries the total number of items and the rows for the
current page.

Register list actions under `listActions`:

```php
'global' => ['listActions' => [
    'users' => App\Actions\ListUsersAction::class,
]],
```

And the client calls it with the `list` operation:

```json
["list", "users", ["value", {"page": 0, "pageSize": 20, "properties": ["id", "email"]}]]
```

The response is the total and the page of results:

```json
{"total": 134, "result": [{"id": 1, "email": "user@example.com"}]}
```

Like normal actions, a list action can also require authentication by implementing
`AuthenticatedActionInterface`.
