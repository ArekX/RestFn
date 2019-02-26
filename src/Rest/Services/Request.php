<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Rest\Services;

use ArekX\JsonQL\Helpers\Value;
use ArekX\JsonQL\Interfaces\RequestInterface;
use function ArekX\JsonQL\Validation\anyField;
use function ArekX\JsonQL\Validation\arrayField;
use function ArekX\JsonQL\Validation\objectField;
use ArekX\JsonQL\Values\InvalidValueException;

/**
 * Class Request
 *
 * Class for handling request data from HTTP input.
 *
 * @package ArekX\JsonQL\Rest\Services
 */
class Request implements RequestInterface
{
    /** @var null|array */
    protected $body = null;

    /** @var null|array */
    protected $meta = [];

    /**
     * @codeCoverageIgnore
     */
    public function read(): array
    {
        if ($this->body !== null) {
            return $this->body;
        }

        $result = @json_decode(file_get_contents('php://input'), true);

        if (empty($result)) {
            $result = [];
        }

        $errors = objectField([
            'meta' => arrayField()
        ])->requiredKeys([])->anyKey(anyField())->validate($result);

        if (!empty($errors)) {
            throw new InvalidValueException($errors);
        }

        if (array_key_exists('meta', $result)) {
            $this->meta = $result['meta'];
            unset($result['meta']);
        }

        return $this->body = $result;
    }

    public function getMeta(string $key, $defaultValue = null)
    {
        return Value::get($this->meta, $key, $defaultValue);
    }
}