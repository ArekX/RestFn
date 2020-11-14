# And Op

This op represents one AND operation.

This operation returns true if all items in it are truthy. If one of the items is falsy then this operation will fail 
fast and not evaluate other operations.

Definition: `["and", <check1: expression(boolean)>, ...<checkN: expression(boolean)>]`

* __&lt;checkN: expression(boolean)&gt;__ - represents an expression which returns truthy or falsy value.

## Usage


Request:
```json
["and", ["value", true]]
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
  ["and", ["run", "getUser", 1], ["run", "getUser", 2]], 
  ["value", "There are users 1 and 2"], 
  ["value", "No users found."] 
]
```

Response:
```json
"There are users 1 and 2"
```