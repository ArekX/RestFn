# Ops

Operations are used during the requests to handle specific actions. You can pass multiple
operations in one request.

You can find all the supported operations below:


|     Operation      |                                Definition                                   |                                  Description                                   |
|--------------------|-----------------------------------------------------------------------------|--------------------------------------------------------------------------------|
| [Value](value.md)  | `["value", <literal: any>]`                                                 |  Returns a literal directly as a result.                                       |
| [Take](take.md)    | `["take", <number: int>, <result: expression(array)>]`                      |  Takes a number of items from an resulting expression array and returns them.  |
| [And](and.md)      | `["and", <check1: expression(boolean)>, ...<checkN: expression(boolean)>]`  |  Runs one AND operation.                                                       |
| [Or](or.md)        | `["or", <check1: expression(boolean)>, ...<checkN: expression(boolean)>]`   |  Runs one OR operation.                                                        |
| [Not](not.md)      | `["not", <check: expression(boolean)>]`                                     |  Runs one NOT operation.                                                       |