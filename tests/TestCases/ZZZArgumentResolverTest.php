<?php

namespace Koncept\DI\Tests\TestCases;

use Koncept\DI\Exceptions\UnresolvableParameterException;
use Koncept\DI\Tests\Objects\ZZZObjectA;
use Koncept\DI\Tests\Objects\ZZZObjectB;
use Koncept\DI\Tests\Objects\ZZZObjectCDependingOnB;
use Koncept\DI\Tests\Objects\ZZZObjectDExtendingB;
use Koncept\DI\Utility\ArgumentResolver;
use Koncept\DI\Utility\ObjectContainer;
use PHPUnit\Framework\TestCase;


class ZZZArgumentResolverTest
    extends TestCase
{
    /** @var ArgumentResolver */
    private $resolver;

    public function setUp()
    {
        $this->resolver = new ArgumentResolver(
            (new ObjectContainer)
                ->with(new ZZZObjectA)
                ->with(new ZZZObjectDExtendingB, ZZZObjectB::class)
        );
    }

    public function testResolve()
    {
        $closure = function (
            ZZZObjectA $a,
            ZZZObjectB $b,
            ?ZZZObjectCDependingOnB $c,
            $d = 33,
            int $e = 4) {
            $this->assertInstanceOf(ZZZObjectA::class, $a);
            $this->assertInstanceOf(ZZZObjectDExtendingB::class, $b);
            $this->assertNull($c);
            $this->assertEquals(33, $d);
            $this->assertEquals(4, $e);
        };
        $closure(...$this->resolver->resolveClosure($closure));
    }

    public function testNoTypeHint()
    {
        $this->expectException(UnresolvableParameterException::class);
        $this->resolver->resolveClosure(function ($a) { });
    }

    public function testBuiltinTypeHint()
    {
        $this->expectException(UnresolvableParameterException::class);
        $this->resolver->resolveClosure(function (int $a) { });
    }

    public function testVariadic()
    {
        $this->expectException(UnresolvableParameterException::class);
        $this->resolver->resolveClosure(function(ZZZObjectA ...$a){});
    }

    public function testNotSupported()
    {
        $this->expectException(UnresolvableParameterException::class);
        $this->resolver->resolveClosure(function (ZZZObjectCDependingOnB $c) { });
    }
}