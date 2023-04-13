<?php

namespace App\ProductStore;

use App\Entity\Product;
use App\Entity\Product as ProductEntity;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Persistence\ObjectRepository;

class ProductRepository implements Interfaces\IProductRepository
{
    protected ObjectRepository $cpRepository;
    protected ObjectManager $em;

    public function __construct(protected ManagerRegistry $doctrine)
    {
        $this->em = $this->doctrine->getManager();
        $this->cpRepository = $this->doctrine->getRepository(ProductEntity::class);
    }

    /**
     * @inheritDoc
     */
    public function saveEntity(Product $productVO): bool
    {
        try {
            $result = true;
            $codePair = new ProductEntity($productVO->getUrl(), $productVO->getCode());
            $this->em->persist($codePair);
            $this->em->flush();
        } catch (\Throwable) {
            $result = false;
        }
        return $result;
    }

    /**
     * @inheritDoc
     */
    public function isCodeExist(string $product_code): bool
    {
        return (bool)$this->cpRepository->findOneBy(['product_code' => $product_code]);
    }
}