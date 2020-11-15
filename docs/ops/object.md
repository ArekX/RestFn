# Object Op

This is an object operation.

This operation evaluates an expression for every item in the object and then returns the resulting object
where the keys match the keys sent in the request.

Definition: `["object", <object: array[key -> expression(any)]>]`

* __&lt;object: array[key -> expression(any)]&gt;__ - Represents JSON object sent. This object will be walked through
evaluating each key of it as an expression and returning the result once all keys are evaluated.

## Usage


Request:
```json
["object", {
  "user": ["run", "getUser", 1],
  "profile": ["run", "getProfile", 1]
}]
```


Response:
```json
{
   "user": {
       "username": "test",
       "email": "test@email.com"
   },
   "profile": {
       "number_of_contacts": 20,
       "logged_in": true
   }
}
```


## Typical Usage

You can use this operation to return a desired object where you can run multiple evaluations for a single object. This
allows for creating a joined object of values needed in your application in one request instead of sending multiple
requests.