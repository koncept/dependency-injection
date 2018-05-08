<?php

namespace Koncept\DI\Tests\Objects;

use Koncept\DI\Tests\Objects\ZZZCircularY as Target;


class ZZZCircularZ
{
    public function __construct(Target $t) { }
}