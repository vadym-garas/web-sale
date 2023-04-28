<?php

namespace App\Entity\Traits;

use App\Entity\Category;
use App\Entity\Page;
use App\Entity\Product;
use App\Entity\State;
use App\Entity\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\This;
use Symfony\Component\Validator\Constraints as Assert;

trait StateTrait
{
    #[ORM\Column(type: Types::SMALLINT)]
//  #[Assert\Choice(0,1,2)]
    private int $state = State::STATE_DISABLE;

    /**
     * @param int $state
     */
    public function __construct(int $state=State::STATE_DISABLE)
    {
        $this->state = $state;
    }

    /**
     * @return void
     */
    public function setStateDisable(): void
    {
        $this->state = State::STATE_DISABLE;
    }

    /**
     * @return void
     */
    public function setStateEnable(): void
    {
        $this->state = State::STATE_ENABLE;
    }

    /**
     * @return void
     */
    public function setStateHidden(): void
    {
        $this->state = State::STATE_HIDDEN;
    }

    /**
     * @return int
     */
    public function getState(): int
    {
        return $this->state;
    }

    /**
     * @param int $state
     * @return User|Category|Page|Product|StateTrait
     */
    public function setState(int $state=State::STATE_DISABLE): self
    {
        $this->state = $state;

        return $this;
    }

    /**
     * @return bool
     */
    public function isEnable(): bool
    {
        return $this->state === State::STATE_ENABLE;
    }

    /**
     * @return bool
     */
    public function isDisable(): bool
    {
        return $this->state === State::STATE_DISABLE;
    }

    /**
     * @return bool
     */
    public function isHidden(): bool
    {
        return $this->state === State::STATE_HIDDEN;
    }
}