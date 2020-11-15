# If Else Op

This operation runs if and returns evaluated true expression or evaluated false expression.

Definition: `["ifElse", <check: expression(boolean)>, <trueResult: expression(any)>, <falseResult: expression(any)>]`

* __&lt;check: expression(boolean)&gt;__ - represents an expression which returns truthy or falsy value.
* __&lt;trueResult: expression(any)&gt;__ - Expression which is evaluated and returned if the result from `check` is truthy.
* __&lt;falseResult: expression(any)&gt;__ - Expression which is evaluated and returned if the result from `check` is falsy.

## Usage

Request:
```json
["ifElse", ["value", true], ["value", "true value"], ["value", "false value"]]
```


Response:
```json
"true value"
```


## Typical Usage

This operation is used when one or the other expression needs to evaluated and returned based on some check.

Example:

Request:
```json
["ifElse", 
  ["run", "userExists", 1], 
  ["value", "There is an user with ID 1"], 
  ["value", "No user found."] 
]
```

Response:
```json
"There is an user with ID 1"
```