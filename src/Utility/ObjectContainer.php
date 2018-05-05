<?php

namespace Koncept\DI\Utility;

use Koncept\DI\Base\FiniteTypeMapAbstract;
use Koncept\DI\Exceptions\IncompatibleTypeException;
use Koncept\DI\Exceptions\NonexistentTypeException;
use ReflectionClass;
use ReflectionException;
use ReflectionObject;
use Strict\Collection\Vector\Scalar\Vector_string;


/**
 * [Class] Object Container
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Koncept. All Rights Reserved.
 * @package koncept/dependency-injection
 * @since v1.0.0
 */
final class ObjectContainer
    extends FiniteTypeMapAbstract
{
    /** @var object[] */
    private $objects = [];

    /**
     * ObjectContainer constructor.
     *
     * @param object[] ...$objects
     */
    public function __construct(object ...$objects)
    {
        foreach ($objects as $object) {
            $this->objects[get_class($object)] = $object;
        }
    }

    /**
     * Create new instance with the object given.
     *
     * @param object $object
     * @param null|string $type
     * @return ObjectContainer
     */
    public function with(object $object, ?string $type = null): self
    {
        $ret = clone $this;

        if (is_null($type)) {
            $ret->objects[get_class($object)] = $object;
            return $ret;
        }

        $this->checkInstanceOf($object, $type);

        $ret->objects[$type] = $object;
        return $ret;
    }


    /**
     * Merge many ObjectContainers into one ObjectContainer.
     *
     * @param ObjectContainer[] ...$containers
     * @return ObjectContainer
     */
    public static function Merge(ObjectContainer ...$containers): self
    {
        $ret = new ObjectContainer;
        if (empty($containers)) return $ret;

        $objectsArray = [];
        foreach ($containers as $container) $objectsArray[] = $container->objects;

        $ret->objects = array_merge(...$objectsArray);
        return $ret;
    }

    /**
     * Check if $object instanceof $type is true or not.
     * If it is false, find the reason and throw an exception.
     *
     * @param object $object
     * @param string $type
     */
    private function checkInstanceOf(object $object, string $type): void
    {
        if ($object instanceof $type) return;

        try {
            $refCls = new ReflectionClass($type);
        } catch (ReflectionException $reflectionException) {
            $temp = explode('\\', $type);
            $name = array_pop($temp);
            throw new NonexistentTypeException("The designated type {$name} ($type) does not exist", 0, $reflectionException);
        }

        $refObj  = new ReflectionObject($object);
        $refThis = new ReflectionObject($this);

        throw new IncompatibleTypeException(
            "The first argument of {$refThis->getShortName()}::with() ({$refThis->getName()}) " .
            "must be an instance of {$refCls->getShortName()} {$refCls->getName()}, " .
            "instance of {$refObj->getShortName()} ({$refObj->getName()}) returned"
        );
    }

    /**
     * Return the list of supported types.
     * This method will be called only once for each instance.
     *
     * @return Vector_string
     */
    public function generateList(): Vector_string
    {
        return new Vector_string(
            ...array_keys($this->objects)
        );
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
        return $this->objects[$type] ?? null;
    }
}