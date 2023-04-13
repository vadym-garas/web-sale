<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\Table(name: 'categories_products')]
class CategoryProduct
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\NotBlank]
    private int $category_id;

    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\NotBlank]
    private int $product_id;



    public function __construct($category_id=0, $product_id=null)
    {
        $this->category_id = $category_id;
        $this->product_id = $product_id;
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function getProductId(): ?int
    {
        return $this->product_id;
    }

    public function setProductId(int $product_id): self
    {
        $this->product_id = $product_id;

        return $this;
    }

    public function getCategoryId(): ?Product
    {
        return $this->category_id;
    }

    public function setCategoryId(int $category_id): self
    {
        $this->category_id = $category_id;

        return $this;
    }
}