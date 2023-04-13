<?php

namespace App\Helpers;

use InvalidArgumentException;

class NumberValidator
{
    protected int|float $value;

    /**
     * @param $value
     */
    public function __construct($value)
    {
        $this->isNumber($value);
        $this->value = $value;
    }

    protected function isNumber($value): void
    {
        if (!is_numeric($value)) {
            throw new InvalidArgumentException($value . ' - is not a number');
        }
    }
}