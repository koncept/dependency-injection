<?php

namespace Koncept\DI\Utility;

use LogicException;
use ReflectionObject;
use Strict\Collection\Vector\Scalar\Vector_string;


/**
 * [Trait] Auto Provider
 *
 * Implement no-argument method named "provide***" like `providePDO(): PDO`.
 * This trait automatically call those methods based on their return-value type hint.
 * Those provider methods will be called only once and return values are cached for the second call.
 *
 * Implement no-argument method named "create***" like `createVector(): Vector`.
 * This trait automatically call those methods based on their return-value type hint.
 * Those factory methods will be called for each requirement of objects.
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Koncept. All Rights Reserved.
 * @package koncept/dependency-injection
 * @since v1.0.0
 */
trait AutoProviderTrait
{
    private $___APT___Object___List = [];

    /**
     * Return the list of supported types.
     * This method will be called only once for each instance.
     *
     * @return Vector_string
     */
    public function generateList(): Vector_string
    {
        $list = [];
        $ro   = new ReflectionObject($this);
        foreach ($ro->getMethods() as $method) {
            if ($method->isStatic()) continue;
            if ($method->isConstructor()) continue;
            if ($method->isDestructor()) continue;
            if ($method->isAbstract()) continue;

            $name = $method->getName();
            if (1 === preg_match('/^provide[A-Z]/u', $name)) {
                $isProvider = true;
            } else if (1 === preg_match('/^create[A-Z]/u', $name)) {
                $isProvider = false;
            } else {
                continue;
            }

            if (!$method->hasReturnType()) {
                if ($isProvider) {
                    throw new LogicException("Provider method ({$ro->getShortName()}::{$name}) must have return-value type hint");
                }
                throw new LogicException("Factory method ({$ro->getShortName()}::{$name}) must have return-value type hint");
            }

            $returnType = $method->getReturnType();

            if ($method->getNumberOfRequiredParameters() !== 0) {
                if ($isProvider) {
                    throw new LogicException("Provider method ({$ro->getShortName()}::{$name}) can not take any argument");
                }
                throw new LogicException("Factory method ({$ro->getShortName()}::{$name}) can not take any argument");
            }
            if ($returnType->isBuiltin()) {
                if ($isProvider) {
                    throw new LogicException("Return type of provider method ({$ro->getShortName()}::{$name}) must not be a builtin type");
                }
                throw new LogicException("Return type of factory method ({$ro->getShortName()}::{$name}) must not be a builtin type");
            }
            if ($returnType->allowsNull()) {
                if ($isProvider) {
                    throw new LogicException("Return type of provider method ({$ro->getShortName()}::{$name}) can not be nullable");
                }
                throw new LogicException("Return type of factory method ({$ro->getShortName()}::{$name}) can not be nullable");
            }
            $returnTypeName = $returnType->getName();

            // returnTypeName => closure
            if ($isProvider) {
                $list[$returnTypeName] = function () use ($name): object {
                    static $ret = null;
                    return $ret ?? ($ret = ([$this, $name])());
                };
            } else {
                $list[$returnTypeName] = function () use ($name): object {
                    return ([$this, $name])();
                };
            }
        }
        return new Vector_string(...array_keys($this->___APT___Object___List = $list));
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
    protected function getObject(string $type): ?object
    {
        if (isset($this->___APT___Object___List[$type])) {
            return ($this->___APT___Object___List[$type])();
        }
        return null;
    }
}