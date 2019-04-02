<?php

namespace Database\QueryBuilder\Builder\Clause;

class Expr implements ISQL
{
    private $sql;

    /**
     * @param string $sql
     */
    public function __construct($sql)
    {
        $this->sql = $sql;
    }

    /**
     * Get the SQL
     *
     * @return string
     */
    public function sql()
    {
        return $this->sql;
    }

    /**
     * Get the SQL
     *
     * @return string
     */
    public function __toString()
    {
        return $this->sql;
    }
}
