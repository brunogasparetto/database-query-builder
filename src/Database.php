<?php

namespace QueryBuilder;

/**
 * The factory to Query Builder commands
 */
class Database
{
    /**
     * @var \PDO
     */
    private $conn;

    /**
     * @var int
     */
    private $fetchMode = \PDO::FETCH_OBJ;

    /**
     * @param array $config Associative array with user, password and dsn or driver, host, dbname and charset keys.
     * @throws \OutOfRangeException
     * @throws \PDOException
     */
    public function __construct(array $config)
    {
        $dsn = empty($config['dsn']) ? $this->dsn($config) : $config['dsn'];
        $user = empty($config['user']) ? '' : $config['user'];
        $password = empty($config['password']) ? '' : $config['password'];

        $this->conn = new \PDO($dsn, $user, $password, []);

        if (isset($config['fetchMode']) and is_int($config['fetchMode'])) {
            $this->fetchMode = $config['fetchMode'];
        }
    }

    /**
     * Create the Select Builder
     *
     * @param string $columns
     * @return \QueryBuilder\Builder\SelectCommand
     */
    public function select(...$columns)
    {
        $command = new Builder\SelectCommand($this->conn, $this->fetchMode);
        return $command->select($columns);
    }

    /**
     * Create the Delete Builder
     *
     * @param string $table
     * @return \QueryBuilder\Builder\DeleteCommand
     */
    public function delete($table)
    {
        $command = new Builder\DeleteCommand($this->conn);
        return $command->from($table);
    }

    /**
     * Create the Update Builder
     *
     * @param string $table
     * @return \QueryBuilder\Builder\UpdateCommand
     */
    public function update($table)
    {
        $command = new Builder\UpdateCommand($this->conn);
        return $command->update($table);
    }

    /**
     * Create the Insert Builder
     *
     * @param string $table
     * @return \QueryBuilder\Builder\InsertCommand
     */
    public function insert($table)
    {
        $command = new Builder\InsertCommand($this->conn);
        return $command->into($table);
    }

    /**
     * A value used without quoting
     *
     * @param string $sql
     * @return \QueryBuilder\Builder\Clause\Expr
     */
    public function expr($sql)
    {
        return new Builder\Clause\Expr($sql);
    }

    /**
     * Execute a pure SQL
     *
     * @param string $sql
     * @return \PDOStatement
     */
    public function executeSQL($sql)
    {
        return $this->conn->query($sql);
    }

    /**
     * Mount the DSN
     *
     * @param array $config
     * @return string
     * @throws \OutOfRangeException
     */
    private function dsn(array $config)
    {
        $this->checkConfigFields($config);

        if ($config['driver'] === 'sqlsrv') {
            $hostKeyword = 'Server';
            $dbKeyword = 'Database';
            $charset = '';
        } else {
            $hostKeyword = 'host';
            $dbKeyword = 'dbname';
            $charset = isset($config['charset']) ? ";charset={$config['charset']}" : '';
        }

        return sprintf(
            '%s:%s=%s;%s=%s%s',
            $config['driver'],
            $hostKeyword,
            $config['host'],
            $dbKeyword,
            $config['dbname'],
            $charset
        );
    }

    /**
     * Checks the requireds fields for config
     *
     * @param array $config
     * @throws \OutOfRangeException
     */
    private function checkConfigFields(array $config)
    {
        foreach (['driver', 'host', 'dbname', 'user'] as $field) {
            if (empty($config[$field])) {
                throw new \OutOfRangeException("The {$field} in Database config is required.");
            }
        }
    }
}
