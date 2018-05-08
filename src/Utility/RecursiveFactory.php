<?php

namespace Koncept\DI\Utility;

use Koncept\DI\Base\TypeMapAbstract;
use Koncept\DI\Exceptions\CircularDependencyException;
use Koncept\DI\Internal\ReflectionClassAcquisition;
use Koncept\DI\TypeMapInterface;
use ReflectionObject;


/**
 * [Class] Recursive Factory
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Koncept. All Rights Reserved.
 * @package koncept/dependency-injection
 * @since v1.1.0
 */
class RecursiveFactory
    extends TypeMapAbstract
{
    /** @var ArgumentResolver */
    private $resolver;

    /** @var bool[] */
    private $requiring = [];

    /**
     * Factory constructor.
     * @param TypeMapInterface $dependency
     */
    public function __construct(TypeMapInterface $dependency)
    {
        $this->resolver = new ArgumentResolver(new AggregateTypeMap($this, $dependency));
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

        if (isset($this->requiring[$type])) {
            $refThis = new ReflectionObject($this);
            throw new CircularDependencyException("Circular dependency found. " .
                "A dependency of {$refClass->getShortName()} ({$refClass->getName()}) " .
                "depends on {$refClass->getName()} " .
                "in {$refThis->getShortName()} ({$refThis->getName()})");
        }
        $this->requiring[$type] = true;

        $ret = $refClass->newInstance(...$this->resolver->resolveReflection($refConst));

        unset($this->requiring[$type]);
        return $ret;
    }

    use ReflectionClassAcquisition;
}