<?php

namespace App\Entity;

use ReflectionClass;

abstract class Unit
{
    const UNDEFINED = 0;
    const LINEAR_METER = 1;
    const SQUARE_METER = 2;
    const AMOUNT_PIECE = 3;

    private function __construct()
    {
    }

    public static function getArrUnitConstant(): array
    {
        return (new ReflectionClass(static::class))->getConstants();
    }
}