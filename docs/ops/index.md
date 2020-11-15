# Ops

Operations are used during the requests to handle specific actions. You can pass multiple
operations in one request.

You can find all the supported operations below:


|     Operation         |     Definition |
|-----------------------|----------------|
| [Value](value.md)     | `["value", <literal: any>]` <br><br>  Returns a `literal` value directly as a result. |
| [Take](take.md)       | `["take", <number: int>, <result: expression(array)>]` <br><br> Takes a number of items from an resulting expression array and returns them. |
| [And](and.md)         | `["and", <check1: expression(boolean)>, ...<checkN: expression(boolean)>]` <br><br> Runs AND operation returing true if all `check` values are true. |
| [Or](or.md)           | `["or", <check1: expression(boolean)>, ...<checkN: expression(boolean)>]` <br><br> Runs OR operation returning first true value from `check`. |
| [Not](not.md)         | `["not", <check: expression(boolean)>]` <br><br> Runs NOT operation on result of `check`. |
| [Get](get.md)         | `["get", <key: string, expression(string)>, <result: expression(array)> [, <default: expression(any)>]]` <br><br> Gets a value specified by `key` from a `result`. |
| [Compare](compare.md) | `["compare",  <valueA: expression(any)>, <operation: string>, <valueB: expression(any)>]` <br><br>  Compares results of `valueA` and `valueB` by using `operation` |
| [If Else](ifelse.md) | `["ifElse", <check: expression(boolean)>, <trueResult: expression(any)>, <falseResult: expression(any)>]` <br><br>  Evaluates `check` and returns `trueResult` if true or `falseResult` if false. |
| [Map](map.md) | `["map", <key: string, expression(string)>, <value: string, expression(string)>, <result: expression(array)>]` <br><br>  Maps `result` to key-value list where key is specified by `key` and value is specified by `value` |