<?php
/**
  * @author Aleksandar Panic
  * @link https://jsonql.readthedocs.io/
  * @license: http://www.apache.org/licenses/LICENSE-2.0
  * @since 1.0.0
 **/

namespace ArekX\JsonQL\Rest\Services;

use ArekX\JsonQL\Interfaces\RequestInterface;

class Request implements RequestInterface
{
    /** @var null|array */
    protected $body = null;

    /**
     * @codeCoverageIgnore
     */
    public function read(): array
    {
        if ($this->body !== null) {
            return $this->body;
        }

        $this->body = @json_decode(file_get_contents('php://input'), true);

        if (empty($this->body)) {
            $this->body = [];
        }

        return $this->body;
    }
}