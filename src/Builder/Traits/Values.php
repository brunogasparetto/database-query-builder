<?php

namespace QueryBuilder\Builder\Traits;

use QueryBuilder\Builder\Clause\ISQL;

trait Values
{
    /**
     * Set the values
     *
     * @param  mixed $column String or array/object with the keys/properties as the table fields
     * @param  mixed $expr
     * @return self
     */
    public function values($column, $expr = null)
    {
        if (is_array($column) or is_object($column)) {
            foreach ((array) $column as $column => $expr) {
                $this->values[$column] = ($expr instanceof ISQL) ? $expr->sql() : $this->quote($expr);
            }
        } else {
            $this->values[$column] = ($expr instanceof ISQL) ? $expr->sql() : $this->quote($expr);
        }
        return $this;
    }
}
