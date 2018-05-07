<?php

namespace Koncept\DI\Utility;

use Koncept\DI\Base\FiniteTypeMapAbstract;
use Koncept\DI\Internal\FactoryTrait;
use Koncept\DI\Internal\ReflectionClassAcquisition;
use Koncept\DI\TypeMapInterface;


/**
 * [Class] Finite Factory
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Koncept. All Rights Reserved.
 * @package koncept/dependency-injection
 * @since v1.0.0
 */
abstract class FiniteFactory
    extends FiniteTypeMapAbstract
{
    /** @var ArgumentResolver */
    private $resolver;

    /**
     * Factory constructor.
     * @param TypeMapInterface $dependency
     */
    public function __construct(TypeMapInterface $dependency)
    {
        $this->resolver = new ArgumentResolver($dependency);
    }

    /**
     * @return ArgumentResolver
     */
    protected function getArgumentResolver(): ArgumentResolver
    {
        return $this->resolver;
    }

    use ReflectionClassAcquisition;
    use FactoryTrait;
}