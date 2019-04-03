<?php

namespace QueryBuilder\Builder\Traits;

trait Where
{
    /**
     * Create the where's parts
     */
    protected function setUpWhere()
    {
        !isset($this->sqlParts->where)
        and $this->sqlParts->where = new \QueryBuilder\Builder\Clause\Where($this->connection);
    }

    /**
     * Define the where with AND operator
     *
     * @param  string $column
     * @param  string $op
     * @param  mixed $value
     * @return self
     */
    public function where($column, $op, $value)
    {
        $this->setUpWhere();
        $this->sqlParts->where->set('AND', $column, $op, $value);
        return $this;
    }

    /**
     * Define the where with OR operator
     *
     * @param  string $column
     * @param  string $op
     * @param  mixed $value
     * @return self
     */
    public function whereOr($column, $op, $value)
    {
        $this->setUpWhere();
        $this->sqlParts->where->set('OR', $column, $op, $value);
        return $this;
    }

    /**
     * Open a Where group with AND operator
     *
     * @return self
     */
    public function whereOpen()
    {
        return $this->where('(', null, null);
    }

    /**
     * Close the Where group with AND operator
     *
     * @return self
     */
    public function whereClose()
    {
        return $this->where(')', null, null);
    }

    /**
     * Open a Where group with OR operator
     *
     * @return self
     */
    public function whereOrOpen()
    {
        return $this->whereOr('(', null, null);
    }

    /**
     * Close the Where group with OR operator
     *
     * @return self
     */
    public function whereOrClose()
    {
        return $this->whereOr(')', null, null);
    }
}
