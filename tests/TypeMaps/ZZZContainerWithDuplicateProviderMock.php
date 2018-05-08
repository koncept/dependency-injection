<?php

namespace Koncept\DI\Tests\TypeMaps;

use Koncept\DI\Utility\Container;
use stdClass;


class ZZZContainerWithDuplicateProviderMock
    extends Container
{
    protected function provideA(): stdClass { }

    protected function createA(): stdClass { }
}