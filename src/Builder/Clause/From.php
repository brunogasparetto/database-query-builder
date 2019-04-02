<?php

namespace Database\QueryBuilder\Builder\Clause;

/**
 * The From Clause
 */
class From implements IClause
{
    private $table = '';

    /**
     * Set the Table
     *
     * @param string $table
     */
    public function set($table)
    {
        $this->table = $table;
    }

    /**
     * Get the SQL
     *
     * @return string
     */
    public function sql()
    {
        return empty($this->table)
            ? ''
            : 'FROM ' . $this->table;
    }
}
