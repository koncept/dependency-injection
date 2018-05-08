<?php

namespace Koncept\DI\Tests\TestCases;

use Koncept\DI\Exceptions\CircularDependencyException;
use Koncept\DI\Tests\Objects\ZZZCircularX;
use Koncept\DI\Tests\Objects\ZZZObjectB;
use Koncept\DI\Tests\Objects\ZZZObjectCDependingOnB;
use Koncept\DI\Utility\ObjectContainer;
use Koncept\DI\Utility\RecursiveFactory;
use PHPUnit\Framework\TestCase;


class ZZZRecursiveFactoryTest
    extends TestCase
{
    public function testBehavior()
    {
        $fact = new RecursiveFactory(new ObjectContainer(new ZZZObjectB));

        $this->assertInstanceOf(ZZZObjectCDependingOnB::class, $fact->get(ZZZObjectCDependingOnB::class));

        $this->expectException(CircularDependencyException::class);
        $fact->get(ZZZCircularX::class);
    }
}