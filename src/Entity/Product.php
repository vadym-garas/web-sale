<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\StateTrait;
use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;


#[ORM\Entity()]
#[ORM\Table(name: 'products')]
class Product
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(type: Types::STRING, length: 64)]
    private string $product_code = '';

    #[ORM\Column(type: Types::INTEGER)]
    private int $count = 0;

    #[ORM\OneToMany(mappedBy: 'product', targetEntity: Category::class)]
    public Collection $categories;

    use StateTrait;


    public function __construct(string $product_code='', int $count=0, int $state=State::STATE_DISABLE)
    {
        $this->product_code = $product_code;
        $this->count = $count;
        $this->state = $state;

//        $this->categories = new ArrayCollection();
//        $this->addCategory(new Category());
    }

    public static function getClassVars(): array
    {
        return get_class_vars(__CLASS__);
    }

    public function setField(string $name, mixed $value): void
    {
        $this->$name = $value;
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
    public function getProductCode(): ?string
    {
        return $this->product_code;
    }

    /**
     * @return int|null
     */
    public function getCount(): ?int
    {
        return $this->count;
    }

    /**
     * @return Collection<int, Category>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function setProductCode(string $product_code=""): self
    {
        $this->product_code = $product_code;

        return $this;
    }

    public function setCount(int $count=0): self
    {
        $this->count = $count;

        return $this;
    }

    public function addCategory(Category $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
            $category->setProduct($this);
        }

        return $this;
    }

    public function removeCategory(Category $category): self
    {
        if ($this->categories->removeElement($category)) {
            // set the owning side to null (unless already changed)
            if ($category->getProduct() === $this) {
                $category->setProduct(null);
            }
        }

        return $this;
    }
}