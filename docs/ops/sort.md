# Sort Op

This is a sort operation.

This operation returns sorts a result by a key or a plain array in ascending or descending order.

Definition using by: `["sort", <by: expression(string, int), string, int>, <direction: expression(string), string>, <from: expression(array)>]`

* __&lt;by: expression(string, int), string, int&gt;__ - specifies a parameter to sort by. This can be a string if the
resulting array in `from` is an array of json objects or a number if the resulting array is an array of arrays.
This sort supports dot expression to traverse through the json object.

* __&lt;direction: expression(string), string&gt;__ - direction to sort by. Supported directions `asc` for ascending
and `desc` for descending.

* __&lt;from: expression(array)&gt;__ - Expression to be evaluated and the result which will be sorted.

Definition without by: `["sort", <direction: expression(string), string>, <from: expression(array)>]`

* __&lt;direction: expression(string), string&gt;__ - direction to sort by. Supported directions `asc` for ascending
and `desc` for descending.

* __&lt;from: expression(array)&gt;__ - Expression to be evaluated and the result which will be sorted.



## Usage


Request:
```json
["sort", "asc", ["value", [5, 22, 1, 2, 14, 1]]]
```


Response:
```json
[1, 1, 2, 5, 14, 22]
```


## Typical Usage

You would use this operation to sort a result from an evaluated response when necessary. Sort can be by a parameter if
the result is an array of json objects.

Example Request:
```json
["sort", "age", "desc", ["value", [
  {"name":  "jason", "age": 23},
  {"name":  "anna", "age": 1},
  {"name":  "eli", "age": 22}
]]]
```


Example Response:
```json
[
  {"name":  "jason", "age": 23},
  {"name":  "eli", "age": 22},  
  {"name":  "anna", "age": 1}
]
```