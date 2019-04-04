<?php

namespace QueryBuilder\Builder;

class InsertCommand extends Builder
{
    use Traits\Values;

    private $values = [];
    private $table = '';
    private $duplicates = [];

    /**
     * Set the table to insert
     *
     * @param  string $table
     * @return self
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
     * @param  mixed $column String or array/object with keys/properties as the table fields
     * @param  mixed $expr
     * @return self
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
