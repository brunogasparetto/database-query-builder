<?php

namespace Database\QueryBuilder\Tests;

class InsertCommandTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     * @var \Database\QueryBuilder\Builder\InsertCommand
     */
    private $command;

    private static $conn;

    public static function setUpBeforeClass()
    {
        self::$conn = new \PDO('sqlite:memory');
    }

    public function setUp()
    {
        $this->command = new \Database\QueryBuilder\Builder\InsertCommand(self::$conn);
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

    public function testOnlyInto()
    {
        $this->command->into('table');
        $this->assertEquals('', (string) $this->command);
    }

    public function testOnlyValue()
    {
        $this->command->values(['column' => 10]);
        $this->assertEquals('', (string) $this->command);
    }

    public function testIntoValue()
    {
        $this->command->into('table')->values(['column' => 10]);
        $this->assertEquals('INSERT INTO table (column) VALUES (10)', (string) $this->command);
    }

    public function testIntoValues()
    {
        $this->command->into('table')->values(['column1' => 10, 'column2' => 'name']);
        $this->assertEquals(
            "INSERT INTO table (column1, column2) VALUES (10, 'name')",
            (string) $this->command
        );
    }

    public function testIntoValuesCascade()
    {
        $this->command->into('table')->values(['column1' => 10])->values(['column2' => null]);
        $this->assertEquals(
            "INSERT INTO table (column1, column2) VALUES (10, NULL)",
            (string) $this->command
        );
    }

    public function testDuplicate()
    {
        $this->command->into('table')->values(['column' => 'value'])->onDuplicate('column', new \Database\QueryBuilder\Builder\Clause\Expr('column + 1'));
        $this->assertEquals(
            "INSERT INTO table (column) VALUES ('value') ON DUPLICATE KEY UPDATE column = column + 1",
            (string) $this->command
        );
    }

    public function testDuplicateValue()
    {
        $this->command
            ->into('table')
            ->values(['column1' => 'value1', 'column2' => 'value2'])
            ->onDuplicate('column1', 'string');

        $this->assertEquals(
            "INSERT INTO table (column1, column2) VALUES ('value1', 'value2') ON DUPLICATE KEY UPDATE column1 = 'string'",
            (string) $this->command
        );
    }

    public function testDuplicateCascade()
    {
        $this->command
            ->into('table')
            ->values(['column1' => 'value1', 'column2' => 'value2'])
            ->onDuplicate('column1', new \Database\QueryBuilder\Builder\Clause\Expr('column1 + 1'))
            ->onDuplicate('column2', new \Database\QueryBuilder\Builder\Clause\Expr('column2 * 2'));

        $this->assertEquals(
            "INSERT INTO table (column1, column2) VALUES ('value1', 'value2') ON DUPLICATE KEY UPDATE column1 = column1 + 1, column2 = column2 * 2",
            (string) $this->command
        );
    }

    public function testDuplicateArray()
    {
        $this->command
            ->into('table')
            ->values(['column1' => 'value1', 'column2' => 'value2'])
            ->onDuplicate([
                'column1' => new \Database\QueryBuilder\Builder\Clause\Expr('column1 + 1'),
                'column2' => new \Database\QueryBuilder\Builder\Clause\Expr('column2 * 2')
                ]);

        $this->assertEquals(
            "INSERT INTO table (column1, column2) VALUES ('value1', 'value2') ON DUPLICATE KEY UPDATE column1 = column1 + 1, column2 = column2 * 2",
            (string) $this->command
        );
    }
}
