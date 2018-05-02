<?php

namespace Koncept\DI;


/**
 * [Interface] Type Map
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2017 Koncept. All Rights Reserved.
 * @package koncept/dependency-injection
 * @since v1.0.0
 */
interface TypeMapInterface
{
    /**
     * Acquire object of the type.
     *
     * @param string $type
     * @return object
     */
    public function get(string $type): object;

    /**
     * Return the type is supported or not.
     *
     * @param string $type
     * @return bool
     */
    public function support(string $type): bool;
}