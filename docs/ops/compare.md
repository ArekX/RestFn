# Compare Op

This op represents comparison operation between two expressions.

This operation returns true if the results of expressions match the specified operation or false if not.

Definition: `["compare",  <valueA: expression(any)>, <operation: string>, <valueB: expression(any)>]`

* __&lt;valueA: expression(any)&gt;__ - This expression will be evaluated, and it's result will be compared to the result of `valueB`
by using on of the operations specified by operation.

* __&lt;operation: string&gt;__ - Operation to be used for comparison. Available operations: `=`, `!=`, `<`, `>`, `<=`, `>=`

* __&lt;valueB: expression(any)&gt;__ - This expression will be evaluated, and it's result will be compared to the result of `valueA`
by using on of the operations specified by operation.

## Usage


Request:
```json
["compare", ["value", 55], ">", ["value", 22]]
```


Response:
```json
true
```


## Typical Usage

This operation is used to make a determination based on the expressions, so it's used directly or within some other
operations.

Example Request:

Server gets age of user by ID 1 and compares if it's age is above 22
```json
["compare", ["get", "age", ["run", "getUser", 1]], ">", ["value", 22]]
```

Example Response:
```json
true
```