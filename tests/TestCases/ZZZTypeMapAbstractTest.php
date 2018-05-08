<?php

namespace Koncept\DI\Tests\TestCases;

use Koncept\DI\Exceptions\NonexistentTypeException;
use Koncept\DI\Exceptions\UnsupportedTypeException;
use Koncept\DI\Tests\TypeMaps\ZZZTypeMapMock;
use Koncept\DI\Tests\Objects\ZZZObjectA;
use Koncept\DI\Tests\Objects\ZZZObjectB;
use Koncept\DI\Tests\Objects\ZZZObjectCDependingOnB;
use Koncept\DI\Tests\Objects\ZZZObjectDExtendsB;
use PHPUnit\Framework\TestCase;
use stdClass;
use TypeError;


class ZZZTypeMapAbstractTest
    extends TestCase
{
    /** @var ZZZTypeMapMock */
    private $typeMap;

    public function setUp()
    {
        $this->typeMap = new ZZZTypeMapMock;
    }

    public function testGetA()
    {
        $this->assertInstanceOf(ZZZObjectA::class, $this->typeMap->get(ZZZObjectA::class));
    }

    public function testGetB()
    {
        $this->assertInstanceOf(ZZZObjectB::class, $this->typeMap->get(ZZZObjectB::class));
        $this->assertInstanceOf(ZZZObjectDExtendsB::class, $this->typeMap->get(ZZZObjectB::class));
    }

    public function testGetC()
    {
        $this->expectException(TypeError::class);
        $this->typeMap->get(ZZZObjectCDependingOnB::class);
    }

    public function testGetInvalid()
    {
        $this->expectException(NonexistentTypeException::class);
        $this->typeMap->get('INVALID_CLASS');
    }

    public function testUnsupported()
    {
        $this->assertFalse($this->typeMap->supports(stdClass::class));
        $this->expectException(UnsupportedTypeException::class);
        $this->typeMap->get(stdClass::class);
    }
}