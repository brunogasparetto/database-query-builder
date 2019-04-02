<?php

namespace Database\QueryBuilder\Builder;

class InsertCommand extends Builder
{
    private $values = [];
    private $table = '';
    private $duplicates = [];

    /**
     * Set the valeus to insert
     *
     * @param mixed $values Array or object with keys/properties as the table fields
     * @return InsertCommand
     */
    public function values($values)
    {
        if (is_object($values)) {
            $values = (array) $values;
        } elseif (!is_array($values)) {
            throw new \InvalidArgumentException('Must be a array or a object with public properties');
        }
        foreach ($values as $column => $value) {
            $this->values[$column] = $this->quote($value);
        }
        return $this;
    }

    /**
     * Set the table to insert
     *
     * @param string $table
     * @return InsertCommand
     */
    public function into($table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Get the full SQL
     *
     * @return string
     */
    public function sql()
    {
        if (empty($this->table) or empty($this->values)) {
            return '';
        }
        $sql = 'INSERT INTO ' . $this->table
            . ' (' . implode(', ', array_keys($this->values)) . ')'
            . ' VALUES (' . implode(', ', $this->values) . ')';

        if (!empty($this->duplicates)) {
            $parts = [];

            foreach ($this->duplicates as $colum => $expr) {
                $parts[] = $colum . ' = ' . $expr;
            }
            $sql .= ' ON DUPLICATE KEY UPDATE ' . implode(', ', $parts);
        }

        return $sql;
    }

    /**
     * On Duplicate clause
     *
     * @param mixed $column String or array/object with keys/properties as the table fields
     * @param mixed $expr
     * @return InsertCommand
     */
    public function onDuplicate($column, $expr = null)
    {
        if (is_array($column) or is_object($column)) {
            foreach ((array) $column as $column => $expr) {
                $this->duplicates[$column] = ($expr instanceof Clause\ISQL) ? $expr->sql() : $this->quote($expr);
            }
        } else {
            $this->duplicates[$column] = ($expr instanceof Clause\ISQL) ? $expr->sql() : $this->quote($expr);
        }
        return $this;
    }
}
