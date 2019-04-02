<?php

namespace Database\QueryBuilder\Builder\Clause;

/**
 * The SQL contract
 */
interface ISQL
{
    /**
     * Get the SQL
     *
     * @return string
     */
    public function sql();
}
