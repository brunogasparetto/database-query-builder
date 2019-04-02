<?php

namespace QueryBuilder\Builder;

class UpdateCommand extends Builder
{
    private $table = '';
    private $values = [];

    use Traits\Join;
    use Traits\Where;
    use Traits\Order;

    /**
     * Get the SQL
     *
     * @return string
     */
    public function sql()
    {
        if (empty($this->table) or empty($this->values)) {
            return '';
        }

        $sql = 'UPDATE ' . $this->table;

        if (isset($this->sqlParts->join)) {
            $sql .= ' ' . $this->sqlParts->join->sql();
        }

        $parts = [];

        foreach ($this->values as $column => $value) {
            $parts[] = $column . ' = ' . $value;
        }

        $sql .= ' SET ' . implode(', ', $parts);

        foreach (['where', 'order', 'limit'] as $part) {
            if (isset($this->sqlParts->$part)) {
                $sql .= ' ' . $this->sqlParts->$part->sql();
            }
        }

        return $sql;
    }

    /**
     * Set the table to update
     *
     * @param string $table
     * @return UpdateCommand
     */
    public function update($table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Set limit clause
     *
     * @param int $limit
     * @return UpdateCommand
     */
    public function limit($limit)
    {
        !isset($this->sqlParts->limit) and $this->sqlParts->limit = new Clause\Limit(false);
        $this->sqlParts->limit->set($limit);
        return $this;
    }

    /**
     * Set the values
     *
     * @param mixed $column String or array/object with the keys/properties as the table fields
     * @param mixed $expr
     * @return void
     */
    public function values($column, $expr = null)
    {
        if (is_array($column) or is_object($column)) {
            foreach ((array) $column as $column => $expr) {
                $this->values[$column] = ($expr instanceof Clause\ISQL) ? $expr->sql() : $this->quote($expr);
            }
        } else {
            $this->values[$column] = ($expr instanceof Clause\ISQL) ? $expr->sql() : $this->quote($expr);
        }
        return $this;
    }
}
