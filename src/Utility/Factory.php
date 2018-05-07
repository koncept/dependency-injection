<?php

namespace Koncept\DI\Utility;

use Koncept\DI\Base\TypeMapAbstract;
use Koncept\DI\Internal\FactoryTrait;
use Koncept\DI\Internal\ReflectionClassAcquisition;
use Koncept\DI\TypeMapInterface;


/**
 * [Class] Factory
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Koncept. All Rights Reserved.
 * @package koncept/dependency-injection
 * @since v1.0.0
 */
class Factory
    extends TypeMapAbstract
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
     * Return the type is supported or not.
     *
     * @param string $type
     * @return bool
     */
    public function supports(string $type): bool
    {
        return class_exists($type);
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