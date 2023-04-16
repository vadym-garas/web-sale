<?php

namespace App\Entity;

//use Doctrine\Common\Collections\ArrayCollection;
//use Doctrine\Common\Collections\Collection;
//use App\Repository\ProductRepository;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\StateTrait;
use Doctrine\DBAL\Types\Types;


#[ORM\Entity()]
#[ORM\Table(name: 'language')]
class Language
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(type: Types::STRING, length: 64)]
    private string $language_code = '';

    #[ORM\Column(type: Types::STRING, length: 64)]
    private string $language_name = '';

    use StateTrait;


    public function __construct(string $language_code='', string $language_name='', int $state=State::STATE_DISABLE)
    {
        $this->language_code = $language_code;
        $this->language_name = $language_name;
        $this->state = $state;
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
    public function getLanguageCode(): ?string
    {
        return $this->language_code;
    }

    /**
     * @return string|null
     */
    public function getLanguageName(): ?string
    {
        return $this->language_name;
    }

    /**
     * @param string $language_code
     * @return $this
     */
    public function setLanguageCode(string $language_code=""): self
    {
        $this->language_code = $language_code;

        return $this;
    }

    /**
     * @param string $language_code
     * @return $this
     */
    public function setLanguageName(string $language_code=""): self
    {
        $this->language_code = $language_code;

        return $this;
    }
}