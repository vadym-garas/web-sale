<?php

namespace App\Entity\Traits;

use App\Entity\Category;
use App\Entity\Page;
use App\Entity\Product;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


trait NameTrait
{
    #[ORM\Column(type: Types::STRING, length: 255)]
    private string $name = '';

    /**
     * @param string $name
     */
    public function __construct(string $name='')
    {
        $this->name = $name;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return NameTrait|Category|Page|Product
     */
    public function setName(string $name=''): self
    {
        $this->name = $name;

        return $this;
    }
}