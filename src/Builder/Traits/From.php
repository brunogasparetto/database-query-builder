<?php

namespace Database\QueryBuilder\Builder\Traits;

trait From
{
    /**
     * Set the table from
     *
     * @param string $table
     * @return \Database\QueryBuilder\Builder\Select
     */
    public function from($table)
    {
        !isset($this->sqlParts->from) and $this->sqlParts->from = new \Database\QueryBuilder\Builder\Clause\From();
        $this->sqlParts->from->set($table);
        return $this;
    }
}
