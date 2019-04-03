<?php

namespace QueryBuilder\Builder\Traits;

trait Limit
{
    /**
     * Set the Limit and Offset
     *
     * @param  int $limit
     * @param  int $offset [optional]
     * @return self
     */
    public function limit($limit, $offset = 0)
    {
        !isset($this->sqlParts->limit) and $this->sqlParts->limit = new \QueryBuilder\Builder\Clause\Limit();
        $this->sqlParts->limit->set($limit, $offset);
        return $this;
    }
}
