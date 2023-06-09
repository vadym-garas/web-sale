<?php

namespace App\Services;

use App\Entity\Category;
use App\Exceptions\DataNotFoundException;
use App\Exceptions\ObjectCantSaveException;
use App\Repository\CategoryRepository;
use Doctrine\Persistence\ObjectRepository;

class CategoryService extends AbstractEntityService
{

    /**
     * @var CategoryRepository
     */
    protected ObjectRepository $repository;

    protected function init()
    {
        parent::init();
        $this->repository = $this->doctrine->getRepository(Category::class);
    }


    /**
     * @throws ObjectCantSaveException
     */
    public function createCategory(\Closure $getParam): Category
    {
//        try {
            $category = new Category(
                (string)$getParam('name'),
                (int)$getParam('state'),
                (int)$getParam('unit'),
                (int)$getParam('range')
            );
//            $category
//                ->setName((string)$getParam('name'))
//                ->setState((int)$getParam('state'))
//                ->setUnit((int)$getParam('unit'));
            $this->save($category);

            return $category;
//        } catch (\Exception $e) {
//            throw new ObjectCantSaveException('Category not saved', previous: $e);
//        }
    }


    public function updateCategoryById(int $category_id, \Closure $getParam): Category
    {
        try {
            $category = $this->getCategoryById($category_id);

//            echo 'form updateCategoryById = '.(int)$getParam('range');

            $category
                ->setName((string)$getParam('name'))
                ->setUnit((int)$getParam('unit'))
                ->setState((int)$getParam('state'))
                ->setRange((int)$getParam('range'));
            $this->save($category);

            return $category;
        } catch (\Throwable) {
            throw new DataNotFoundException('Product not found by code from ProductService editProductByCode');
        }
    }

    public function getArrValueCategoryDetail(): array
    {
        $categories = $this->getAllCategory();
        $arrTemp = [];

        foreach ($categories as $category) {
            $arrTemp[$category->getId()] = $category->getName();
        }
        return $arrTemp;
    }

    public function deleteCategoryById(int $category_id): void
    {
//        try {
            $category = $this->getCategoryById($category_id);
            $this->delete($category);
//        } catch (\Throwable) {
//            throw new DataNotFoundException('Product not found by code from ProductService editProductByCode');
//        }
    }

    public function getCategoryById(int $id=0): Category
    {
//        try {
            $result = $this->repository->findOneBy(['id' => $id]);
            if(!isset($result)) {
                $result = $this->getDefaultCategory();
            }
            return $result;
//        } catch (\Throwable) {
//            throw new DataNotFoundException('Product not found by code from ProductService getUrlByCode code = ' . $id);
//        }
    }

    public function getDefaultCategory(): Category
    {
//        try {
            $result = $this->repository->findOneBy(['name' => Category::WITHOUT_CATEGORY]);
            if(!isset($result)) {
                $result = new Category();
            }
            return $result;

//        } catch (\Throwable) {
//            throw new DataNotFoundException('Product not found by code from ProductService getUrlByCode code = ' . $id);
//        }
    }

    public function getAllCategory(): array
    {
        try {
            return $this->repository->findAll();
        } catch (\Throwable) {
            echo 'throw';
            throw new DataNotFoundException('Data not found by code');
        }
    }

}