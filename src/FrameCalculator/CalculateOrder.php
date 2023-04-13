<?php

namespace App\FrameCalculator;

use App\FrameCalculator\Interfaces\ICalculateFrame;
use Symfony\Component\HttpFoundation\Request;

class CalculateOrder implements ICalculateFrame
{

    public function calculate(array $params): int|float
    {

        $perimeter = $this->getPerimeter($params['frame_height'], $params['frame_width'], $params['margin']);

        return $this->getBaguetteCost($perimeter, $params['baguette_price']);

        // return $costFrame;
    }

    protected function getPerimeter(float|int $frame_height, float|int $frame_width, int $margin): float|int
    {
        return ($frame_height + $frame_width) * 2 + $margin * 2;
    }

    protected function getBaguetteCost(float|int $perimeter, float|int $cost): float|int
    {
        return ($perimeter/100 * $cost)/100;
    }
}