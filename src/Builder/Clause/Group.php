<?php

namespace QueryBuilder\Builder\Clause;

/**
 * The Group By Clause
 */
class Group implements IClause
{
    private $groups = [];

    /**
     * Add Columns to the Group
     *
     * @param array $columns
     */
    public function set($columns)
    {
        $this->groups = array_merge($this->groups, $columns);
    }

    /**
     * Get the SQL
     *
     * @return string
     */
    public function sql()
    {
        return empty($this->groups)
            ? ''
            : 'GROUP BY ' . implode(', ', $this->groups);
    }
}
