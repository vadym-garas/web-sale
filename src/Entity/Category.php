<?php

namespace App\Entity;

use App\Entity\Traits\StateTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity()]
#[ORM\Table(name: 'categories')]
class Category
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\NotBlank]
    private int $name_id;

//    #[ORM\ManyToOne(inversedBy: 'categories')]
//    private ?Product $product = null;

    use StateTrait;

    public function __construct($name_id=0, $product=null, $state=State::STATE_DISABLE)
    {
        $this->name_id = $name_id;
        $this->state = $state;
    }


    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function getProduct(): ?Product
    {
        return $this->product;
    }

    public function setProduct(?Product $product): self
    {
        $this->product = $product;

        return $this;
    }
}