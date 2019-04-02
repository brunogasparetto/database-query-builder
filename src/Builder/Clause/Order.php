<?php

namespace QueryBuilder\Builder\Clause;

/**
 * The Order Clause
 */
class Order implements IClause
{
    private $orders = [];

    /**
     * Get the SQL
     *
     * @return string
     */
    public function sql()
    {
        if (empty($this->orders)) {
            return '';
        }
        $parts = [];

        foreach ($this->orders as $items) {
            list($column, $order) = $items;
            $parts[] = "$column $order";
        }
        return 'ORDER BY ' . implode(', ', $parts);
    }

    /**
     * Add a new item to Order Clause
     *
     * @param string $column
     * @param string $order
     */
    public function set($column, $order = 'ASC')
    {
        $this->orders[] = [$column, strtoupper($order)];
    }
}
