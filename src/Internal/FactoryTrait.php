<?php

namespace Koncept\DI\Internal;

use Koncept\DI\Utility\ArgumentResolver;
use ReflectionClass;


/**
 * [Trait] Factory Trait
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Koncept. All Rights Reserved.
 * @package koncept/dependency-injection
 * @since v1.0.0
 *
 * @internal
 */
trait FactoryTrait
{
    /**
     * Generate an instance of ReflectionClass.
     * Throw NonexistentTypeException if class does not exist.
     *
     * @param string $type
     * @return ReflectionClass
     */
    abstract protected function getReflectionClass(string $type): ReflectionClass;

    /**
     * @return ArgumentResolver
     */
    abstract protected function getArgumentResolver(): ArgumentResolver;

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
    final protected function getObject(string $type): ?object
    {
        $refClass = $this->getReflectionClass($type);
        if (is_null($refConst = $refClass->getConstructor()))
            return $refClass->newInstance();

        return $refClass->newInstance(...$this->getArgumentResolver()->resolveReflection($refConst));
    }
}