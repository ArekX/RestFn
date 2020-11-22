# Cast Op

This operation casts one value into another value.

Definition: `["cast", <into: expression(string), string>, <from: expression(any)>]`

* __&lt;into: expression(string), string&gt;__ - This is a type which you can cast into.
Supported types: `int`, `float`, `bool`, `string`

## Usage


Request:
```json
["cast", "float", ["value", "1.45"]]
```


Response:
```json
1.45
```


## Typical Usage

You would use this operation to convert one value into another when needed.