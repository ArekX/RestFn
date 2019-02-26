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

class ViaTableItemScope implements ScopeInterface
{
    public $parentColumn;
    public $subClass;
    public $field;
    public $via;
    public $viaColumn;
    public $scopeColumn;
    public $viaScope;

    public function __construct($subClass, array $setup)
    {
        $this->subClass = $subClass;
        Value::setup($this, $setup, [
            'parentColumn' => null,
            'field' => null,
            'via' => null,
            'viaColumn' => null,
            'scopeColumn' => null,
            'viaScope' => null
        ]);
    }

    public static function from($subItemClass, array $setup)
    {
        return new static($subItemClass, $setup);
    }

    public function apply(Query $query, array $subFields)
    {
        $query->requestField($this->parentColumn);
        $query->mapField($this->field, function ($results) use ($subFields) {
            /** @var DataItem $subItemClass */
            $subItemClass = $this->subClass;
            $subQuery = $subItemClass::request($subFields);

            $subQuery->request(function (Select $select) use ($results, $subQuery) {

                $select->columns = array_map(function ($column) use ($subQuery) {
                    if (strpos($column, '.') === false) {
                        return $subQuery->metadata['tableName'] . '.' . $column;
                    }

                    return $column;
                }, $select->columns);

                $select->andColumns($this->scopeColumn);

                $paramField = $select->scopeParam();
                $viaScope = $this->viaScope;
                $viaScope[] = $this->scopeColumn . ' IN (' . $paramField . ')';
                $select->join("INNER JOIN", $this->via, $viaScope);
                $select->param($paramField, array_unique(array_column($results, $this->parentColumn)));
            });

            return [$subQuery, $this->viaColumn, $this->parentColumn];
        });
    }
}