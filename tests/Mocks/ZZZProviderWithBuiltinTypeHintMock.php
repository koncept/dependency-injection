<?php

namespace Koncept\DI\Tests\Mocks;

use Koncept\DI\Base\FiniteTypeMapAbstract;
use Koncept\DI\Utility\AutoProviderTrait;
use stdClass;


class ZZZProviderWithBuiltinTypeHintMock
    extends FiniteTypeMapAbstract
{
    use AutoProviderTrait;

    protected function createSomething(): int
    {
        return 3;
    }
}