<?php

namespace QueryBuilder\Tests;

use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    /**
     *
     * @var \QueryBuilder\Database
     */
    private static $db;

    public static function setUpBeforeClass()
    {
        self::$db = new \QueryBuilder\Database(['dsn' => 'sqlite::memory:']);
    }

    public function testSelect()
    {
        $this->assertInstanceOf('\QueryBuilder\Builder\SelectCommand', self::$db->select());
    }

    public function testUpdate()
    {
        $this->assertInstanceOf('\QueryBuilder\Builder\UpdateCommand', self::$db->update('table'));
    }

    public function testDelete()
    {
        $this->assertInstanceOf('\QueryBuilder\Builder\DeleteCommand', self::$db->delete('table'));
    }

    public function testInsert()
    {
        $this->assertInstanceOf('\QueryBuilder\Builder\InsertCommand', self::$db->insert('table'));
    }

    public function testExpr()
    {
        $this->assertInstanceOf('\QueryBuilder\Builder\Clause\Expr', self::$db->expr('COUNT(*) AS total'));
    }
}
