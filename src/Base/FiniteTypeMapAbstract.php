<?php

namespace Koncept\DI\Base;

use Koncept\DI\FiniteTypeMapInterface;


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
    /** @var null|int[] */
    private $list = null;

    /**
     * Return the type is supported or not.
     *
     * @param string $type
     * @return bool
     */
    final public function support(string $type): bool
    {
        $list = $this->list ?? array_flip($this->getList()->getArray());
        return isset($list[$type]);
    }
}