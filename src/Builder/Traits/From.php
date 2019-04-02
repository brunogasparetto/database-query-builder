<?php

namespace QueryBuilder\Builder\Traits;

trait From
{
    /**
     * Set the table from
     *
     * @param string $table
     * @return \QueryBuilder\Builder\Select
     */
    public function from($table)
    {
        !isset($this->sqlParts->from) and $this->sqlParts->from = new \QueryBuilder\Builder\Clause\From();
        $this->sqlParts->from->set($table);
        return $this;
    }
}
