# Cookbook

Recipes that compose operations to solve common tasks. Each one is a complete request
body you can send to the endpoint. If you're new to the operation language, read
[Operations](ops/index.md) first. Each recipe links to the operations it uses.

## The one rule to remember

A parameter that's an **array is evaluated as an operation**; anything else is taken
**literally**. So a scalar passes straight through, but to pass an array or object as
data you have to wrap it in [`value`](ops/value.md), or it's read as an operation and
rejected.

```json
["run", "getUser", 1]
```

```json
["run", "createUser", ["value", {"email": "ada@example.com", "roles": ["admin"]}]]
```

The first passes the literal `1`. The second wraps the object so it's passed as data
instead of being read as an operation.

## Reading and reshaping data

### Pick one field from a result

Run an action and pull a single field out of its result with [`get`](ops/get.md).

```json
["get", "email", ["run", "getUser", 1]]
```

```json
"ada@example.com"
```

### Reach into nested data

`get` keys support dot paths, so you can reach deep into a result in one step.

```json
["get", "address.city", ["run", "getUser", 1]]
```

```json
"Berlin"
```

### Provide a fallback when a field is missing

A third parameter to `get` is the default, returned when the key is absent (it'd
otherwise be `null`).

```json
["get", "nickname", ["run", "getUser", 1], ["value", "Anonymous"]]
```

### Build a custom response object

[`object`](ops/object.md) evaluates each value, so you can assemble the shape you
want, pulling from several actions in a single request.

```json
["object", {
  "user":  ["run", "getUser", 1],
  "posts": ["run", "getPosts", 1]
}]
```

```json
{
  "user":  { "id": 1, "username": "ada" },
  "posts": [ { "id": 10, "title": "Hello" } ]
}
```

## Lists and collections

### Turn a list into a lookup map

[`map`](ops/map.md) takes a key field and a value field and builds a `{key: value}`
object from a list.

```json
["map", "id", "username", ["run", "getUsers", ["value", {"role": "member"}]]]
```

```json
{ "1": "ada", "2": "linus" }
```

### Take the top N

Sort a result, then take from the front. [`sort`](ops/sort.md) with a field and
direction, then [`take`](ops/take.md) for how many.

```json
["take", 3, ["sort", "score", "desc", ["run", "getPlayers", "season-2024"]]]
```

### Take the last N

A negative count for `take` counts from the end.

```json
["take", -5, ["run", "getEvents", "today"]]
```

### Sort a plain array

`sort` with just a direction sorts a flat array. The array literal is wrapped in
`value`.

```json
["sort", "asc", ["value", [5, 2, 8, 1]]]
```

```json
[1, 2, 5, 8]
```

### Apply defaults to a result

[`merge`](ops/merge.md) shallow-merges arrays left to right, so later values win. Put
defaults first and the real result second to fill in only what's missing.

```json
["merge",
  ["value", {"role": "guest", "active": true}],
  ["run", "getUser", 1]
]
```

## Logic and conditionals

### Branch on a comparison

[`ifElse`](ops/ifelse.md) picks one of two expressions based on a check. Here the
check is a [`compare`](ops/compare.md). All three branches have to be expressions, so
the literals are wrapped in `value`.

```json
["ifElse",
  ["compare", ["get", "age", ["run", "getUser", 1]], ">=", ["value", 18]],
  ["value", "adult"],
  ["value", "minor"]
]
```

### Combine several checks

[`and`](ops/and.md) (and [`or`](ops/or.md)) evaluate to a boolean and short-circuit.

```json
["and", ["run", "isActive", 1], ["run", "isAdmin", 1]]
```

```json
true
```

### First value that isn't null

[`coalesce`](ops/coalesce.md) returns the first non-null result and stops. Unlike a
`get` default, the alternatives can be whole actions, which is handy for a cache
fallback.

```json
["coalesce",
  ["run", "getFromCache", "user:1"],
  ["run", "getUser", 1]
]
```

## Multi-step requests

These use [`sequence`](ops/sequence.md) (evaluate each item, return the last) and
[`var`](ops/var.md) (store and recall a value) to do several things in one request.

### Fetch once, use many times

Calling an action twice does the work twice. Store it in a variable instead and read
it back as often as you like.

```json
["sequence",
  ["var", "user", ["run", "getUser", 1]],
  ["object", {
    "name":  ["get", "username", ["var", "user"]],
    "email": ["get", "email", ["var", "user"]]
  }]
]
```

### Create something, then use its id

The result of a create flows into the next step through a variable.

```json
["sequence",
  ["var", "user", ["run", "createUser", ["value", {"email": "ada@example.com"}]]],
  ["run", "sendWelcome", ["get", "user_id", ["var", "user"]]]
]
```

### Read a nested value from a stored variable

`var` reads support the same dot paths as `get`.

```json
["sequence",
  ["var", "user", ["run", "getUser", 1]],
  ["var", "user.address.city"]
]
```

## Converting types

[`cast`](ops/cast.md) converts a value to `int`, `float`, `bool`, or `string`, which
is useful when an action returns a number as a string.

```json
["cast", "int", ["get", "count", ["run", "getStats", "today"]]]
```

## Paginating

[`list`](ops/list.md) runs a list action and returns `{ "total", "result" }`. Its
data is wrapped in `value` and has to include `properties`.

```json
["list", "users", ["value", {
  "properties": ["id", "username"],
  "filters":    {"role": "admin"},
  "page":       0,
  "pageSize":   20
}]]
```

```json
{
  "total": 42,
  "result": [
    { "id": 1, "username": "ada" },
    { "id": 2, "username": "linus" }
  ]
}
```

## Putting it together

A single request can do all of the above at once. This fetches a user, then builds a
dashboard object (their name, their three most-liked posts, and whether they're an
admin), reusing the stored user along the way.

```json
["sequence",
  ["var", "user", ["run", "getUser", 1]],
  ["object", {
    "name":     ["get", "username", ["var", "user"]],
    "topPosts": ["take", 3, ["sort", "likes", "desc", ["run", "getPosts", 1]]],
    "isAdmin":  ["compare", ["get", "role", ["var", "user"]], "=", ["value", "admin"]]
  }]
]
```
