<?php
/**
 * @author Aleksandar Panic
 * @link https://jsonql.readthedocs.io/
 * @license http://www.apache.org/licenses/LICENSE-2.0
 * @since 1.0.0
 **/

namespace App\Readers;

use App\Services\Database;
use ArekX\JsonQL\Data\Query;
use ArekX\JsonQL\Rest\Interfaces\ReaderInterface;

class TestUser implements ReaderInterface
{
    /** @var Database */
    public $db;

    public function __construct(Database $db, array $setup)
    {
        $this->db = $db;
    }

    public function run(array $data)
    {
        $result = $this->db->select('actor', [
            'actor_id',
            'first_name'
        ]);
        // ScopedData
        return $result + ['test' => 2];
    }
}