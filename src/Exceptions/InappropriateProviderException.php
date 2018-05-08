<?php

namespace Koncept\DI\Exceptions;

use LogicException;
use ReflectionMethod;


/**
 * [Exception] Inappropriate Provider Method
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Koncept. All Rights Reserved.
 * @package koncept/dependency-injection
 * @since v1.0.0
 */
class InappropriateProviderException
    extends LogicException
{
    public static function OnStatic(ReflectionMethod $refMethod): self
    {
        $name = self::GenerateName($refMethod);
        return new self("Provider method must be non-static, static provider {$name} declared");
    }

    public static function OnNoReturnType(ReflectionMethod $reflectionMethod): self
    {
        $name = self::GenerateName($reflectionMethod);
        return new self("Provider method must have return value type hint, provider {$name} without return value type hint declared");
    }

    public static function OnBuiltinReturnType(ReflectionMethod $reflectionMethod): self
    {
        $name = self::GenerateName($reflectionMethod);
        return new self("Provider method must return object, provider {$name} has its return value type hint of builtin type");
    }

    public static function OnNullableReturnType(ReflectionMethod $reflectionMethod): self
    {
        $name = self::GenerateName($reflectionMethod);
        return new self("Provider method cannot return null, provider {$name} has nullable return value type hint");
    }

    private static function GenerateName(ReflectionMethod $reflectionMethod): string
    {
        return "{$reflectionMethod->getDeclaringClass()->getShortName()}::{$reflectionMethod->getName()} " .
            "({$reflectionMethod->getDeclaringClass()->getName()})";
    }
}