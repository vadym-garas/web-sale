<?php

namespace App\Entity;

use App\Entity\Traits\NameTrait;
use App\Entity\Traits\RangeTrait;
use App\Entity\Traits\StateTrait;
use App\Entity\Traits\UnitTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\This;

#[ORM\Entity()]
#[ORM\Table(name: 'categories')]
class Category
{
    const AS_RADIO_BTN = 0;
    const AS_DROP_LIST = 1;
    //const AS_CHECK_BOX = 2;

    const WITHOUT_CATEGORY = 0;

    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(type: Types::INTEGER)]
    private ?int $display = self::AS_RADIO_BTN;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Product::class)]
    private Collection $products;

    #[ORM\ManyToMany(targetEntity: Page::class, mappedBy: 'categories')]
    private Collection $pages;

    use StateTrait;
    use NameTrait;
    use UnitTrait;
    use RangeTrait;

    public function __construct($state=State::STATE_DISABLE)
    {
        $this->name = 'WITHOUT_CATEGORY';
        $this->state = $state;
        $this->unit = Unit::UNDEFINED;
        $this->display = self::AS_RADIO_BTN;
        //$this->range = '';

        $this->products = new ArrayCollection();
        $this->pages = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function getDisplay(): int
    {
        return $this->display;
    }

    public function setDisplay($display): self
    {
        $this->display = $display;

        return $this;
    }

    public static function getClassVars(): array
    {
        return get_class_vars(__CLASS__);
    }

    public static function getArrDisplayConstant(): array
    {
        return [
            'AS_RADIO_BTN' => static::AS_RADIO_BTN,
            'AS_DROP_LIST' => static::AS_RADIO_BTN
        ];
    }

    public function getValueByKey(string $name): mixed
    {
        return $this->$name;
    }

//    public function setDefaultCategory()
//    {
//
//    }

//    public function setField(string $name, mixed $value): void
//    {
//        $this->$name = $value;
//    }

    /**
     * @return Collection<int, Product>
     */
    public function getProducts(): Collection
    {
        return $this->products;
    }

    public function addProduct(Product $product): self
    {
        if (!$this->products->contains($product)) {
            $this->products->add($product);
            $product->setCategory($this);
        }

        return $this;
    }

    public function removeProduct(Product $product): self
    {
        if ($this->products->removeElement($product)) {
            // set the owning side to null (unless already changed)
            if ($product->getCategory() === $this) {
                $product->setCategory(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Page>
     */
    public function getPages(): Collection
    {
        return $this->pages;
    }

    public function addPage(Page $page): self
    {
        if (!$this->pages->contains($page)) {
            $this->pages->add($page);
            $page->addCategory($this);
        }

        return $this;
    }

    public function removePage(Page $page): self
    {
        if ($this->pages->removeElement($page)) {
            $page->removeCategory($this);
        }

        return $this;
    }
}