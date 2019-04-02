<?php

namespace QueryBuilder\Builder\Traits;

trait Order
{
    /**
     * Set the Order By
     *
     * Each call add more item in the Order By
     *
     * @param string $column
     * @param string $order [optional]
     * @return \QueryBuilder\Builder\Select
     */
    public function order($column, $order = 'ASC')
    {
        !isset($this->sqlParts->order) and $this->sqlParts->order = new \QueryBuilder\Builder\Clause\Order();
        $this->sqlParts->order->set($column, $order);
        return $this;
    }
}
