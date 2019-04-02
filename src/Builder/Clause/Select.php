<?php

namespace QueryBuilder\Builder\Clause;

/**
 * The Select clause
 */
class Select implements IClause
{

    private $columns = [];

    /**
     * Add items to select
     *
     * @param mixed $columns The args are get by func_get_args if are not an array
     */
    public function set($columns = null)
    {
        if (!is_array($columns)) {
            $columns = func_get_args();
        }
        $this->columns = array_merge($this->columns, $columns);
    }

    /**
     * Get the SQL
     *
     * @return string
     */
    public function sql()
    {
        return 'SELECT ' . (empty($this->columns) ? '*' : implode(', ', array_unique($this->columns)));
    }
}
