# Take Op

This operation takes a number of items from a resulting evaluated array. 

Definition:`["take", <number: int>, <expression: array>]`

* __&lt;number: int&gt;__ - number of items to take from result array. If it's above 0 it returns number of items.
  If it's below zero it will take the amount of items from the end.
* __&lt;expression: array&gt;__ - expression which will be evaluated which returns an array.
  If an array is not returned this operation will throw an exception.

## Usage


Request:
```json
["take", 3, ["value", [1, 2, 3, 4, 5]]]
```


Response:
```json
[1, 2, 3]
```


## Typical Usage

This value is to get a part of an array result when whole result is not necessary.