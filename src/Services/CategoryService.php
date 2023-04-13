<?php

namespace App\Services;

use App\Entity\Product;
use App\Entity\Product as ProductEntity;

use App\Repository\ProductRepository;
use Doctrine\Persistence\ObjectRepository;

class CategoryService extends AbstractEntityService
{

    /**
     * @var ProductRepository
     */
    protected ObjectRepository $repository;

    protected function init()
    {
        parent::init();
        $this->repository = $this->doctrine->getRepository(UrlCodePair::class);
    }
}