# Sequence Op

This operation evaluates all items in the sequence and returns last result.

Definition: `["sequence", <item1: expression(any)>, ...<itemN: expression(any)>]`

* __&lt;item: expression(any)&gt;__ - represents an expression to be evaluated.

## Usage


Request:

This sequence 
```json
["sequence", 
   ["var", "user", ["run", "createUser", ["value", {
       "username": "test",
       "password": "test"
    }]
   ]],
   ["var", "group", ["run", "createUserGroup", ["object", {
       "name": ["value", "My Group"],
       "owner_id": ["var", "user.user_id"]
    }]
   ]],
   ["run", "linkGroup", ["object", {
       "to_group_id": ["value", 2],
       "from_group_id": ["var", "group.group_id"]
    }]
   ]
]
```


Response:
```json
{
   "group_linked": true
}
```


## Typical Usage

You would use this operation if you need to run multiple operations sequentially, and you can do it all in one
request. These include when you need to create an user and link it to an entity, or create multiple items all at once.