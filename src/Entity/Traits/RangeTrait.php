<?php

namespace App\Entity\Traits;

use App\Entity\Category;
use App\Entity\Page;
use App\Entity\Product;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait RangeTrait
{
    #[ORM\Column(type: Types::SMALLINT)]
    private int $order_range = 0;

    /**
     * @param int $range
     */
    public function __construct(int $range=0)
    {
        $this->order_range = $range;
    }

    /**
     * @return int
     */
    public function getOrderRange(): int
    {
        return $this->order_range;
    }

    /**
     * @param int $order_range
     * @return RangeTrait|Category|Page|Product
     */
    public function setOrderRange(int $order_range=0): self
    {
        $this->order_range = $order_range;

        return $this;
    }
}