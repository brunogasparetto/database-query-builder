<?php

namespace Database\QueryBuilder\Builder\Clause;

/**
 * The Where Clause
 */
class Where extends Condition
{
    /**
     * Get the SQL
     *
     * @return string
     */
    public function sql()
    {
        $sql = parent::sql();

        return empty($sql) ? '' : 'WHERE ' . $sql;
    }
}
