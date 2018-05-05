<?php

namespace Koncept\DI\Tests\TestCases;

use Koncept\DI\Exceptions\UnsupportedTypeException;
use Koncept\DI\Tests\Mocks\ZZZProviderMock;
use Koncept\DI\Tests\Mocks\ZZZProviderWithBuiltinTypeHintMock;
use Koncept\DI\Tests\Mocks\ZZZProviderWithNoReturnTypeMock;
use Koncept\DI\Tests\Mocks\ZZZProviderWithNullableTypeHintMock;
use Koncept\DI\Tests\Mocks\ZZZProviderWithRequiredParameterMock;
use Koncept\DI\Tests\Objects\ZZZObjectA;
use Koncept\DI\Tests\Objects\ZZZObjectB;
use Koncept\DI\Tests\Objects\ZZZObjectCDependingOnB;
use LogicException;
use PHPUnit\Framework\TestCase;
use stdClass;


class ZZZAutoProviderTest
    extends TestCase
{
    public function testBehavior()
    {
        $zpm = new ZZZProviderMock;

        $this->assertInstanceOf(ZZZObjectA::class, $oa1 = $zpm->get(ZZZObjectA::class));
        $this->assertInstanceOf(ZZZObjectA::class, $oa2 = $zpm->get(ZZZObjectA::class));
        $this->assertInstanceOf(ZZZObjectB::class, $ob1 = $zpm->get(ZZZObjectB::class));
        $this->assertInstanceOf(ZZZObjectB::class, $ob2 = $zpm->get(ZZZObjectB::class));
        $this->assertTrue($oa1 == $oa2);
        $this->assertTrue($oa1 !== $oa2);
        $this->assertTrue($ob1 == $ob2);
        $this->assertTrue($ob1 === $ob2);

        $this->expectException(UnsupportedTypeException::class);
        $zpm->get(ZZZObjectCDependingOnB::class);
    }

    public function testNoReturnType()
    {
        $zpm = new ZZZProviderWithNoReturnTypeMock;

        $this->expectException(LogicException::class);
        $zpm->get(stdClass::class);
    }

    public function testRequiredParameter()
    {
        $zpm = new ZZZProviderWithRequiredParameterMock;

        $this->expectException(LogicException::class);
        $zpm->get(stdClass::class);
    }

    public function testBuiltinTypeHint()
    {
        $zpm = new ZZZProviderWithBuiltinTypeHintMock;

        $this->expectException(LogicException::class);
        $zpm->get(stdClass::class);
    }

    public function testNullableTypeHint()
    {
        $zpm = new ZZZProviderWithNullableTypeHintMock;

        $this->expectException(LogicException::class);
        $zpm->get(stdClass::class);
    }
}