<?php

namespace Koncept\DI\Utility;

use Closure;
use Koncept\DI\Base\FiniteTypeMapAbstract;
use Koncept\DI\Exceptions\CircularDependencyException;
use Koncept\DI\Exceptions\DuplicateProviderException;
use Koncept\DI\Exceptions\InappropriateProviderException;
use Koncept\DI\Internal\ReflectionClassAcquisition;
use Koncept\DI\TypeMapInterface;
use ReflectionMethod;
use ReflectionObject;
use Strict\Collection\Vector\Scalar\Vector_string;


/**
 * [Abstract Class] Container
 *
 * Implement method named "provide***" like `providePDO(): PDO`.
 * This class automatically call those methods based on their return-value type hint when required by `get()`.
 * Those provider methods will be called only once and return values are cached for the second call.
 *
 * Implement method named "create***" like `createVector(): Vector`.
 * This class automatically call those methods based on their return-value type hint when required by `get()`.
 * Those factory methods will be called for each requirement of objects.
 *
 * ***** ***** IMPORTANT ***** *****
 * Do not use get() or getObject() in factory or provider methods.
 * Instead require dependencies via arguments.
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Koncept. All Rights Reserved.
 * @package koncept/dependency-injection
 * @since v1.0.0
 */
abstract class Container
    extends FiniteTypeMapAbstract
{
    /** @var ArgumentResolver */
    private $resolver;

    /** @var Closure[] */
    private $typeClosures;

    /** @var bool[] */
    private $requiring = [];

    /**
     * Container constructor.
     *
     * @param TypeMapInterface|null $dependency
     */
    final public function __construct(?TypeMapInterface $dependency = null)
    {
        if (is_null($dependency)) {
            $typeMap = $this;
        } else {
            $typeMap = new AggregateTypeMap($dependency, $this);
        }

        $this->resolver = new ArgumentResolver($typeMap);
    }

    /**
     * Return the list of supported types.
     * This method will be called only once for each instance.
     *
     * @return Vector_string
     */
    final protected function generateList(): Vector_string
    {
        $refObj = new ReflectionObject($this);

        $typeClosures = [];

        foreach ($refObj->getMethods() as $method) {

            $name = $method->getName();

            if (1 !== preg_match('/^(provide|create)[A-Z]/u', $name))
                continue;

            if ($method->isStatic())
                throw InappropriateProviderException::OnStatic($method);

            if (!$method->hasReturnType())
                throw InappropriateProviderException::OnNoReturnType($method);

            $returnType = $method->getReturnType();

            if ($returnType->isBuiltin())
                throw InappropriateProviderException::OnBuiltinReturnType($method);

            if ($returnType->allowsNull())
                throw InappropriateProviderException::OnNullableReturnType($method);

            // return type OK.

            $solver   = $this->getResolverClosure($method);
            $typeName = $returnType->getName();
            if (isset($typeClosures[$typeName]))
                throw new DuplicateProviderException("Two or more providers for {$this->getShortName($typeName)} ({$typeName}) found");

            if (preg_match('/^provide[A-Z]/u', $name)) {
                $typeClosures[$typeName] = function () use ($solver): object {
                    static $object = null;
                    if (!is_null($object)) return $object;
                    return $object = $solver();
                };
            } else {
                $typeClosures[$typeName] = $solver;
            }
        }

        $this->typeClosures = $typeClosures;

        return new Vector_string(...array_keys($typeClosures));
    }

    /**
     * Generate solver for method.
     *
     * @param ReflectionMethod $method
     * @return Closure
     */
    private function getResolverClosure(ReflectionMethod $method): Closure
    {
        return function () use ($method): object {
            $closure = $method->getClosure($this);
            return $closure(...$this->resolver->resolveClosure($closure, $method));
        };
    }

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
        if (isset($this->typeClosures[$type])) {
            if (isset($this->requiring[$type])) {
                $refClass = $this->getReflectionClass($type);
                $refThis  = new ReflectionObject($this);
                throw new CircularDependencyException("Circular dependency found. " .
                    "A dependency of {$refClass->getShortName()} ({$refClass->getName()}) " .
                    "depends on {$refClass->getName()} " .
                    "in {$refThis->getShortName()} ({$refThis->getName()})");
            }
            $this->requiring[$type] = true;

            $ret = $this->typeClosures[$type]();

            unset($this->requiring[$type]);
            return $ret;
        }
        return null;
    }

    use ReflectionClassAcquisition;
}