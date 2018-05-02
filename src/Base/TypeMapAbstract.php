<?php

namespace Koncept\DI\Base;

use Koncept\DI\Exceptions\NonexistentTypeException;
use Koncept\DI\Exceptions\UnsupportedTypeException;
use Koncept\DI\TypeMapInterface;
use ReflectionClass;
use ReflectionException;
use ReflectionObject;
use TypeError;


/**
 * [Abstract Class] Type Map Abstract
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2017 Koncept. All Rights Reserved.
 * @package koncept/dependency-injection
 * @since v1.0.0
 */
abstract class TypeMapAbstract
    implements TypeMapInterface
{
    /**
     * Acquire object of the type.
     *
     * This method is called inside get() after confirming that the type is supported.
     * So, there is no need to call support() at first in your implementation of this method.
     * In other words, assert($this->support($type)) always passes in this method.
     *
     * @param string $type
     * @return object
     */
    abstract protected function getObject(string $type): object;

    /**
     * Acquire object of the type.
     *
     * @param string $type
     * @return object
     */
    final public function get(string $type): object
    {
        if (!$this->support($type)) {
            $refCls = $this->getReflectionClass($type);
            throw new UnsupportedTypeException("The required class {$refCls->getShortName()} ({$refCls->getName()}) is not supported");
        }

        if (!(($ret = $this->getObject($type)) instanceof $type)) {
            $refCls = $this->getReflectionClass($type);
            $refObj = new ReflectionObject($ret);
            $refThis = new ReflectionObject($this);

            (function (TypeError $e) {
                throw $e;
            })(new TypeError(
                "Return value of {$refThis->getShortName()}::getObject() ({$refThis->getName()}) must be an instance of {$refCls->getShortName()} ({$type}), " .
                "instance of {$refObj->getShortName()} ({$refObj->getName()}) returned"
            ));
        }

        return $ret;
    }

    /**
     * Generate an instance of ReflectionClass.
     * Throw UnsupportedTypeException if class does not exist.
     *
     * @param string $type
     * @return ReflectionClass
     */
    private function getReflectionClass(string $type): ReflectionClass
    {
        try {
            $refCls = new ReflectionClass($type);
        } catch (ReflectionException $reflectionException) {
            $temp = explode('\\', $type);
            $name = array_pop($temp);
            throw new NonexistentTypeException("The required class {$name} ({$type}) does not exist", 0, $reflectionException);
        }
        return $refCls;
    }
}