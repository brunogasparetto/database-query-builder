<?php

namespace Database\QueryBuilder\Builder\Clause;

/**
 * The Clause contract
 */
interface IClause extends ISQL
{
    /**
     * Set or Add items
     *
     * @param mixed $params
     */
    public function set($params);
}
