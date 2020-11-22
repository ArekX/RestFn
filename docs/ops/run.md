# Run Op

This operation runs an action and returns a value.

Action is a REST action which does some work and returns an associative array of values.

All created actions need to implement `Action` interface

Definition: `["run", <name: expression(string), string>, <data: expression(any), string, int, float, null, bool>]`

* __&lt;name: expression(string), string&gt;__ - Name of the action to be ran. 
This action must be defined in the parser in order to be ran. If action does not exist an error will be thrown.

* __&lt;data: expression(any), string, int, float, null, bool&gt;__ - Data to be passed to this action. If the
value is not an expression array it will be passed directly, otherwise it will be evaluated. If you need to pass
an array or an object directly use [value](value.md) operation.

## Usage


Request:

This operation runs `getUser` with passed param `1` to be used to get an example user with ID 1.

```json
["run", "getUser", 1]
```


Response:

This is an example response of a `getUser` user implementation.

```json
{
   "user_id": 1,
   "username": "test",
   "email": "test@email.com"
}
```


## Typical Usage

You would use this operation to execute an action which has your implementation of the code. Actions are similar to the
endpoints in standard REST server except in RestFn multiple actions can be run in one request.