# JsonQL

JsonQL is a REST framework providing different and more effective access to the rest and faster and more cleaner development by using just one endpoint.

Requests can be aggregated to provide "one-trip request" to the server to get all of the necessary data.

All requests are checked using powerful type definitions which can be used to validate request and response data.
These type definitions also for allow generation of REST API automatically.

__NOTE:__ This framework is still a work in progress and is not production ready.

# Testing

Testing is done using `PHPUnit`

* To run all tests run `composer test`
* To generate test coverage run `composer coverage`

# Documentation

## Manual
Manual is available on https://jsonql.readthedocs.io/en/latest

Otherwise it can be generated locally using steps below:
1. Install Python and PIP.
2. Install MkDocs with `pip install mkdocs`
3. From project root run `mkdocs build`
4. Manual will be built in the `site` folder.

## API Documentation

API documentation can be generated using phpDocumentor.

Steps for generation are below:
1. From project root run `composer document`
2. Documentation will be generated in `api` folder.