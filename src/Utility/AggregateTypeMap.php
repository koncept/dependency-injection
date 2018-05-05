<?php

namespace Koncept\DI\Utility;

use Koncept\DI\Base\TypeMapAbstract;
use Koncept\DI\FiniteTypeMapInterface;
use Koncept\DI\TypeMapInterface;


/**
 * [Class] Aggregate Type Map
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Koncept. All Rights Reserved.
 * @package koncept/dependency-injection
 * @since v1.0.0
 */
final class AggregateTypeMap
    extends TypeMapAbstract
{
    /** @var FiniteTypeMapInterface[] type: string => FiniteTypeMapInterface */
    private $finiteTypeMaps = [];

    /** @var TypeMapInterface[] */
    private $typeMaps = [];

    /**
     * AggregateTypeMap constructor.
     *
     * @param TypeMapInterface[] ...$typeMaps
     */
    public function __construct(TypeMapInterface ...$typeMaps)
    {
        foreach ($typeMaps as $typeMap) {
            if ($typeMap instanceof AggregateTypeMap) {
                $this->finiteTypeMaps = array_merge($this->finiteTypeMaps, $typeMap->finiteTypeMaps);
                $this->typeMaps       = array_merge($typeMap->typeMaps, $this->typeMaps);
                continue;
            }

            if ($typeMap instanceof FiniteTypeMapInterface) {
                foreach ($typeMap->getList() as $type) {
                    $this->finiteTypeMaps[$type] = $typeMap;
                }
                continue;
            }

            array_unshift($this->typeMaps, $typeMap);
        }
    }

    /**
     * Create new instance with the object given.
     *
     * @param object $object
     * @param null|string $type
     * @return AggregateTypeMap
     */
    public function withObject(object $object, ?string $type = null): self
    {
        return $this->withTypeMap((new ObjectContainer)->with($object, $type));
    }

    /**
     * Create new instance with the TypeMap given.
     *
     * @param TypeMapInterface $typeMap
     * @return AggregateTypeMap
     */
    public function withTypeMap(TypeMapInterface $typeMap): self
    {
        return new self($this, $typeMap);
    }

    /** @var TypeMapInterface[] */
    private $supportCache = [];

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
        if (isset($this->supportCache[$type])) {
            return $this->supportCache[$type]->get($type);
        }

        return null;
    }

    /**
     * Return the type is supported or not.
     *
     * @param string $type
     * @return bool
     */
    public function supports(string $type): bool
    {
        if (isset($this->finiteTypeMaps[$type])) {
            $this->supportCache[$type] = $this->finiteTypeMaps[$type];
            return true;
        }

        foreach ($this->typeMaps as $typeMap) {
            if ($typeMap->supports($type)) {
                $this->supportCache[$type] = $typeMap;
                return true;
            }
        }

        return false;
    }
}