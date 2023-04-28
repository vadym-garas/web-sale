<?php

namespace App\Services;

use App\Entity\Category;
use App\Entity\Product;

use App\Entity\User;
use App\Exceptions\DataNotFoundException;
use App\Exceptions\ObjectCantSaveException;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ObjectRepository;


class ProductService extends AbstractEntityService
{

    /**
     * @var ProductRepository
     */
    protected ObjectRepository $repository;

    protected function init(): void
    {
        parent::init();
        $this->repository = $this->doctrine->getRepository(Product::class);
    }

    /**
     * @throws ObjectCantSaveException
     */
    public function createProduct(\Closure $getParam): Product
    {
//        try {
        $product = new Product();

        $product
            ->setCode((string)$getParam('code'))
            ->setName((string)$getParam('name'))
            ->setPrice((int)$getParam('price'))
            ->setCost((int)$getParam('cost'))
            ->setState((int)$getParam('state'))
            ->setOrderRange(0);

        $this->save($product);

            return $product;
//        } catch (\Exception $e) {
//            throw new ObjectCantSaveException('Product not saved', previous: $e);
//        }
    }


    public function updateProductById(\Closure $getParam, Category $currCategory, int $product_id=0): Product
    {
        $product = $this->getProductById($product_id);
        $product
            ->setCode((string)$getParam('code'))
            ->setName((string)$getParam('name'))
            ->setPrice((int)$getParam('price'))
            ->setCost((int)$getParam('cost'))
            ->setState((int)$getParam('state'))
            ->setOrderRange(0)
            ->setCategory($currCategory);
//
//            echo 'product name ' . $product->getName();

        $this->save($product);

            return $product;
//        } catch (\Throwable) {
//            throw new DataNotFoundException('Product not found by code from ProductService editProductByCode');
//        }
    }


    public function deleteProductById(int $product_id): void
    {
        try {
            $product = $this->getProductById($product_id);
            $this->delete($product);

        } catch (\Throwable) {
            throw new DataNotFoundException('Product not found by code from ProductService editProductByCode');
        }
    }

    public function getProductById(int $id): Product
    {
//        try {
        return $this->repository->findOneBy(['id' => $id]);
//        } catch (\Throwable) {
//            throw new DataNotFoundException('Product not found by code from ProductService getProductById = ' . $id);
//        }
    }

    public function getProductByCode(string $code): Product
    {
        try {
            return $this->repository->findOneBy(['code' => $code]);
        } catch (\Throwable) {
            throw new DataNotFoundException('Product not found by code from ProductService getUrlByCode code = ' . $code);
        }
    }

    public function getProductsByUser(?User $user = null): array
    {
        try {
            return $this->repository->findAll();
        } catch (\Throwable) {
            echo 'throw';
            throw new DataNotFoundException('Data not found by code');
        }
    }

//    public function getProductsByCategoryId($category_id): array
//    {
//        try {
//            return $this->repository->findBy(['category_id' => '3']);//     (['category_id' => $category_id], ['id' => 'ASC']);
//        } catch (\Throwable) {
//            echo 'throw';
//            throw new DataNotFoundException('Data not found by code');
//        }
//    }

    public function getAllProduct(): array
    {
//        try {
            return $this->repository->findAll();
//        } catch (\Throwable) {
//            echo 'throw';
//            throw new DataNotFoundException('Data not found by code');
//        }
    }
}