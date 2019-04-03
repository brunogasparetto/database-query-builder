<?php

namespace QueryBuilder\Builder\Clause;

/**
 * The Limit Clause
 */
class Limit implements IClause
{
    private $limit = 0;
    private $offset = 0;
    private $showOffset = true;

    /**
     *
     * @param bool $showOffset If false don't show the offset (think in Delete clause)
     */
    public function __construct($showOffset = true)
    {
        $this->showOffset = (bool) $showOffset;
    }

    /**
     * Set the Limit and Offset
     *
     * @param  int $limit
     * @param  int $offset
     * @throws \InvalidArgumentException
     */
    public function set($limit, $offset = 0)
    {
        if (!is_int($limit) or !  is_int($offset)) {
            throw new \InvalidArgumentException('Need be a integer');
        }
        $this->limit = intval($limit);
        $this->offset = intval($offset);
    }

    /**
     * Get the SQL
     *
     * @return string
     */
    public function sql()
    {
        if (empty($this->limit)) {
            return '';
        }

        return $this->showOffset
            ? "LIMIT {$this->offset}, {$this->limit}"
            : "LIMIT {$this->limit}";
    }
}
