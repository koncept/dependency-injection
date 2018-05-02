<?php

namespace Koncept\DI\Tests\TestCases;

use Koncept\DI\Exceptions\IncompatibleTypeException;
use Koncept\DI\Exceptions\NonexistentTypeException;
use Koncept\DI\Exceptions\UnsupportedTypeException;
use Koncept\DI\Tests\Objects\ZZZObjectA;
use Koncept\DI\Tests\Objects\ZZZObjectB;
use Koncept\DI\Tests\Objects\ZZZObjectCDependingOnB;
use Koncept\DI\Tests\Objects\ZZZObjectDExtendsB;
use Koncept\DI\Utility\ObjectContainer;
use PHPUnit\Framework\TestCase;


class ZZZObjectContainerTest
    extends TestCase
{
    /** @var ObjectContainer */
    private $objectContainer;

    public function setUp()
    {
        $this->objectContainer = new ObjectContainer(new ZZZObjectA);
    }

    public function testNullInitializer()
    {
        $oc = new ObjectContainer;

        $this->expectException(UnsupportedTypeException::class);
        $oc->get(ZZZObjectA::class);
    }

    public function testBehavior()
    {
        $this->assertTrue($this->objectContainer->support(ZZZObjectA::class));
        $this->assertFalse($this->objectContainer->support(ZZZObjectB::class));

        $this->assertInstanceOf(ZZZObjectA::class, $this->objectContainer->get(ZZZObjectA::class));
    }

    public function testWith()
    {
        $oc = $this->objectContainer
            ->with(new ZZZObjectCDependingOnB(new ZZZObjectB))
            ->with(new ZZZObjectDExtendsB, ZZZObjectB::class);

        $this->assertTrue($oc->support(ZZZObjectA::class));
        $this->assertTrue($oc->support(ZZZObjectB::class));
        $this->assertTrue($oc->support(ZZZObjectCDependingOnB::class));
        $this->assertFalse($oc->support(ZZZObjectDExtendsB::class));

        $this->assertInstanceOf(ZZZObjectA::class, $oc->get(ZZZObjectA::class));
        $this->assertInstanceOf(ZZZObjectB::class, $oc->get(ZZZObjectB::class));
        $this->assertInstanceOf(ZZZObjectDExtendsB::class, $oc->get(ZZZObjectB::class));
        $this->assertInstanceOf(ZZZObjectCDependingOnB::class, $oc->get(ZZZObjectCDependingOnB::class));
    }

    public function testIncompatibleWith()
    {
        $this->expectException(IncompatibleTypeException::class);
        $this->objectContainer
            ->with(new ZZZObjectCDependingOnB(new ZZZObjectB), ZZZObjectB::class);
    }

    public function testNonexistentType()
    {
        $this->expectException(NonexistentTypeException::class);
        $this->objectContainer
            ->with(new ZZZObjectDExtendsB(), 'INVALID_CLASS');
    }
}