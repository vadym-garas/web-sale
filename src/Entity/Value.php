<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Traits\StateTrait;
use Doctrine\DBAL\Types\Types;


#[ORM\Entity()]
#[ORM\Table(name: 'value')]
class Value
{
    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    #[ORM\GeneratedValue]
    private int $id;

    #[ORM\Column(type: Types::STRING, length: 64)]
    private string $value_code = '';

    #[ORM\Column(type: Types::STRING, length: 64)]
    private string $value_name = '';

    #[ORM\Column(type: Types::STRING, length: 64)]
    private string $value_symbol = '';

    use StateTrait;


    public function __construct(string $value_code='', string $value_name='', string $value_symbol='', int $state=State::STATE_DISABLE)
    {
        $this->value_code = $value_code;
        $this->value_name = $value_name;
        $this->value_symbol = $value_symbol;
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
    public function getValueCode(): ?string
    {
        return $this->value_code;
    }

    /**
     * @return string|null
     */
    public function getValueName(): ?string
    {
        return $this->value_name;
    }

    /**
     * @return string|null
     */
    public function getValueSymbol(): ?string
    {
        return $this->value_symbol;
    }

    /**
     * @param string $value_code
     * @return $this
     */
    public function setValueCode(string $value_code=""): self
    {
        $this->value_code = $value_code;

        return $this;
    }

    /**
     * @param string $value_name
     * @return $this
     */
    public function setValueName(string $value_name=""): self
    {
        $this->value_name = $value_name;

        return $this;
    }

    /**
     * @param string $value_symbol
     * @return $this
     */
    public function setValueSymbol(string $value_symbol=""): self
    {
        $this->value_symbol = $value_symbol;

        return $this;
    }
}