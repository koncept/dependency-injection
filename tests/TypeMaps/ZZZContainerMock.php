<?php

namespace Koncept\DI\Tests\TypeMaps;

use Koncept\DI\Tests\Objects\ZZZObjectA;
use Koncept\DI\Tests\Objects\ZZZObjectB;
use Koncept\DI\Tests\Objects\ZZZObjectCDependingOnB;
use Koncept\DI\Tests\Objects\ZZZObjectDExtendingB;
use Koncept\DI\Utility\Container;


class ZZZContainerMock
    extends Container
{
    private function provideObjectA(): ZZZObjectA
    {
        return new ZZZObjectA();
    }

    private function createObjectB(): ZZZObjectB
    {
        return new ZZZObjectDExtendingB();
    }

    private function createObjectC(ZZZObjectB $b): ZZZObjectCDependingOnB
    {
        return new ZZZObjectCDependingOnB($b);
    }
}