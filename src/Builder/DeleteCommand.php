<?php

namespace QueryBuilder\Builder;

class DeleteCommand extends Builder
{
    use Traits\From;
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
        if (!count($this->sqlParts) or !isset($this->sqlParts->from)) {
            return '';
        }

        $sql = '';

        foreach (['from', 'join', 'where', 'order', 'limit'] as $part) {
            if (!isset($this->sqlParts->$part)) {
                continue;
            }
            $result = $this->sqlParts->$part->sql();

            if ($result) {
                $sql .= ' ' . $result;
            }
        }

        return 'DELETE' . $sql;
    }

    public function limit($limit)
    {
        !isset($this->sqlParts->limit) and $this->sqlParts->limit = new Clause\Limit(false);
        $this->sqlParts->limit->set($limit);
    }
}
