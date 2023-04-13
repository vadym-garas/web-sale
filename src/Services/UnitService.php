<?php

namespace App\Services;

use App\Entity\Product;
use App\Entity\Product as ProductEntity;

use App\Entity\Unit;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ObjectRepository;

class UnitService extends AbstractEntityService
{

    /**
     * @var ProductRepository
     */
    protected ObjectRepository $repository;

    protected function init()
    {
        parent::init();
        $this->repository = $this->doctrine->getRepository(Unit::class);
    }

    public function getUrlByCode(string $code): UrlCodePair
    {
        try {
            return $this->repository->findOneBy(['code' => $code]);
        } catch (\Throwable) {
            throw new DataNotFoundException('Url not found by code from UrlService getUrlByCode code = ' . $code);
        }
    }

    public function getUrlsByUser(?User $user = null): array
    {
        try {
            return $this->repository->findAll();
        } catch (\Throwable) {
            echo 'throw';
            throw new DataNotFoundException('Data not found by code');
        }
    }
}