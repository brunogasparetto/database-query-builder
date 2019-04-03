<?php

namespace QueryBuilder\Builder;

use QueryBuilder\Builder\Clause\IClause;

/**
 * Container to save the SQL parts
 */
class PartsContainer implements \Countable, \ArrayAccess
{
    /**
     * @var IClause[]
     */
    private $parts = [];

    /**
     * Get the Clause Part
     *
     * @param  string $clause
     * @return IClause
     * @throws \OutOfRangeException
     */
    public function __get($clause)
    {
        if (!isset($this->parts[$clause])) {
            throw new \OutOfRangeException();
        }
        return $this->parts[$clause];
    }

    /**
     * Set a part
     *
     * @param string $clause
     * @param IClause $clauseObject
     */
    public function __set($clause, IClause $clauseObject)
    {
        $this->parts[$clause] = $clauseObject;
    }

    /**
     * Isset magic method
     *
     * @param  string $clause
     * @return bool
     */
    public function __isset($clause)
    {
        return isset($this->parts[$clause]);
    }

    /**
     * The total parts
     *
     * @return int
     */
    public function count()
    {
        return count($this->parts);
    }

    /**
     * @param  string $clause
     * @return bool
     */
    public function offsetExists($clause)
    {
        return isset($this->parts[$clause]);
    }

    /**
     * @param  string $clause
     * @return IClause
     */
    public function offsetGet($clause)
    {
        if (!isset($this->parts[$clause])) {
            throw new \OutOfRangeException();
        }
        return $this->parts[$clause];
    }

    /**
     * Set a part
     *
     * @param string $clause
     * @param IClause $clauseObject
     */
    public function offsetSet($clause, $clauseObject)
    {
        if ($clauseObject instanceof IClause) {
            $this->parts[$clause] = $clauseObject;
        } else {
            throw new \InvalidArgumentException();
        }
    }

    /**
     * @param string $clause
     */
    public function offsetUnset($clause)
    {
        if (isset($this->parts[$clause])) {
            unset($this->parts[$clause]);
        }
    }

    /**
     * Clear all the parts
     */
    public function clear()
    {
        foreach (array_keys($this->parts) as $part) {
            unset($this->parts[$part]);
        }
    }
}
