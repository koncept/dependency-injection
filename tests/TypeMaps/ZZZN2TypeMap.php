<?php

namespace Koncept\DI\Tests\TypeMaps;

use Koncept\DI\Base\TypeMapAbstract;
use Koncept\DI\Tests\Objects\ZZZInterfaceN;
use Koncept\DI\Tests\Objects\ZZZObjectN2;


class ZZZN2TypeMap
    extends TypeMapAbstract
{
    /**
     * Acquire object of the type.
     *
     * This method is called inside get() after confirming that the type is supported.
     * So, there is no need to call support() at first in your implementation of this method.
     * In other words, assert($this->support($type)) always passes in this method.
     * Return null at unreachable code. Returning null causes LogicException to be thrown.
     *
     * @param string $type
     * @return null|object
     */
    protected function getObject(string $type): ?object
    {
        return new ZZZObjectN2;
    }

    /**
     * Return the type is supported or not.
     *
     * @param string $type
     * @return bool
     */
    public function supports(string $type): bool
    {
        return $type === ZZZInterfaceN::class;
    }
}