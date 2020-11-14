# Get Op

This op represents a retrieval operation

This operation returns one specific value, referenced by a key from the resulting expression or a default if specified.

Definition:`["get",  <key: string | expression(string)>, <result: expression(array)> [, <default: expression(any)>]]`

* __&lt;key: string | expression(string)&gt;__ - Key which will be used to search the value by. This key supports full
walking through resulting array by using a dot syntax.

For example for an array:
```php
[
  'path' => [
     'to' => [
        'value' => 'this is a value'
     ]
  ]
]
```
You can return `'this is a value'` directly if you use the key `path.to.value`. If the key does not exist in resulting
array in `<result: expression(array)>`, `NULL` will be returned unless there is a default expression specified.

You can also specify an expression to be evaluated as a key which must return a string.

* __&lt;result: expression(array)&gt;__ - Expression which will be evaluated which should return an array which
can be walked through. This operation will walk through this array to get the requested value from `key`. If no
value is found here, `NULL` will be returned or a value specified in optional `default` expression.

* __&lt;default: expression(array)&gt;__ - [Optional value]. If you pass this to the request and nothing can be returned
from `result` expression, this value will be evaluated and it's result returned directly. If this expression is not
specified, `NULL` will be returned.

## Usage


Request:
```json
["get", "path.to.value", ["value", {
    "path" : {
        "to" : {
           "value" : "this is a value"
        }
    }
}]]
```


Response:
```json
"this is a value"
```


## Typical Usage

You would use this operation to return a specific desired result directly from a processed expression.