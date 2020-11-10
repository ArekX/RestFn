# Or Op

This op represents one OR operation.

This operation returns true if one of the items in it is truthy. If one of the items is trutyh then this operation will
stop further evaluations.

Definition:`["or", <expression1: boolean>, ...<expressionN: boolean>]`

* __&lt;expressionN: boolean&gt;__ - represents an expression which returns truthy or falsy value.

## Usage


Request:
```json
["or", ["value", false], ["value", true]]
```


Response:
```json
true
```


## Typical Usage

This value is usually not sent alone to the requests, but it is used as a sub operation for other requests.

Example:

Request:
```json
["ifElse", 
  ["or", ["run", "getUser", 1], ["run", "getUser", 2]], 
  ["value", "There is an user"], 
  ["value", "No users found."] 
]
```

Response:
```json
"There is an user"
```