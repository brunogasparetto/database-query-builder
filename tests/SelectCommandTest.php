<?php

namespace Database\QueryBuilder\Tests;

class SelectCommandTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     * @var Database\QueryBuilder\Builder\SelectCommand
     */
    private $command;

    private static $conn;

    public static function setUpBeforeClass()
    {
        self::$conn = new \PDO('sqlite:memory');
    }

    public function setUp()
    {
        $this->command = new Database\QueryBuilder\Builder\SelectCommand(self::$conn);
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

    public function testSelectEmpty()
    {
        $this->command->select();
        $this->assertEquals('SELECT *', (string) $this->command);

    }

    public function testSelectColumn()
    {
        $this->command->select('column');
        $this->assertEquals('SELECT column', (string) $this->command);
    }

    public function testSelectColumns()
    {
        $this->command->select('column1', 'column2');
        $this->assertEquals('SELECT column1, column2', (string) $this->command);
    }

    public function testSelectAdd()
    {
        $this->command->select('column1');
        $this->command->select('column2');
        $this->assertEquals('SELECT column1, column2', (string) $this->command);
    }

    public function testFrom()
    {
        $this->command->from('table');
        $this->assertEquals('SELECT * FROM table', (string) $this->command);
    }

    public function testFromModify()
    {
        $this->command->select('column')->from('table');
        $this->command->from('othertable');
        $this->assertEquals('SELECT column FROM othertable', (string) $this->command);
    }

    public function testLimit()
    {
        $this->command->select('column')->from('table')->limit(5);
        $this->assertEquals('SELECT column FROM table LIMIT 0, 5', (string) $this->command);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testLimitWrong()
    {
        $this->command->select('column')->from('table')->limit('hello');
    }

    public function testLimitOffset()
    {
        $this->command->select('column')->from('table')->limit(5, 10);
        $this->assertEquals('SELECT column FROM table LIMIT 10, 5', (string) $this->command);
    }

    public function testOrder()
    {
        $this->command->select('column')->from('table')->order('column');
        $this->assertEquals('SELECT column FROM table ORDER BY column ASC', (string) $this->command);
    }

    public function testOrderDesc()
    {
        $this->command->select('column')->from('table')->order('column', 'DESC');
        $this->assertEquals('SELECT column FROM table ORDER BY column DESC', (string) $this->command);
    }

    public function testSelectFromOrderLimit()
    {
        $this->command->select('column')->from('table')->order('column')->limit(5);
        $this->assertEquals('SELECT column FROM table ORDER BY column ASC LIMIT 0, 5', (string) $this->command);
    }

    public function testGroup()
    {
        $this->command->select('column')->from('table')->group('column');
        $this->assertEquals('SELECT column FROM table GROUP BY column', (string) $this->command);
    }

    public function testGroupColumns()
    {
        $this->command->select('column1', 'column2')->from('table')->group('column1', 'column2');
        $this->assertEquals('SELECT column1, column2 FROM table GROUP BY column1, column2', (string) $this->command);
    }

    public function testGroupAdd()
    {
        $this->command->select('column1', 'column2')->from('table')->group('column1')->group('column2');
        $this->assertEquals('SELECT column1, column2 FROM table GROUP BY column1, column2', (string) $this->command);
    }

    public function testSelectFromGroupOrderLimit()
    {
        $this->command
            ->select('column1', 'column2')
            ->from('table')
            ->group('column1', 'column2')
            ->order('column', 'DESC')
            ->limit(5, 10);

        $this->assertEquals(
            'SELECT column1, column2 FROM table GROUP BY column1, column2 ORDER BY column DESC LIMIT 10, 5',
            (string) $this->command
        );
    }

    public function testWhereNumber()
    {
        $this->command->select('column')->from('table')->where('id', '=', 5);
        $this->assertEquals(
            "SELECT column FROM table WHERE id = 5",
            (string) $this->command
        );
    }

    public function testWhereString()
    {
        $this->command->select('column')->from('table')->where('name', '=', 'Full Name');
        $this->assertEquals(
            "SELECT column FROM table WHERE name = 'Full Name'",
            (string) $this->command
        );
    }

    public function testWhereArray()
    {
        $this->command->select('column')->from('table')->where('id', 'IN', [1, 2, 3, 4, 5]);
        $this->assertEquals(
            "SELECT column FROM table WHERE id IN (1, 2, 3, 4, 5)",
            (string) $this->command
        );
    }

    public function testWhereBoolean()
    {
        $this->command->select('column')->from('table')->where('removed', '=', false);
        $this->assertEquals(
            "SELECT column FROM table WHERE removed = 0",
            (string) $this->command
        );
    }

    public function testWhereNull()
    {
        $this->command->select('column')->from('table')->where('email', '!=', null);
        $this->assertEquals(
            "SELECT column FROM table WHERE email IS NOT NULL",
            (string) $this->command
        );
    }

    public function testWhereSubquery()
    {
        $subQuery = new Database\QueryBuilder\Builder\SelectCommand(self::$conn);
        $subQuery->select('id')->from('subTable')->where('removed', '=', true);

        $this->command->select('column')->from('table')->where('id', 'IN', $subQuery);
        $this->assertEquals(
            "SELECT column FROM table WHERE id IN (SELECT id FROM subTable WHERE removed = 1)",
            (string) $this->command
        );
    }

    public function testWhereAnd()
    {
        $this->command->select('column')->from('table')->where('email', '!=', null)->where('age', '>=', 18);
        $this->assertEquals(
            "SELECT column FROM table WHERE email IS NOT NULL AND age >= 18",
            (string) $this->command
        );
    }

    public function testWhereOr()
    {
        $this->command->select('column')->from('table')->where('id', '=', 5)->whereOr('age', '>=', 18);
        $this->assertEquals(
            "SELECT column FROM table WHERE id = 5 OR age >= 18",
            (string) $this->command
        );
    }

    public function testWhereOpenClose()
    {
        $this->command
            ->select('column')
            ->from('table')
            ->whereOpen()
                ->where('column1', '=', 5)
                ->where('column2', '=', 'Name')
            ->whereClose()
            ->whereOrOpen()
                ->where('column1', '=', 10)
                ->where('column2', '=', 'Other Name')
            ->whereOrClose()
        ;

        $this->assertEquals(
            "SELECT column FROM table WHERE (column1 = 5 AND column2 = 'Name') OR (column1 = 10 AND column2 = 'Other Name')",
            (string) $this->command
        );
    }

    public function testHavingOpenClose()
    {
        $this->command
            ->select('column')
            ->from('table')
            ->whereOpen()
                ->where('column1', '=', 5)
                ->where('column2', '=', 'Name')
            ->whereClose()
            ->whereOrOpen()
                ->where('column1', '=', 10)
                ->where('column2', '=', 'Other Name')
            ->whereOrClose()
            ->havingOpen()
                ->having('column1', '=', 5)
                ->havingOr('column2', '!=', 'Name')
            ->havingClose()
            ->havingOpen()
                ->having('column3', '=', 15)
                ->havingOr('column4', '!=', 'Full Name')
            ->havingClose()
        ;

        $this->assertEquals(
            "SELECT column FROM table WHERE (column1 = 5 AND column2 = 'Name') OR (column1 = 10 AND column2 = 'Other Name') HAVING (column1 = 5 OR column2 != 'Name') AND (column3 = 15 OR column4 != 'Full Name')",
            (string) $this->command
        );
    }

    /**
     * @expectedException LogicException
     */
    public function testSelectFromJoinException()
    {
        $this->command->select()->on('column1', '=', 'column2');
    }

    public function testJoin()
    {
        $this->command
            ->select()
            ->from('table1')
            ->join('table2');

        $this->assertEquals(
            'SELECT * FROM table1 INNER JOIN table2',
            (string) $this->command
        );
    }

    public function testJoinOn()
    {
        $this->command
            ->select()
            ->from('table1')
            ->join('table2')
                ->on('table1.id', '=', 'table2.table1_id');

        $this->assertEquals(
            'SELECT * FROM table1 INNER JOIN table2 ON table1.id = table2.table1_id',
            (string) $this->command
        );
    }
}
