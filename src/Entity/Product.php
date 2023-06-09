<?php

namespace App\Entity;

use App\Entity\Traits\NameTrait;
use App\Entity\Traits\RangeTrait;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\StateTrait;
use Doctrine\DBAL\Types\Types;


#[ORM\Entity()]
#[ORM\Table(name: 'products')]
class Product
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $code = '';

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $price = 0;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $cost = 0;

    #[ORM\ManyToOne(targetEntity: Category::class, cascade: ['persist', 'remove'])]
    private ?Category $category = null;

    use StateTrait;
    use NameTrait;
    use RangeTrait;

    public function __construct(
        ?Category $category = null,
        string $code='',
        int $price=0,
        int $cost=0
    ) {
        $this->code = $code;
        $this->price = $price;
        $this->cost = $cost;

        isset($category) ? $this->category = $category : $this->category = new Category();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @return int|null
     */
    public function getCost(): ?int
    {
        return $this->cost;
    }

    /**
     * @return int|null
     */
    public function getPrice(): ?int
    {
        return $this->price;
    }

    /**
     * @param string $code
     * @return $this
     */
    public function setCode(string $code=''): self
    {
        $this->code = $code;

        return $this;
    }

    public function setCost(int $cost=0): self
    {
        $this->cost = $cost;

        return $this;
    }

    public function setPrice(int $price=0): self
    {
        $this->price = $price;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }
}