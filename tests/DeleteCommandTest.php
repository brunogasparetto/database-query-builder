<?php

namespace Database\QueryBuilder\Tests;

class DeleteCommandTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     * @var \Database\QueryBuilder\Builder\DeleteCommand
     */
    private $command;

    private static $conn;

    public static function setUpBeforeClass()
    {
        self::$conn = new \PDO('sqlite:memory');
    }

    public function setUp()
    {
        $this->command = new \Database\QueryBuilder\Builder\DeleteCommand(self::$conn);
    }

    public function testEmptySql()
    {
        $this->assertEquals('', (string) $this->command);
    }

    /**
     * @expectedException LogicException
     */
    public function testEmptySqlExecute()
    {
        $this->command->execute();
    }

    public function testFrom()
    {
        $this->command->from('table');
        $this->assertEquals('DELETE FROM table', (string) $this->command);
    }

    public function testWhere()
    {
        $this->command->from('table')->where('column', '=', 10);
        $this->assertEquals('DELETE FROM table WHERE column = 10', (string) $this->command);
    }

    public function testEmptyFrom()
    {
        $this->command->where('column', '=', 10);
        $this->assertEquals('', (string) $this->command);
    }

    public function testJoin()
    {
        $this->command->from('table')->join('othertable');
        $this->assertEquals('DELETE FROM table INNER JOIN othertable', (string) $this->command);
    }

    public function testJoinOn()
    {
        $this->command->from('table')->join('othertable')->on('table.id', '=', 'othertable.table_id');
        $this->assertEquals(
            'DELETE FROM table INNER JOIN othertable ON table.id = othertable.table_id',
            (string) $this->command
        );
    }

    public function testWhereJoinOn()
    {
        $this->command
            ->from('table')
            ->where('removed', '=', true)
            ->join('othertable')
                ->on('table.id', '=', 'othertable.table_id')
        ;
        $this->assertEquals(
            'DELETE FROM table INNER JOIN othertable ON table.id = othertable.table_id WHERE removed = 1',
            (string) $this->command
        );
    }

    public function testOrder()
    {
        $this->command->from('table')->order('id');
        $this->assertEquals('DELETE FROM table ORDER BY id ASC', (string) $this->command);
    }
    public function testLimit()
    {
        $this->command->from('table')->limit(5);
        $this->assertEquals('DELETE FROM table LIMIT 5', (string) $this->command);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testLimitWrong()
    {
        $this->command->from('table')->limit('hello');
    }

    public function testFromJoinOrderLimit()
    {
        $this->command
            ->from('table1')
            ->join('table2')
                ->on('table1.id', '=', 'table2.id')
            ->where('user', '=', 'name')
            ->order('column')
            ->limit(1)
        ;
        $this->assertEquals(
            "DELETE FROM table1 INNER JOIN table2 ON table1.id = table2.id WHERE user = 'name' ORDER BY column ASC LIMIT 1",
            (string) $this->command
        );
    }

    public function testWhereSubquery()
    {
        $subQuery = new \Database\QueryBuilder\Builder\SelectCommand(self::$conn);
        $subQuery->select('id')->from('othertable')->where('age', '<', 18);
        $this->command->from('table')->where('id', 'IN', $subQuery);
        $this->assertEquals(
            "DELETE FROM table WHERE id IN (SELECT id FROM othertable WHERE age < 18)",
            (string) $this->command
        );
    }
}
