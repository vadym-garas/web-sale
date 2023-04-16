<?php

namespace App\Services;

use App\Entity\Category;
use App\Entity\Category as CategoryEntity;

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
        try {
            $category = new Category();

            $category
//                ->setProductId((int)$getParam('product_id'))
                ->setState((int)$getParam('state'));

            $this->save($category);

            return $category;
        } catch (\Exception $e) {
            throw new ObjectCantSaveException('Category not saved', previous: $e);
        }
    }


    public function updateCategoryById(int $category_id, \Closure $getParam): Category
    {
        try {
            $category = $this->getCategoryById($category_id);

            $category
//                ->setNameId((int)$getParam('name_id'))
                ->setState((int)$getParam('state'));

            $this->save($category);

            return $category;
        } catch (\Throwable) {
            throw new DataNotFoundException('Product not found by code from ProductService editProductByCode');
        }
    }


    public function deleteCategoryById(int $category_id)
    {
        try {
            $category = $this->getCategoryById($category_id);
            $this->delete($category);

        } catch (\Throwable) {
            throw new DataNotFoundException('Product not found by code from ProductService editProductByCode');
        }
    }

    public function getCategoryById(int $id): Category
    {
        try {
            return $this->repository->findOneBy(['id' => $id]);
        } catch (\Throwable) {
            throw new DataNotFoundException('Product not found by code from ProductService getUrlByCode code = ' . $id);
        }
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