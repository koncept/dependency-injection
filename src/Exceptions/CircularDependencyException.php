<?php

namespace Koncept\DI\Exceptions;

use LogicException;


/**
 * [Exception] Circular Dependency Detection
 *
 * @author Showsay You <akizuki.c10.l65@gmail.com>
 * @copyright 2018 Koncept. All Rights Reserved.
 * @package koncept/dependency-injection
 * @since v1.0.0
 */
class CircularDependencyException
    extends LogicException
{
}