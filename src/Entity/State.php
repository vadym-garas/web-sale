<?php

namespace App\Entity;

use ReflectionClass;

abstract class State
{
    const STATE_DISABLE = 0;
    const STATE_ENABLE = 1;
    const STATE_HIDDEN = 2;

    private function __construct()
    {
    }

    public static function getArrStateConstant(): array
    {
        return (new ReflectionClass(static::class))->getConstants();
    }
}