<?php

namespace Koncept\DI\Tests\TypeMaps;

use Koncept\DI\Tests\Objects\ZZZObjectA;
use Koncept\DI\Tests\Objects\ZZZObjectB;
use Koncept\DI\Tests\Objects\ZZZObjectCDependingOnB;
use Koncept\DI\Utility\Container;


class ZZZContainerWithCircularDependency
    extends Container
{
    public function provideA(ZZZObjectCDependingOnB $c): ZZZObjectA
    {
        return new ZZZObjectA;
    }

    public function provideB(ZZZObjectA $a): ZZZObjectB
    {
        return new ZZZObjectB;
    }

    private function provideObjectC(ZZZObjectB $b): ZZZObjectCDependingOnB
    {
        return new ZZZObjectCDependingOnB($b);
    }
}