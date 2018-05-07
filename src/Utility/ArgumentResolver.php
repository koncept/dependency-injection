<?php

namespace Koncept\DI\Utility;

use Closure;
use Koncept\DI\Exceptions\UnresolvableParameterException;
use Koncept\DI\TypeMapInterface;
use ReflectionFunction;
use ReflectionFunctionAbstract;


/**
 * [Class] Argument Resolver
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Koncept. All Rights Reserved.
 * @package koncept/dependency-injection
 * @since v1.0.0
 */
class ArgumentResolver
{
    /** @var TypeMapInterface */
    private $dependency;

    /**
     * ArgumentResolver constructor.
     * @param TypeMapInterface $dependency
     */
    public function __construct(TypeMapInterface $dependency)
    {
        $this->dependency = $dependency;
    }

    /**
     * @see ArgumentResolver::resolveReflection()
     *
     * @param Closure $closure
     * @return array
     */
    public function resolveClosure(Closure $closure): array
    {
        return $this->resolveReflection(
            (function () use ($closure): ReflectionFunction {
                return new ReflectionFunction($closure);
            })()
        );
    }

    /**
     * Resolve argument of ReflectionFunctionAbstract.
     *
     * Default value must be provided for parameter without type hint and
     *                                    parameter with builtin type hint.
     *
     * Variadic parameter is prohibited.
     *
     * When the type of a parameter is not supported by the given type map,
     *   null will be given as long as the parameter is nullable.
     *
     * @param ReflectionFunctionAbstract $reflectionFunction
     * @return array
     */
    public function resolveReflection(ReflectionFunctionAbstract $reflectionFunction): array
    {
        $arg = [];

        foreach ($reflectionFunction->getParameters() as $parameter) {

            if (!$parameter->hasType()) {
                if ($parameter->isDefaultValueAvailable()) {
                    $arg[] = $parameter->getDefaultValue();
                    continue;
                }
                throw UnresolvableParameterException::OnNoTypeHint($reflectionFunction, $parameter);
            }

            if ($parameter->isPassedByReference())
                throw UnresolvableParameterException::OnPassedByReference($reflectionFunction, $parameter);

            if ($parameter->isVariadic())
                throw UnresolvableParameterException::OnVariadic($reflectionFunction, $parameter);

            $type = $parameter->getType();
            if ($type->isBuiltin()) {
                if ($parameter->isDefaultValueAvailable()) {
                    $arg[] = $parameter->getDefaultValue();
                    continue;
                }
                throw UnresolvableParameterException::OnBuiltIn($reflectionFunction, $parameter);
            }

            if (!$this->dependency->supports($name = $type->getName())) {
                if ($parameter->allowsNull()) {
                    $arg[] = null;
                    continue;
                }
                throw UnresolvableParameterException::OnNotSupported($reflectionFunction, $parameter);
            }

            $arg[] = $this->dependency->get($name);
        }

        return $arg;
    }
}