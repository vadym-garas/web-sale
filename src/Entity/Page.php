<?php

namespace App\Entity;

use App\Entity\Traits\NameTrait;
use App\Entity\Traits\RangeTrait;
use App\Entity\Traits\StateTrait;
use App\Repository\PageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PageRepository::class)]
//#[ORM\Table(name: 'pages')]
class Page
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Category::class, inversedBy: 'pages')]
    private Collection $categories;

    use NameTrait;
    use RangeTrait;
    use StateTrait;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
    }

    public function getClassVarsName(): array
    {
        return array_keys(get_object_vars($this));
    }

    public function getId(): ?int
    {
        return $this->id;
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
        if ($this->categories->contains($category)) {
            $this->categories->removeElement($category);
            echo 'Remove category '.$category->getName();
            echo 'Remove category '.$category->getId();
        }
        return $this;
    }

//    public function removeCategories(array $categories): self
//    {
//        foreach ($this->categories as $category)
//        {
//            $this->removeCategory($category);
//        }
//        return $this;
//    }
}
