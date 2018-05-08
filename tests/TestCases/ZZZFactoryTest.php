<?php

namespace Koncept\DI\Tests\TestCases;

use Koncept\DI\Tests\Objects\ZZZObjectB;
use Koncept\DI\Tests\Objects\ZZZObjectCDependingOnB;
use Koncept\DI\Tests\Objects\ZZZObjectDExtendingB;
use Koncept\DI\Utility\Factory;
use Koncept\DI\Utility\FiniteFactory;
use Koncept\DI\Utility\ObjectContainer;
use PHPUnit\Framework\TestCase;
use Strict\Collection\Vector\Scalar\Vector_string;


class ZZZFactoryTest
    extends TestCase
{
    public function testFactory()
    {
        $f = new Factory((new ObjectContainer)->with(new ZZZObjectDExtendingB, ZZZObjectB::class));
        $this->assertInstanceOf(ZZZObjectCDependingOnB::class, $f->get(ZZZObjectCDependingOnB::class));
    }

    public function testFiniteFactory()
    {
        $f = new class((new ObjectContainer)->with(new ZZZObjectDExtendingB, ZZZObjectB::class))
            extends FiniteFactory
        {
            /**
             * Return the list of supported types.
             * This method will be called only once for each instance.
             *
             * @return Vector_string
             */
            protected function generateList(): Vector_string
            {
                return new Vector_string(ZZZObjectCDependingOnB::class);
            }
        };
        $this->assertInstanceOf(ZZZObjectCDependingOnB::class, $f->get(ZZZObjectCDependingOnB::class));
    }
}