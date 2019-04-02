<?php

namespace QueryBuilder\Builder\Traits;

trait Where
{
    protected function setUpWhere()
    {
        !isset($this->sqlParts->where)
        and $this->sqlParts->where = new \QueryBuilder\Builder\Clause\Where($this->connection);
    }

    /**
     * Define the where with AND operator
     *
     * @param string $column
     * @param string $op
     * @param mixed $value
     * @return \QueryBuilder\Builder\Select
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
     * @param string $column
     * @param string $op
     * @param mixed $value
     * @return \QueryBuilder\Builder\Select
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
     * @return \QueryBuilder\Builder\Select
     */
    public function whereOpen()
    {
        return $this->where('(', null, null);
    }

    /**
     * Close the Where group with AND operator
     *
     * @return \QueryBuilder\Builder\Select
     */
    public function whereClose()
    {
        return $this->where(')', null, null);
    }

    /**
     * Open a Where group with OR operator
     *
     * @return \QueryBuilder\Builder\Select
     */
    public function whereOrOpen()
    {
        return $this->whereOr('(', null, null);
    }

    /**
     * Close the Where group with OR operator
     *
     * @return \QueryBuilder\Builder\Select
     */
    public function whereOrClose()
    {
        return $this->whereOr(')', null, null);
    }
}
