<?php

namespace QueryBuilder\Builder;

class SelectCommand extends Builder
{
    use Traits\From;
    use Traits\Join;
    use Traits\Where;
    use Traits\Order;
    use Traits\Limit;

    /**
     * The fetch mode must be one of the PDO::FETCH_* constants.
     *
     * @var int
     */
    private $fetchMode;

    /**
     *
     * @param \PDO $conn
     * @param int $fetchMode The fetch mode must be one of the PDO::FETCH_* constants.
     */
    public function __construct(\PDO $conn, $fetchMode = \PDO::FETCH_OBJ)
    {
        parent::__construct($conn);
        $this->fetchMode = $fetchMode;
    }

    /**
     * Set the columns to retrieve.
     * Accept a variable list of arguments.
     *
     * @param  mixed $columns An array of columns or lenght variabel arguments, both with strings
     * @return self
     */
    public function select($columns = null)
    {
        if (!is_array($columns)) {
            $columns = func_get_args();
        }
        !isset($this->sqlParts->select) and $this->sqlParts->select = new Clause\Select();
        $this->sqlParts->select->set($columns);
        return $this;
    }

    /**
     * Set the Order By
     *
     * Accept multiple columns as parameters.
     * Each call adds columns.
     *
     * @param  mixed $columns An array of columns or lenght variabel arguments, both with strings
     * @return self
     */
    public function group($columns)
    {
        if (!is_array($columns)) {
            $columns = func_get_args();
        }
        !isset($this->sqlParts->group) and $this->sqlParts->group = new Clause\Group();
        $this->sqlParts->group->set($columns);
        return $this;
    }

    /**
     * Create the having's parts
     *
     * @return void
     */
    protected function setUpHaving()
    {
        !isset($this->sqlParts->having)
        and $this->sqlParts->having = new \QueryBuilder\Builder\Clause\Having($this->connection);
    }

    /**
     * Having clause
     *
     * @param  string $column
     * @param  string $op
     * @param  mixed $value
     * @return self
     */
    public function having($column, $op, $value)
    {
        $this->setUpHaving();
        $this->sqlParts['having']->set('AND', $column, $op, $value);
        return $this;
    }

    /**
     * Having OR clause
     *
     * @param  string $column
     * @param  string $op
     * @param  mixed $value
     * @return self
     */
    public function havingOr($column, $op, $value)
    {
        $this->setUpHaving();
        $this->sqlParts['having']->set('OR', $column, $op, $value);
        return $this;
    }

    /**
     * Open a group of having clause
     *
     * @return self
     */
    public function havingOpen()
    {
        return $this->having('(', null, null);
    }

    /**
     * Close a group of having clause
     *
     * @return self
     */
    public function havingClose()
    {
        return $this->having(')', null, null);
    }

    /**
     * Open a group of having clause with OR
     *
     * @return self
     */
    public function havingOrOpen()
    {
        return $this->havingOr('(', null, null);
    }

    /**
     * Close a group of having clause with OR
     *
     * @return self
     */
    public function havingOrClose()
    {
        return $this->havingOr(')', null, null);
    }

    /**
     * Get the SQL
     *
     * @return string
     */
    public function sql()
    {
        if (!count($this->sqlParts)) {
            return '';
        }

        if (!isset($this->sqlParts->select)) {
            $this->select();
        }

        $sql = '';

        foreach (['select', 'from', 'join', 'where', 'group', 'having', 'order', 'limit'] as $part) {
            if (isset($this->sqlParts->$part)) {
                $sql .= ' ' . $this->sqlParts->$part->sql();
            }
        }

        return ltrim($sql);
    }

    /**
     * Execute the SQL
     *
     * @throws \LogicException
     * @return \PDOStatement
     */
    public function execute()
    {
        $result = parent::execute();
        $result->setFetchMode($this->fetchMode);
        return $result;
    }
}
