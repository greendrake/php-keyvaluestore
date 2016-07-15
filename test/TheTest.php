<?php

namespace Greendrake\KeyValueStore;

use PHPUnit_Framework_TestCase;

class TheTest extends PHPUnit_Framework_TestCase
{

    public function testBasics()
    {
        $store = new Store;
        $key = 'foo';
        $this->assertNull($store->get($key));
        $value = 'bar';
        $store->set($key, $value);
        $this->assertEquals($value, $store->get($key));
        $store->flush();
    }

    public function testPrefixes()
    {
        $s1 = new Store;
        $s1->setPrefix('p1');
        $s2 = clone $s1;
        $s2->setPrefix('p2');

        $key = 'foo';
        $this->assertNull($s1->get($key));
        $this->assertNull($s2->get($key));
        $value1 = 'bar';
        $s1->set($key, $value1);
        $this->assertEquals($value1, $s1->get($key));
        $this->assertNull($s2->get($key));
        $value2 = 'bar2';
        $s2->set($key, $value2);
        $this->assertEquals($value1, $s1->get($key));
        $this->assertEquals($value2, $s2->get($key));
        $s1->flush();
        $this->assertNull($s1->get($key));
        $this->assertEquals($value2, $s2->get($key));
        $s2->flush();
        $this->assertNull($s2->get($key));
    }

    public function testIncr()
    {
        $s = new Store;
        $s->setPrefix('incr');
        $this->assertEquals(1, $s->incr());
        $this->assertEquals(2, $s->incr());
        $this->assertEquals(1, $s->incr('other'));
    }

}