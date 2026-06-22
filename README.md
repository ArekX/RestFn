# RestFn

RestFn ("REST function") is a PHP library for building a single, functional-style
endpoint.

Instead of having many REST endpoints, you have one. The client sends a request
that is a tree of operations, and the server runs that tree to shape the response.
You write actions for the actual work, and the client composes those actions with
built-in operations (get, map, sort, run, and so on) to get back the data it needs
in one request.

RestFn is the engine for this. It gives you the operation language and a dependency
injection container to wire everything together; you provide the endpoint and the
actions.

Requires PHP 8.4+.

[![Documentation Status](https://readthedocs.org/projects/restfn/badge/?version=latest)](https://restfn.readthedocs.io/en/latest/?badge=latest)
[![Build Status](https://scrutinizer-ci.com/g/ArekX/RestFn/badges/build.png?b=master)](https://scrutinizer-ci.com/g/ArekX/RestFn/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/ArekX/RestFn/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/ArekX/RestFn/?branch=master)
[![Code Coverage](https://scrutinizer-ci.com/g/ArekX/RestFn/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/ArekX/RestFn/?branch=master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/ArekX/RestFn/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)

## User guide

User guide is available on https://restfn.readthedocs.io/en/latest

### Manual generation

1. Install `Python` and `pip`.
2. Install `mkdocs` with `pip install mkdocs`
3. From project's directory run `mkdocs build`
4. Manual will be built in the `site` folder.

## Testing

Testing is done using `PHPUnit`

* To run all tests run `composer test`
* To generate test coverage run `composer coverage`

## License

Copyright 2025 Aleksandar Panic

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

http://www.apache.org/licenses/LICENSE-2.0 or [in this repository](LICENSE.md)

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.