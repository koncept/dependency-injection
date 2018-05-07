<?php

namespace Koncept\DI\Internal;

use Koncept\DI\Exceptions\NonexistentTypeException;
use ReflectionClass;
use ReflectionException;


/**
 * [Trait] Reflection Class Acquisition
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Koncept. All Rights Reserved.
 * @package koncept/dependency-injection
 * @since v1.0.0
 *
 * @internal
 */
trait ReflectionClassAcquisition
{
    /**
     * Generate an instance of ReflectionClass.
     * Throw NonexistentTypeException if class does not exist.
     *
     * @param string $type
     * @return ReflectionClass
     */
    private function getReflectionClass(string $type): ReflectionClass
    {
        try {
            $refCls = new ReflectionClass($type);
        } catch (ReflectionException $reflectionException) {
            $name = $this->getShortName($type);
            throw new NonexistentTypeException(
                "The required class {$name} ({$type}) does not exist",
                0, $reflectionException
            );
        }
        return $refCls;
    }

    /**
     * Acquire short name of a class regardless of existence of the class.
     *
     * @param string $type
     * @return string
     */
    private function getShortName(string $type): string
    {
        $temp = explode('\\', $type);
        return array_pop($temp);
    }
}