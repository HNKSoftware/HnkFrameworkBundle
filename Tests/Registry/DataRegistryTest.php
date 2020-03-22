<?php

namespace Hnk\HnkFrameworkBundle\Tests\Registry;

use Hnk\HnkFrameworkBundle\Exception\HnkException;
use Hnk\HnkFrameworkBundle\Registry\DataRegistry;
use PHPUnit\Framework\TestCase;

class DataRegistryTest extends TestCase
{
    public function testRegistry()
    {
        $registry = new DataRegistry();

        $this->assertFalse($registry->isStored("undefined key"), "is stored on undefined key");

        $registry->set("key1", 1);
        $this->assertEquals(1, $registry->get("key1"), "test get 1");
        $this->assertTrue($registry->isStored("key1"), "test is stored 1");

        $value2 = ["field1" => true, "field2" => new \DateTime()];
        $registry->set("key2", $value2);
        $this->assertEquals($value2, $registry->get("key2"), "test get 2");

        $registry->set("key3", null);
        $this->assertFalse($registry->isStored("key3"), "is stored with default strict on null value");
        $this->assertTrue($registry->isStored("key3", false), "is stored without strict on null value");
        $this->assertFalse($registry->isStored("key3", true), "is stored with strict on null value");
    }

    public function testSetWithoutOverride()
    {
        $this->expectException(HnkException::class);
        $this->expectExceptionMessage("Key key already exists in registry");

        $registry = new DataRegistry();

        $registry->set("key", 1);
        $registry->set("key", 2);
    }

    public function testSetWithOverride()
    {
        $registry = new DataRegistry();

        $registry->set("key", 1);
        $registry->set("key", 2, true);

        $this->assertEquals(2, $registry->get("key"));
    }
}
