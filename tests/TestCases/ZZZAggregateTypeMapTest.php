<?php

namespace Koncept\DI\Tests\TestCases;

use Koncept\DI\Exceptions\UnsupportedTypeException;
use Koncept\DI\Tests\TypeMaps\ZZZBFiniteTypeMap;
use Koncept\DI\Tests\TypeMaps\ZZZDFiniteTypeMap;
use Koncept\DI\Tests\TypeMaps\ZZZN1TypeMap;
use Koncept\DI\Tests\TypeMaps\ZZZN2TypeMap;
use Koncept\DI\Tests\Objects\ZZZInterfaceN;
use Koncept\DI\Tests\Objects\ZZZObjectA;
use Koncept\DI\Tests\Objects\ZZZObjectB;
use Koncept\DI\Tests\Objects\ZZZObjectDExtendingB;
use Koncept\DI\Tests\Objects\ZZZObjectN1;
use Koncept\DI\Tests\Objects\ZZZObjectN2;
use Koncept\DI\Utility\AggregateTypeMap;
use PHPUnit\Framework\TestCase;


class ZZZAggregateTypeMapTest
    extends TestCase
{
    /** @var AggregateTypeMap */
    private $atm1, $atm2;

    public function setUp()
    {
        $this->atm1 = new AggregateTypeMap(new ZZZBFiniteTypeMap, new ZZZN1TypeMap);
        $this->atm2 = new AggregateTypeMap(new ZZZDFiniteTypeMap, new ZZZN2TypeMap);
    }

    public function testGet()
    {
        $this->assertInstanceOf(ZZZObjectB::class, $this->atm1->get(ZZZObjectB::class));
        $this->assertInstanceOf(ZZZInterfaceN::class, $this->atm1->get(ZZZInterfaceN::class));

        $this->expectException(UnsupportedTypeException::class);
        $this->atm1->get(ZZZObjectA::class);
    }

    public function testAggregate()
    {
        $a12 = new AggregateTypeMap($this->atm1, $this->atm2);
        $this->assertInstanceOf(ZZZObjectDExtendingB::class, $a12->get(ZZZObjectB::class));
        $this->assertInstanceOf(ZZZObjectN2::class, $a12->get(ZZZInterfaceN::class));

        $a12 = $this->atm1->withTypeMap($this->atm2);
        $this->assertInstanceOf(ZZZObjectDExtendingB::class, $a12->get(ZZZObjectB::class));
        $this->assertInstanceOf(ZZZObjectN2::class, $a12->get(ZZZInterfaceN::class));

        $a21 = new AggregateTypeMap($this->atm2, $this->atm1);
        $this->assertNotInstanceOf(ZZZObjectDExtendingB::class, $a21->get(ZZZObjectB::class));
        $this->assertInstanceOf(ZZZObjectN1::class, $a21->get(ZZZInterfaceN::class));

        $a21 = $this->atm2->withTypeMap($this->atm1);
        $this->assertNotInstanceOf(ZZZObjectDExtendingB::class, $a21->get(ZZZObjectB::class));
        $this->assertInstanceOf(ZZZObjectN1::class, $a21->get(ZZZInterfaceN::class));

        $a3 = $this->atm1->withObject(new ZZZObjectA);
        $this->assertInstanceOf(ZZZObjectA::class, $a3->get(ZZZObjectA::class));
    }
}