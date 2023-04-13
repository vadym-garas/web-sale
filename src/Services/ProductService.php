<?php

namespace App\Services;

use App\Entity\Product;
use App\Entity\Product as ProductEntity;

use App\Entity\User;
use App\Exceptions\DataNotFoundException;
use App\Exceptions\ObjectCantSaveException;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Callback;


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
        try {
            $product = new Product();

            $product
                ->setProductCode((string)$getParam('product_code'))
                ->setState((int)$getParam('state'))
                ->setCount((int)$getParam('count'));

            $this->save($product);

            return $product;
        } catch (\Exception $e) {
            throw new ObjectCantSaveException('Product not saved', previous: $e);
        }
    }


    public function updateProductById(int $product_id, \Closure $getParam): Product
    {
        try {
            $product = $this->getProductById($product_id);

//            $fields = Product::getClassVars();
//
//            foreach ($fields as $key => $value) {
//
//                if ($getParam($key)) {
//
//                    $product->setField($key, $fields[$key]);
//                }
//            }

            $product
                ->setProductCode((string)$getParam('product_code'))
                ->setState((int)$getParam('state'))
                ->setCount((int)$getParam('count'));

            $this->save($product);

            return $product;
        } catch (\Throwable) {
            throw new DataNotFoundException('Product not found by code from ProductService editProductByCode');
        }
    }


    public function deleteProductById(int $product_id)
    {
        try {
            $product = $this->getProductById($product_id);
            $this->delete($product);

        } catch (\Throwable) {
            throw new DataNotFoundException('Product not found by code from ProductService editProductByCode');
        }
    }

    public function getProductByCode(string $code): Product
    {
        try {
            return $this->repository->findOneBy(['code' => $code]);
        } catch (\Throwable) {
            throw new DataNotFoundException('Product not found by code from ProductService getUrlByCode code = ' . $code);
        }
    }

    public function getProductById(int $id): Product
    {
        try {
            return $this->repository->findOneBy(['id' => $id]);
        } catch (\Throwable) {
            throw new DataNotFoundException('Product not found by code from ProductService getUrlByCode code = ' . $id);
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

    public function getAllProduct(): array
    {
        try {
            return $this->repository->findAll();
        } catch (\Throwable) {
            echo 'throw';
            throw new DataNotFoundException('Data not found by code');
        }
    }
}