<?php

namespace Koncept\DI\Tests\TestCases;

use Koncept\DI\Tests\Mocks\ZZZFiniteTypeMapMock;
use Koncept\DI\Tests\Objects\ZZZObjectA;
use Koncept\DI\Tests\Objects\ZZZObjectB;
use Koncept\DI\Tests\Objects\ZZZObjectCDependingOnB;
use Koncept\DI\Tests\Objects\ZZZObjectDExtendsB;
use PHPUnit\Framework\TestCase;


class ZZZFiniteTypeMapAbstractTest
    extends TestCase
{
    /** @var ZZZFiniteTypeMapMock */
    private $finiteTypeMap;

    public function setUp()
    {
        $this->finiteTypeMap = new ZZZFiniteTypeMapMock;
    }

    public function testSupport()
    {
        $this->assertTrue($this->finiteTypeMap->support(ZZZObjectA::class));
        $this->assertTrue($this->finiteTypeMap->support(ZZZObjectB::class));
        $this->assertFalse($this->finiteTypeMap->support(ZZZObjectCDependingOnB::class));
        $this->assertFalse($this->finiteTypeMap->support(ZZZObjectDExtendsB::class));
    }
}