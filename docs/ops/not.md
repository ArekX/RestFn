# Not Op

This op represents one NOT operation.

This operation returns true if the result is false or returns false if the result is true.

Definition: `["not", <check: expression(boolean)>]`

* __&lt;check: expression(boolean)&gt;__ - represents an expression which returns truthy or falsy value.

## Usage


Request:
```json
["not", ["value", false]]
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
  ["not", ["run", "getUser", 1]], 
  ["value", "There is an user with ID 1"], 
  ["value", "No user found."] 
]
```

Response:
```json
"There is an user with ID 1"
```