<?php

namespace QueryBuilder\Builder;

class UpdateCommand extends Builder
{
    use Traits\Join;
    use Traits\Where;
    use Traits\Order;
    use Traits\Values;

    private $table = '';
    private $values = [];

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
     * @param  string $table
     * @return self
     */
    public function update($table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * Set limit clause
     *
     * @param  int $limit
     * @return self
     */
    public function limit($limit)
    {
        !isset($this->sqlParts->limit) and $this->sqlParts->limit = new Clause\Limit(false);
        $this->sqlParts->limit->set($limit);
        return $this;
    }
}
