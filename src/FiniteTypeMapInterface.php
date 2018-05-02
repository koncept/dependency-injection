<?php

namespace Koncept\DI;

use Strict\Collection\Vector\Scalar\Vector_string;


/**
 * [Interface] Finite Type Map
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Koncept. All Rights Reserved.
 * @package koncept/dependency-injection
 * @since v1.0.0
 */
interface FiniteTypeMapInterface
    extends TypeMapInterface
{
    /**
     * Return the list of supported types.
     *
     * @return Vector_string
     */
    public function getList(): Vector_string;
}