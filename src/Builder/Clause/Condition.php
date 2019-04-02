<?php

namespace QueryBuilder\Builder\Clause;

use \QueryBuilder\Builder\Builder as Builder;

/**
 * Abstract Condition to implements Where and Having Clauses
 */
abstract class Condition extends Builder implements IClause
{
    private $conditions = [];
    private $sql = '';
    private $lastCondition = null;

    /**
     * Add a new Condition
     *
     * @param string $logic
     * @param string $column
     * @param string $operator
     * @param mixed $value
     * @throws \InvalidArgumentException
     */
    public function set($logic, $column = null, $operator = null, $value = null)
    {
        if ($column === null) {
            throw new \InvalidArgumentException('Expected a column or parentheses.');
        }
        if ($operator === null and $value === null) {
            $this->conditions[] = [strtoupper($logic) => $column];
        } else {
            $this->conditions[] = [strtoupper($logic) => [$column, $operator, $value]];
        }
    }

    /**
     * Get the SQL
     *
     * @return string
     */
    public function sql()
    {
        if (empty($this->conditions)) {
            return '';
        }

        $this->sql = '';

        foreach ($this->conditions as $group) {
            foreach ($group as $logic => $condition) {
                $this->setSql($logic, $condition);
            }
        }
        return $this->sql;
    }

    /**
     * Set the Sql for the group
     *
     * @param string $logic
     * @param mixed $condition
     */
    private function setSql($logic, $condition)
    {
        if ($this->setLogic($logic, $condition)) {
            $this->sql .= $this->getComparisonSql($condition);
        }
        $this->lastCondition = $condition;
    }

    /**
     * Set the keyword AND or OR if necessary
     *
     * @param string $logic
     * @param mixed $condition
     * @return boolean If returns true is necessary set the comparison
     */
    private function setLogic($logic, &$condition)
    {
        if (is_string($condition)) {
            if (!empty($this->sql) and $this->lastCondition !== '(' and $condition !== ')') {
                $this->sql .= ' ' . $logic . ' ';
            }
            $this->sql .= $condition;
            return false;
        }

        if (!empty($this->sql) and $this->lastCondition !== '(') {
            $this->sql .= ' ' . $logic . ' ';
        }
        return true;
    }

    /**
     * Get the SQL to the comparison
     *
     * @param array $condition
     * @return string
     */
    private function getComparisonSql(array &$condition)
    {
        list($column, $operator, $value) = $condition;
        $operator = strtoupper($operator);

        if (($sql = $this->nullValueSql($column, $operator, $value))) {
            return $sql;
        }

        if (($sql = $this->betweenValueSql($column, $operator, $value))) {
            return $sql;
        }

        if ($value instanceof ISQL) {
            $value = '(' . $value->sql() . ')';
        } else {
            $value = $this->quote($value);
        }

        return $column . ' ' . $operator . ' ' . $value;
    }

    /**
     * Get the SQL to a null comparison
     *
     * @param string $column
     * @param string $operator
     * @param mixed $value
     * @return string
     */
    private function nullValueSql($column, $operator, $value)
    {
        if ($value !== null) {
            return '';
        }

        if ($operator === '=') {
            $operator = 'IS';
        } elseif ($operator === '!=' or $operator === '<>') {
            $operator = 'IS NOT';
        }
        return $column . ' ' . strtoupper($operator) . ' ' . $this->quote($value);
    }

    /**
     * Get the SQL to a BETWEEN comparison with a array in the value
     *
     * @param string $column
     * @param string $operator
     * @param mixed $value
     * @return string
     */
    private function betweenValueSql($column, $operator, $value)
    {
        if ($operator !== 'BETWEEN' or !is_array($value)) {
            return '';
        }
        list($min, $max) = $value;
        is_string($min) and $min = $this->quote($min);
        is_string($max) and $max = $this->quote($max);

        return $column . ' ' . $operator . ' ' . $min . ' AND ' . $max;
    }
}
