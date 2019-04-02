<?php

namespace Database\QueryBuilder\Tests;

class DatabaseTest extends PHPUnit_Framework_TestCase
{
    /**
     *
     * @var \Database\QueryBuilder\Database
     */
    private static $db;

    public static function setUpBeforeClass()
    {
        self::$db = new \Database\QueryBuilder\Database([
            'driver'   => 'sqlite',
            'host'     => 'localhost',
            'dbname'   => 'moodle',
            'charset'  => 'utf8',
            'user'     => 'root',
            'password' => 'vagrant'
        ]);
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
}
