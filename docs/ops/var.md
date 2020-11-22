# Var Op

This operation sets or returns a variable.

Definition: `["var", <name: expression(string), string> [, <value: expression(any)>]]`

* __&lt;name: expression(string), string&gt;__ - represents a name which will be get or set. If `value` is not set
then this operation will return an already set variable by this name or `null` if there was no variable set previously.

* __&lt;value: expression(any)&gt;__ - [Optional value]. If it's set then this operation will set a variable to the
value evaluated in this expression. If this is set then this value will also be return as a result of this operation.

## Usage


Request:
```json
["sequence",
  ["var", "test", ["value", 55]],
  ["var", "test"]
]
```


Response:
```json
55
```


## Typical Usage

This operation is typically used together in [sequence](sequence.md) operator in order to store a state during
a request so that it can be used as a value in one of the other operations in a sequence.

Using it alone in a request will not have any visible effect.