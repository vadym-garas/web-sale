<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
//use App\Repository\ProductRepository;
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

    #[ORM\Column(type: Types::STRING, length: 64)]
    private ?string $code = null;

    #[ORM\Column(type: Types::STRING, length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $price = null;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $count = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'products')]
    private Collection $categories;

    #[ORM\OneToOne(inversedBy: 'product', cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?Unit $unit = null;

    use StateTrait;


    public function __construct(
        int $state=State::STATE_DISABLE,
        string $code=null,
        string $name=null,
        string $description=null,
        string $price=null,
        int $count=null
    ) {
        $this->code = $code;
        $this->count = $count;
        $this->state = $state;

        $this->categories = new ArrayCollection();
    }

//    public static function getClassVars(): array
//    {
//        return get_class_vars(__CLASS__);
//    }
//
//    public function setField(string $name, mixed $value): void
//    {
//        $this->$name = $value;
//    }

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
    public function getCount(): ?int
    {
        return $this->count;
    }

    /**
     * @return int|null
     */
    public function getPrice(): ?int
    {
        return $this->price;
    }


    public function setCode(string $code=""): self
    {
        $this->code = $code;

        return $this;
    }

    public function setCount(int $count=0): self
    {
        $this->count = $count;

        return $this;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    public function getUnit(): ?Unit
    {
        return $this->unit;
    }

    public function setUnit(Unit $unit): self
    {
        $this->unit = $unit;

        return $this;
    }
}