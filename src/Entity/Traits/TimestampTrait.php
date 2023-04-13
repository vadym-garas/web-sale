<?php

namespace App\Entity\Traits;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

trait TimestampTrait
{
    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: false, updatable: false)]
    private \DateTime $createdAt;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true, updatable: true)]
    private \DateTime $updatedAt;

    public function __construct()
    {
        $this->setCreatedAt();
        $this->updateDateTime();
    }

    /**
     * @return void
     */
    protected function setCreatedAt(): void
    {
        $this->createdAt = new \DateTime();
    }

    /**
     * @return void
     */
    public function updateDateTime(): void
    {
        $this->updatedAt = new \DateTime();
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }
}