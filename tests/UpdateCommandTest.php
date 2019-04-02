<?php

namespace Database\QueryBuilder\Tests;

class UpdateCommandTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     * @var \Database\QueryBuilder\Builder\UpdateCommand
     */
    private $command;

    private static $conn;

    public static function setUpBeforeClass()
    {
        self::$conn = new \PDO('sqlite:memory');
    }

    public function setUp()
    {
        $this->command = new \Database\QueryBuilder\Builder\UpdateCommand(self::$conn);
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

    public function testUpdateOnly()
    {
        $this->command->update('table');
        $this->assertEquals('', (string) $this->command);
    }

    public function testValueOnly()
    {
        $this->command->values('column', 'string');
        $this->assertEquals('', (string) $this->command);
    }

    public function testUpdateValue()
    {
        $this->command->update('table')->values('column', 'string');
        $this->assertEquals(
            "UPDATE table SET column = 'string'",
            (string) $this->command
        );
    }

    public function testUpdateValuesCascade()
    {
        $this->command->update('table')->values('column1', 'string')->values('column2', 10);
        $this->assertEquals(
            "UPDATE table SET column1 = 'string', column2 = 10",
            (string) $this->command
        );
    }

    public function testUpdateValuesArray()
    {
        $this->command->update('table')->values(['column1' => 'string', 'column2' => 10]);
        $this->assertEquals(
            "UPDATE table SET column1 = 'string', column2 = 10",
            (string) $this->command
        );
    }

    public function testWhere()
    {
        $this->command->update('table')->values('column', 'string')->where('id', '=', 20);
        $this->assertEquals(
            "UPDATE table SET column = 'string' WHERE id = 20",
            (string) $this->command
        );
    }

    public function testOrder()
    {
        $this->command->update('table')->values('column', 'string')->order('id');
        $this->assertEquals(
            "UPDATE table SET column = 'string' ORDER BY id ASC",
            (string) $this->command
        );
    }

    public function testLimit()
    {
        $this->command->update('table')->values('column', 'string')->limit(5);
        $this->assertEquals(
            "UPDATE table SET column = 'string' LIMIT 5",
            (string) $this->command
        );
    }

    public function testJoin()
    {
        $this->command
            ->update('table1')
            ->join('table2')
                ->on('table1.id', '=', 'table2.table1_id')
            ->values('column', 'string')
            ->limit(5);
        $this->assertEquals(
            "UPDATE table1 INNER JOIN table2 ON table1.id = table2.table1_id SET column = 'string' LIMIT 5",
            (string) $this->command
        );
    }

    public function testExpr()
    {
        $this->command
            ->update('table1')
            ->values('column', new \Database\QueryBuilder\Builder\Clause\Expr('column + 1'))
            ->where('column', '>', 10);
        $this->assertEquals(
            "UPDATE table1 SET column = column + 1 WHERE column > 10",
            (string) $this->command
        );
    }
}
