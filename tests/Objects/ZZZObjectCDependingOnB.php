<?php

namespace Koncept\DI\Tests\Objects;


class ZZZObjectCDependingOnB
{
    public function __construct(ZZZObjectB $b)
    {
    }
}