# Merge Op

This operation is a shallow merge operation between arrays.

This operation joins two or more resulting arrays into one arrays. Arrays can have number based indices, or they
can be associative arrays.

Same keys will get overwritten so this operation can be used to set default arrays.

Definition: `["merge", <result1: expression(array)>, ...<resultN: expression(array)>]`

* __&lt;result: expression(array)&gt;__ - Expressions to be evaluated expecting resulting arrays which are going
to be merged into one array.

## Usage


Request:
```json
["merge", 
  ["value", {
    "userRole": "defaultRole",
    "default": "parameter"
  }], 
  ["run", "getUser", 1]
]
```


Response:
```json
{
  "userName": "Test User",
  "email": "test@user.com",
  "userRole": "defaultRole",
  "default": "parameter"
}
```


## Typical Usage

You would use this operation to merge two or more arrays into one. You can also merge objects as they are associative
arrays.