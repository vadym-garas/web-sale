<?php

namespace App\Entity\Traits;

use App\Entity\Category;
use App\Entity\Unit;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait UnitTrait
{
    #[ORM\Column(type: Types::SMALLINT)]
//    #[Assert\Choice(0,1,2,3)]
    private int $unit = Unit::UNDEFINED;

    /**
     * @param int $unit
     */
    public function __construct(int $unit=Unit::UNDEFINED)
    {
        $this->unit = $unit;
    }

    /**
     * @return int
     */
    public function getUnit(): int
    {
        return $this->unit;
    }

    /**
     * @param int $unit
     * @return UnitTrait|Category
     */
    public function setUnit(int $unit=Unit::UNDEFINED): self
    {
        $this->unit = $unit;

        return $this;
    }
}