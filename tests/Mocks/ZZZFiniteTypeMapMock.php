<?php

namespace Koncept\DI\Tests\Mocks;

use Koncept\DI\Base\FiniteTypeMapAbstract;
use Koncept\DI\Tests\Objects\ZZZObjectA;
use Koncept\DI\Tests\Objects\ZZZObjectB;
use Strict\Collection\Vector\Scalar\Vector_string;


class ZZZFiniteTypeMapMock
    extends FiniteTypeMapAbstract
{
    /**
     * Return the list of supported types.
     *
     * @return Vector_string
     */
    public function getList(): Vector_string
    {
        return new Vector_string(
            ZZZObjectA::class,
            ZZZObjectB::class
        );
    }

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
        return new $type;
    }
}