<?php
/**
  * @author Aleksandar Panic
  * @link https://jsonql.readthedocs.io/
  * @license: http://www.apache.org/licenses/LICENSE-2.0
  * @since 1.0.0
 **/

namespace tests\Mock;

class MockRequest implements \ArekX\JsonQL\Interfaces\RequestInterface
{
    public $body = [];

    /**
     * Returns request body.
     *
     * @return array
     */
    public function read(): array
    {
        return $this->body;
    }
}