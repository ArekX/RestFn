# Map Op

This operation maps result from evaluated result expression into a key-value list.

Definition: `["map", <key: string, expression(string)>, <value: string, expression(string)>, <result: expression(array)>]`

* __&lt;key: string, expression(string)&gt;__ - Specifies a key to be used in key-value result.
* __&lt;value: string, expression(string)&gt;__ - Specifies a value to be used in key-value result.
* __&lt;result: expression(array)&gt;__ - Expression to be evaluated from which the key-value list will be created.

## Usage

Request:
```json
["map", "name", "age", ["value", [
   {"name": "john", "age":  22},
   {"name": "mark", "age":  15}
]]]
```


Response:
```json
{
   "john": 22,
   "mark": 15
}
```


## Typical Usage

You can use this operation in conjuction with other expresison to result key-value lists which you can use in 
applications for filtering, validation or just list showing.