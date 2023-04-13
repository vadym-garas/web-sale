<?php

namespace App\FrameCalculator\Interfaces;

use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;

interface ICalculateFrame
{
    /**
     * @param array $params
     * @return int|float
     */
    public function calculate(array $params): int|float;

//    public function calculate(Request $request): int|float;
}