<?php

namespace Koncept\DI\Tests\Objects;

use Koncept\DI\Tests\Objects\ZZZCircularZ as Target;


class ZZZCircularY
{
    public function __construct(Target $t) { }
}