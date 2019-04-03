<?php

namespace QueryBuilder\Builder\Traits;

trait Join
{
    /**
     * Join tables
     *
     * @param  string $table
     * @param  string $type
     * @return self
     */
    public function join($table, $type = 'INNER')
    {
        !isset($this->sqlParts->join) and $this->sqlParts->join = new \QueryBuilder\Builder\Clause\Join();
        $this->sqlParts->join->set($table, $type);
        return $this;
    }

    /**
     * On clause to JOIN tables
     *
     * @param  string $column1
     * @param  string $operator
     * @param  string $column2
     * @return self
     */
    public function on($column1, $operator, $column2)
    {
        if (!isset($this->sqlParts->join)) {
            throw new \LogicException("Can't assign a ON clause without a JOIN");
        }
        $this->sqlParts->join->set($column1, $operator, $column2);
        return $this;
    }
}
