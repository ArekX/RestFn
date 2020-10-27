# Value Op

This value op represents one single literal value which can be used in other requests.

Value does not do any further evaluation of its parameters and just returns the value passed.

Definition:`["value", <any value>]`

## Usage


Request:
```json
["value", "this is a value"]
```


Response:
```json
"this is a value"
```


## Typical Usage

This value is usually not sent alone to the requests, but it is used as a literal inside evaluation for other ops.

Example:

Request:
```json
["take", 1, ["value", [1, 2, 3]]]
```

Response:
```json
2
```