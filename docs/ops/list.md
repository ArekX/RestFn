# List Op

This operation run a list action and returns a paginated result.

List Action is a REST action which runs cannot return a full result and needs to do it in chunks.

All created list actions need to implement `ListAction` interface

Definition: `["list", <name: expression(string), string>, <data: expression(array)>]`

* __&lt;name: expression(string), string&gt;__ - Name of the action to be ran. 
This action must be defined in the parser in order to be ran. If action does not exist an error will be thrown.

* __&lt;data: expression(array)&gt;__ - Data to be sent for the request. The list request accepts following data for the
request:
    * properties - Array of strings noting which properties should be returned.
    * filter - Optional. Key, value json object of filter to be applied.
    * pageSize - Optional. Integer of page size meaning how many items per page to be returned.
    * page - Optional. Zero based integer denoting which page of data to return. 
     
## Usage


Request:

This operation runs `user` requesting properties `"username", "email", "user_id"`

```json
["list", "users", {
   "properties": ["username", "email", "user_id"],
   "filter": {"email":  "test"}
}]
```


Response:

This is an example response of a `user` list action showing two users in one result.
Total denotes total amount of results for this action and the passed filter.

```json
{
   "total": 2,
   "result": [
      {"username":  "test1", "email":  "test1@email.com", "user_id":  1},
      {"username":  "test2", "email":  "test2@email.com", "user_id":  2}
   ]
}
```


## Typical Usage

You would use this in actions which need to return lots of data in places such as tables or paginated reports.