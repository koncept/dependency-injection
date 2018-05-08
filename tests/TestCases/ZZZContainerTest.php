<?php

namespace Koncept\DI\Tests\TestCases;

use Koncept\DI\Exceptions\CircularDependencyException;
use Koncept\DI\Exceptions\DuplicateProviderException;
use Koncept\DI\Tests\Objects\ZZZObjectA;
use Koncept\DI\Tests\Objects\ZZZObjectB;
use Koncept\DI\Tests\Objects\ZZZObjectCDependingOnB;
use Koncept\DI\Tests\Objects\ZZZObjectDExtendingB;
use Koncept\DI\Tests\TypeMaps\ZZZContainerMock;
use Koncept\DI\Tests\TypeMaps\ZZZContainerWithCircularDependency;
use Koncept\DI\Tests\TypeMaps\ZZZContainerWithDuplicateProviderMock;
use PHPUnit\Framework\TestCase;


class ZZZContainerTest
    extends TestCase
{
    public function testContainer()
    {
        $ctn = new ZZZContainerMock;
        $this->assertInstanceOf(ZZZObjectA::class, $ctn->get(ZZZObjectA::class));
        $this->assertInstanceOf(ZZZObjectB::class, $ctn->get(ZZZObjectB::class));
        $this->assertInstanceOf(ZZZObjectDExtendingB::class, $ctn->get(ZZZObjectB::class));
        $this->assertInstanceOf(ZZZObjectCDependingOnB::class, $ctn->get(ZZZObjectCDependingOnB::class));

        $a1 = $ctn->get(ZZZObjectA::class);
        $a2 = $ctn->get(ZZZObjectA::class);
        $b1 = $ctn->get(ZZZObjectB::class);
        $b2 = $ctn->get(ZZZObjectB::class);
        $this->assertTrue($a1 == $a2);
        $this->assertTrue($a1 === $a2);
        $this->assertTrue($b1 == $b2);
        $this->assertTrue($b1 !== $b2);
    }

    public function testDuplicate()
    {
        $this->expectException(DuplicateProviderException::class);
        (new ZZZContainerWithDuplicateProviderMock)->getList();
    }

    public function testCircular()
    {
        $this->expectException(CircularDependencyException::class);
        (new ZZZContainerWithCircularDependency)->get(ZZZObjectA::class);
    }
}