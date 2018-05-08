<?php

namespace Koncept\DI\Tests\TypeMaps;

use Koncept\DI\Tests\Objects\ZZZObjectA;
use Koncept\DI\Tests\Objects\ZZZObjectB;
use Koncept\DI\Tests\Objects\ZZZObjectCDependingOnB;
use Koncept\DI\Tests\Objects\ZZZObjectDExtendsB;
use Koncept\DI\Base\TypeMapAbstract;


class ZZZTypeMapMock
    extends TypeMapAbstract
{

    /**
     * Acquire object of the type.
     *
     * This method is called inside get() after confirming that the type is supported.
     * So, there is no need to call support() at first in your implementation of this method.
     * In other words, assert($this->support($type)) always passes in this method.
     *
     * @param string $type
     * @return object
     */
    protected function getObject(string $type): object
    {
        if ($type === ZZZObjectA::class) {
            return new ZZZObjectA;
        }
        if ($type === ZZZObjectB::class) {
            return new ZZZObjectDExtendsB;
        }

        // expected: ZZZObjectCDependingOnB
        return new ZZZObjectA;
    }

    /**
     * Return the type is supported or not.
     *
     * @param string $type
     * @return bool
     */
    public function supports(string $type): bool
    {
        $candidates = [
            ZZZObjectA::class             => true,
            ZZZObjectB::class             => true,
            ZZZObjectCDependingOnB::class => true,
            'INVALID_CLASS'               => true,
        ];
        return isset($candidates[$type]);
    }
}