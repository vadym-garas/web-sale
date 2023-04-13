<?php

namespace App\Services;

use App\Entity\Product;
use App\Entity\Product as ProductEntity;

use App\Repository\ProductRepository;
use Doctrine\Persistence\ObjectRepository;

class HandbookService extends AbstractEntityService
{

    /**
     * @var ProductRepository
     */
    protected ObjectRepository $repository;

    protected function init()
    {
        parent::init();
        $this->repository = $this->doctrine->getRepository(Handbook::class);
    }
}