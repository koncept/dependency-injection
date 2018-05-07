<?php

namespace Koncept\DI\Exceptions;

use LogicException;
use ReflectionFunctionAbstract;
use ReflectionParameter;


/**
 * [Exception] Unresolvable Parameter
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Koncept. All Rights Reserved.
 * @package koncept/dependency-injection
 * @since v1.0.0
 */
class UnresolvableParameterException
    extends LogicException
{
    public static function OnNoTypeHint(
        ReflectionFunctionAbstract $refFunc,
        ReflectionParameter $refParam
    ): self {
        return new self(
            "Parameter \${$refParam->getName()} of {$refFunc->getShortName()} " .
            "({$refFunc->getName()} in {$refFunc->getFileName()} from #{$refFunc->getStartLine()}) " .
            "has neither type hint nor default value"
        );
    }

    public static function OnPassedByReference(
        ReflectionFunctionAbstract $refFunc,
        ReflectionParameter $refParam
    ): self {
        return new self(
            "Parameter \${$refParam->getName()} of {$refFunc->getShortName()} " .
            "({$refFunc->getName()} in {$refFunc->getFileName()} from #{$refFunc->getStartLine()}) " .
            "requires values to be passed by reference"
        );
    }

    public static function OnVariadic(
        ReflectionFunctionAbstract $refFunc,
        ReflectionParameter $refParam
    ): self {
        return new self(
            "Parameter \${$refParam->getName()} of {$refFunc->getShortName()} " .
            "({$refFunc->getName()} in {$refFunc->getFileName()} from #{$refFunc->getStartLine()}) " .
            "is variadic"
        );
    }

    public static function OnBuiltIn(
        ReflectionFunctionAbstract $refFunc,
        ReflectionParameter $refParam
    ): self {
        return new self(
            "Parameter \${$refParam->getName()} of {$refFunc->getShortName()} " .
            "({$refFunc->getName()} in {$refFunc->getFileName()} from #{$refFunc->getStartLine()}) " .
            "has type hint of builtin type and no default value is provided"
        );
    }

    public static function OnNotSupported(
        ReflectionFunctionAbstract $refFunc,
        ReflectionParameter $refParam
    ): self {
        return new self(
            "Parameter \${$refParam->getName()} of {$refFunc->getShortName()} " .
            "({$refFunc->getName()} in {$refFunc->getFileName()} from #{$refFunc->getStartLine()}) " .
            "has type hint of unsupported type and is not nullable"
        );
    }
}