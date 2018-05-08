<?php

namespace Koncept\DI\Tests\TestCases;

use Koncept\DI\Exceptions\IncompatibleTypeException;
use Koncept\DI\Exceptions\NonexistentTypeException;
use Koncept\DI\Exceptions\UnsupportedTypeException;
use Koncept\DI\Tests\Objects\ZZZObjectA;
use Koncept\DI\Tests\Objects\ZZZObjectB;
use Koncept\DI\Tests\Objects\ZZZObjectCDependingOnB;
use Koncept\DI\Tests\Objects\ZZZObjectDExtendingB;
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
        $this->assertTrue($this->objectContainer->supports(ZZZObjectA::class));
        $this->assertFalse($this->objectContainer->supports(ZZZObjectB::class));

        $this->assertInstanceOf(ZZZObjectA::class, $this->objectContainer->get(ZZZObjectA::class));
    }

    public function testWith()
    {
        $oc = $this->objectContainer
            ->with(new ZZZObjectCDependingOnB(new ZZZObjectB))
            ->with(new ZZZObjectDExtendingB, ZZZObjectB::class);

        $this->assertTrue($oc->supports(ZZZObjectA::class));
        $this->assertTrue($oc->supports(ZZZObjectB::class));
        $this->assertTrue($oc->supports(ZZZObjectCDependingOnB::class));
        $this->assertFalse($oc->supports(ZZZObjectDExtendingB::class));

        $this->assertInstanceOf(ZZZObjectA::class, $oc->get(ZZZObjectA::class));
        $this->assertInstanceOf(ZZZObjectB::class, $oc->get(ZZZObjectB::class));
        $this->assertInstanceOf(ZZZObjectDExtendingB::class, $oc->get(ZZZObjectB::class));
        $this->assertInstanceOf(ZZZObjectCDependingOnB::class, $oc->get(ZZZObjectCDependingOnB::class));
    }

    public function testMerge()
    {
        $oc1 = (new ObjectContainer(new ZZZObjectA))
            ->with(new ZZZObjectB);

        $oc2 = (new ObjectContainer(new ZZZObjectCDependingOnB(new ZZZObjectB)))
            ->with(new ZZZObjectDExtendingB, ZZZObjectB::class);

        $merged = ObjectContainer::Merge($oc1, $oc2);

        $this->assertTrue($merged->supports(ZZZObjectA::class));
        $this->assertTrue($merged->supports(ZZZObjectB::class));
        $this->assertTrue($merged->supports(ZZZObjectCDependingOnB::class));
        $this->assertFalse($merged->supports(ZZZObjectDExtendingB::class));

        $this->assertInstanceOf(ZZZObjectA::class, $merged->get(ZZZObjectA::class));
        $this->assertInstanceOf(ZZZObjectB::class, $merged->get(ZZZObjectB::class));
        $this->assertInstanceOf(ZZZObjectCDependingOnB::class, $merged->get(ZZZObjectCDependingOnB::class));
        $this->assertInstanceOf(ZZZObjectDExtendingB::class, $merged->get(ZZZObjectB::class));
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
            ->with(new ZZZObjectDExtendingB(), 'INVALID_CLASS');
    }
}