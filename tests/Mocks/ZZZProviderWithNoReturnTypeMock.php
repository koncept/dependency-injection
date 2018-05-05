<?php

namespace Koncept\DI\Tests\Mocks;

use Koncept\DI\Base\FiniteTypeMapAbstract;
use Koncept\DI\Utility\AutoProviderTrait;


class ZZZProviderWithNoReturnTypeMock
    extends FiniteTypeMapAbstract
{
    use AutoProviderTrait;

    protected function createSomething()
    {
        return (object)[];
    }
}