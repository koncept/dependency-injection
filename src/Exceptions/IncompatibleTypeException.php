<?php

namespace Koncept\DI\Exceptions;

use InvalidArgumentException;


/**
 * [Exception] Incompatible Type
 *
 * This exception will be thrown when an object is not an instance of expected type.
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Koncept. All Rights Reserved.
 * @package koncept/dependency-injection
 * @since v1.0.0
 */
class IncompatibleTypeException
    extends InvalidArgumentException
{
}