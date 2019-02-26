<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Data;

class Query
{
    const SORT_ASCENDING = 'ascending';
    const SORT_DESCENDING = 'descending';

    public $errors = [];

    /** @var static[] */
    public $subQueries = [];

    public $indexBy = null;

    public $metadata = [];
    public $fields = [];
    public $setups = [];
    public $mapFields = [];
    public $takeFields = [];
    public $request = [];
    public $mappers = [];
    public $filters = [];

    public function addError(string $field, $error)
    {
        $this->errors[$field] = $error;
    }

    public function valid()
    {
        $isParentValid = empty($this->errors);

        if (!$isParentValid) {
            return false;
        }

        foreach ($this->subQueries as $subQuery) {
            if (!$subQuery->valid()) {
                return false;
            }
        }

        return true;
    }

    public function requestField(string $field, ?callable $setup = null)
    {
        $this->fields[$field] = $setup;
    }

    public function mapField(string $field, ?callable $setup = null)
    {
        $this->mapFields[$field] = $setup;
    }

    public function meta(string $key, $value)
    {
        $this->metadata[$key] = $value;
    }

    public function setup(callable $setup)
    {
        $this->setups[] = $setup;
    }

    public function request(callable $requester)
    {
        $this->request[] = $requester;
    }

    public function map(callable $mapper)
    {
        $this->mappers[] = $mapper;
    }

    public function filter(string $key, callable $value)
    {
        $this->filters[$key] = $value;
    }

    public function takeFields(array $fields)
    {
        $this->takeFields = $fields;
    }
}