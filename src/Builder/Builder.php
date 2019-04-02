<?php

namespace Database\QueryBuilder\Builder;

abstract class Builder implements Clause\ISQL
{
    /**
     * The Database Connection to use \PDO::quote and \PDO::query
     *
     * @var \PDO
     */
    protected $connection;

    /**
     * The SQL pieces
     *
     * @var PartsContainer
     */
    protected $sqlParts;

    public function __construct(\PDO $conn)
    {
        $this->connection = $conn;
        $this->sqlParts = new PartsContainer();
    }

    /**
     * Execute the SQL
     *
     * @throws \LogicException
     * @return \PDOStatement
     */
    public function execute()
    {
        $sql = $this->sql();

        if (empty($sql)) {
            throw new \LogicException('Need provide a valid SQL.');
        }
        return $this->connection->query($sql);
    }

    /**
     * Convert the value to use in SQL
     *
     * @param mixed $value
     * @return string
     */
    public function quote($value)
    {
        if ($value === null) {
            return 'NULL';
        } elseif (is_bool($value) or is_int($value)) {
            return (int) $value;
        } elseif (is_float($value)) {
            return sprintf('%F', $value);
        } elseif (is_array($value)) {
            return '(' . implode(', ', array_map(array($this, __FUNCTION__), $value)) . ')';
        }
        return $this->connection->quote($value);
    }

    /**
     * Reset the SQL Builder
     */
    public function reset()
    {
        $this->sqlParts->clear();
        return $this;
    }

    /**
     * Gets the SQL
     *
     * @return string
     */
    public function __toString()
    {
        return $this->sql();
    }
}
