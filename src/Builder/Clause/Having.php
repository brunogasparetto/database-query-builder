<?php

namespace QueryBuilder\Builder\Clause;

/**
 * The Having Clause
 */
class Having extends Condition
{
    /**
     * Get the SQL
     *
     * @return string
     */
    public function sql()
    {
        $sql = parent::sql();

        return empty($sql)
            ? ''
            : 'HAVING ' . $sql;
    }

}
