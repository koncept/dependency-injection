<?php

namespace Koncept\DI\Tests\Mocks;

use Koncept\DI\Base\FiniteTypeMapAbstract;
use Koncept\DI\Utility\AutoProviderTrait;
use stdClass;


class ZZZProviderWithNullableTypeHintMock
    extends FiniteTypeMapAbstract
{
    use AutoProviderTrait;

    protected function createSomething(): ?stdClass
    {
        return (object)[];
    }
}