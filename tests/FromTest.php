<?php

namespace Database\QueryBuilder\Tests;

class FromTest extends PHPUnit_Framework_TestCase
{
    public function testEmpty()
    {
        $from = new Database\QueryBuilder\Builder\From();
        $this->assertEquals('', (string) $from);
    }

    public function testValue()
    {
        $from = new Database\QueryBuilder\Builder\From();
        $from->set('tabela');
        $this->assertEquals('FROM tabela', (string) $from);
    }
}
