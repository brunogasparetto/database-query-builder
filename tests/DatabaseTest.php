<?php

namespace Database\QueryBuilder\Tests;

use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    /**
     *
     * @var \Database\QueryBuilder\Database
     */
    private static $db;

    public static function setUpBeforeClass()
    {
        self::$db = new \Database\QueryBuilder\Database(['dsn' => 'sqlite::memory:']);
    }

    public function testSelect()
    {
        $this->assertInstanceOf('\Database\QueryBuilder\Builder\SelectCommand', self::$db->select());
    }

    public function testUpdate()
    {
        $this->assertInstanceOf('\Database\QueryBuilder\Builder\UpdateCommand', self::$db->update('table'));
    }

    public function testDelete()
    {
        $this->assertInstanceOf('\Database\QueryBuilder\Builder\DeleteCommand', self::$db->delete('table'));
    }

    public function testInsert()
    {
        $this->assertInstanceOf('\Database\QueryBuilder\Builder\InsertCommand', self::$db->insert('table'));
    }

    public function testExpr()
    {
        $this->assertInstanceOf('\Database\QueryBuilder\Builder\Clause\Expr', self::$db->expr('COUNT(*) AS total'));
    }
}
