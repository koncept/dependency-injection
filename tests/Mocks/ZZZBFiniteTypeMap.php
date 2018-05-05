<?php

namespace Koncept\DI\Tests\Mocks;

use Koncept\DI\Base\FiniteTypeMapAbstract;
use Koncept\DI\Tests\Objects\ZZZObjectB;
use Strict\Collection\Vector\Scalar\Vector_string;


class ZZZBFiniteTypeMap
    extends FiniteTypeMapAbstract
{

    /**
     * Return the list of supported types.
     *
     * @return Vector_string
     */
    public function getList(): Vector_string
    {
        return new Vector_string(ZZZObjectB::class);
    }

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
        return new ZZZObjectB;
    }
}