# Coalesce Op

This is a coalescing operation.

This operation keeps evaluating and returning first non-null result. If there are expressions after the non-null
value. They will not be evaluated.

Definition:  `["coalesce", <result1: expression(any)>, ...<resultN: expression(any)>]`

* __&lt;result: expression(any)&gt;__ - These are results to be evaluated. Each result is evaluated sequentially
and first non-null result is returned. Results after that are not evaluated.

## Usage


Request:
```json
["coalesce", ["run", "getUser", 1], ["value", {"name"; "default user"}]]
```


Response:
```json
{"name": "default user"}
```


## Typical Usage

You would use this operation to return default values if one or more operations fail and return a null.