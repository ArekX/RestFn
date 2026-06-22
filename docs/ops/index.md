# Operations

Operations are the building blocks of a request. A request is a tree of
operations, and the server evaluates it to produce a result. You can combine as
many operations as you need in one request.

## How an operation looks

An operation is a JSON array. The first element is the operation name, and the
rest are its parameters:

```json
["get", "email", ["run", "getUser", 1]]
```

Parameters can be other operations, which is how operations nest. The tree above
reads inside out: `run` calls an action, and `get` pulls a field out of its
result.

## Literals and expressions

Many parameters accept either a literal value or an expression (another
operation). The rule is simple: if a parameter is an array it is treated as an
expression and evaluated, otherwise it is used as is.

This means a plain string, number or boolean is taken literally, but to pass an
array or object literally - without it being read as an operation - you wrap it in
the [value](value.md) operation:

```json
["run", "createUser", ["value", {"email": "user@example.com"}]]
```

## Validation

Before a request is evaluated it is validated. Validation walks the whole tree and
checks that every operation has the parameters it expects. If something is wrong,
the request is rejected with a nested error that points at the part of the tree
that failed, so a malformed request never runs.

## Allowed operations

All built-in operations are available by default, so you can use the full
language without registering anything. If you want to restrict what a client can
use, set the `ops` config value to a map of operation name to class. That map
becomes the allow list, and only those operations can be used.

For worked examples that combine these operations to solve common tasks, see the
[Cookbook](../cookbook.md).

## Supported operations

You can find all the supported operations below:


| Operation               | Definition                                                                                                                                                                                                                                                                 |
| ----------------------- | -------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| [Value](value.md)       | `["value", <literal: any>]` <br><br>  Returns a `literal` value directly as a result.                                                                                                                                                                                      |
| [Take](take.md)         | `["take", <number: int, expression(int)>, <result: expression(array)>]` <br><br> Takes a number of items from an resulting expression array and returns them.                                                                                                              |
| [And](and.md)           | `["and", <check1: expression(boolean)>, ...<checkN: expression(boolean)>]` <br><br> Runs AND operation returning true if all `check` values are true.                                                                                                                      |
| [Or](or.md)             | `["or", <check1: expression(boolean)>, ...<checkN: expression(boolean)>]` <br><br> Runs OR operation returning `true` on the first truthy `check`, or `false` if none.                                                                                                     |
| [Not](not.md)           | `["not", <check: expression(boolean)>]` <br><br> Runs NOT operation on result of `check`.                                                                                                                                                                                  |
| [Get](get.md)           | `["get", <key: string, expression(string)>, <result: expression(array)> [, <default: expression(any)>]]` <br><br> Gets a value specified by `key` from a `result`.                                                                                                         |
| [Compare](compare.md)   | `["compare",  <valueA: expression(any)>, <operation: string, expression(string)>, <valueB: expression(any)>]` <br><br>  Compares results of `valueA` and `valueB` by using `operation`                                                                                     |
| [If Else](ifelse.md)    | `["ifElse", <check: expression(boolean)>, <trueResult: expression(any)>, <falseResult: expression(any)>]` <br><br>  Evaluates `check` and returns `trueResult` if true or `falseResult` if false.                                                                          |
| [Map](map.md)           | `["map", <key: string, expression(string)>, <value: string, expression(string)>, <result: expression(array)>]` <br><br>  Maps `result` to key-value list where key is specified by `key` and value is specified by `value`                                                 |
| [Object](object.md)     | `["object", <object: array[key -> expression(any)]>]` <br><br>  Evaluates all expressions in an object and returns the result with same populated keys.                                                                                                                    |
| [Coalesce](coalesce.md) | `["coalesce", <result1: expression(any)>, ...<resultN: expression(any)>]` <br><br>  Evaluates results sequentially one by one and stops and returns on first non-null result.                                                                                              |
| [Merge](merge.md)       | `["merge", <result1: expression(array)>, ...<resultN: expression(array)>]` <br><br>  Merges resulting arrays in `result1, ..., resultN` into one array.                                                                                                                    |
| [Sort](sort.md)         | `["sort", <by: expression(string, int), string, int>, <direction: expression(string), string>, <from: expression(array)>]` or <br> `["sort", <direction: expression(string), string>, <from: expression(array)>]` <br><br>  Sorts result in ascending or descending order. |
| [Sequence](sequence.md) | `["sequence", <item1: expression(any)>, ...<itemN: expression(any)>]` <br><br>  Evaluates all items in a sequence and returns the result of the last item.                                                                                                                 |
| [Var](var.md)           | `["var", <name: expression(string), string> [, <value: expression(any)>]]` <br><br>  Gets or sets a variable to be used during a request.                                                                                                                                  |
| [Cast](cast.md)         | `["cast", <into: expression(string), string>, <from: expression(any)>]` <br><br>  Converts one value into another.                                                                                                                                                         |
| [Run](run.md)           | `["run", <name: expression(string), string>, <data: expression(any), string, int, float, null, bool>]` <br><br> Runs one action and returns it's result.                                                                                                                   |
| [List](list.md)         | `["list", <name: expression(string), string>, <data: expression(array)>]`<br><br> Runs list action returning paginated data.                                                                                                                                               |
