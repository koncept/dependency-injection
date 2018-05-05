<?php

namespace Koncept\DI\Base;

use Koncept\DI\FiniteTypeMapInterface;
use Strict\Collection\Vector\Scalar\Vector_string;


/**
 * [Abstract Class] Finite Type Map Abstract
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Koncept. All Rights Reserved.
 * @package koncept/dependency-injection
 * @since v1.0.0
 */
abstract class FiniteTypeMapAbstract
    extends TypeMapAbstract
    implements FiniteTypeMapInterface
{
    /** @var Vector_string */
    private $list = null;


    /**
     * Return the list of supported types.
     * This method will be called only once for each instance.
     *
     * @return Vector_string
     */
    abstract protected function generateList(): Vector_string;

    /**
     * Return the list of supported types.
     *
     * @return Vector_string
     */
    final public function getList(): Vector_string
    {
        return $this->list ?? $this->list = $this->generateList();
    }

    /**
     * Return the type is supported or not.
     *
     * @param string $type
     * @return bool
     */
    final public function supports(string $type): bool
    {
        $list = array_flip($this->getList()->getArray());
        return isset($list[$type]);
    }
}