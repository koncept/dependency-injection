<?php

namespace Koncept\DI\Tests\Mocks;

use Koncept\DI\Base\FiniteTypeMapAbstract;
use Koncept\DI\Tests\Objects\ZZZObjectA;
use Koncept\DI\Tests\Objects\ZZZObjectB;
use Koncept\DI\Tests\Objects\ZZZObjectCDependingOnB;
use Koncept\DI\Utility\AutoProviderTrait;


class ZZZProviderMock
    extends FiniteTypeMapAbstract
{
    use AutoProviderTrait;

    protected function createObjectA(): ZZZObjectA
    {
        return new ZZZObjectA;
    }

    protected function provideObjectB(): ZZZObjectB
    {
        return new ZZZObjectB;
    }

    // Invalid
    protected function xObjectC(): ZZZObjectCDependingOnB
    {
        return new ZZZObjectCDependingOnB(new ZZZObjectB);
    }
}