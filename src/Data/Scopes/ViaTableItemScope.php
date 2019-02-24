<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace ArekX\JsonQL\Data\Scopes;


use App\Data\DataItem;
use App\Data\Select;
use ArekX\JsonQL\Data\Query;
use ArekX\JsonQL\Helpers\Value;

class SubItemScope implements ScopeInterface
{
    public $parentColumn;
    public $subClass;
    public $subColumn;
    public $field;

    public function __construct($subClass, array $setup)
    {
        $this->subClass = $subClass;
        Value::setup($this, $setup, [
            'parentColumn' => null,
            'subColumn' => null,
            'field' => null
        ]);
    }


    public static function from($subItemClass, array $setup)
    {
        return new static($subItemClass, $setup);
    }

    public function apply(Query $query, array $subFields)
    {
        $query->setup(function (Select $select) {
            $select->andColumns($this->parentColumn);
        });

        $query->mapField($this->field, function ($results) use ($subFields) {
            /** @var DataItem $subClass */
            $subClass = $this->subClass;
            $subQuery = $subClass::request($subFields);

            $subQuery->request(function (Select $select) use ($results) {
                $select->andColumns($this->subColumn);
                $select->andWhere([$this->subColumn => array_unique(array_column($results, $this->parentColumn))]);
            });

            return [$subQuery, $this->subColumn, $this->parentColumn];
        });
    }
}