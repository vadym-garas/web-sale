<?php

namespace App\Services;

use App\Entity\Page;

use App\Exceptions\DataNotFoundException;
use App\Exceptions\ObjectCantSaveException;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ObjectRepository;


class PageService extends AbstractEntityService
{

    /**
     * @var ProductRepository
     */
    protected ObjectRepository $repository;

    protected function init(): void
    {
        parent::init();
        $this->repository = $this->doctrine->getRepository(Page::class);
    }

    /**
     * @throws ObjectCantSaveException
     */
    public function createPage(\Closure $getParam): Page
    {
//        try {
            $page = new Page();

            $page
                ->setName((string)$getParam('name'))
                ->setState((int)$getParam('state'));

            $this->save($page);

            return $page;
//        } catch (\Exception $e) {
//            throw new ObjectCantSaveException('Product not saved', previous: $e);
//        }
    }

    public function updatePage(Page $page): Page
    {
        try {
            $this->save($page);

            return $page;
        } catch (\Throwable) {
            throw new DataNotFoundException('Product not found by code from ProductService editProductByCode');
        }
    }

    public function updatePageById(int $page_id, \Closure $getParam): Page
    {
        try {
            $page = $this->getPageById($page_id);

            $page
                ->setName((string)$getParam['name'])
                ->setState((int)$getParam['state']);

            $this->save($page);

            return $page;
        } catch (\Throwable) {
            throw new DataNotFoundException('Product not found by code from ProductService editProductByCode');
        }
    }

    public function deletePageById(int $page_id): void
    {
        try {
            $page = $this->getPageById($page_id);
            $this->delete($page);

        } catch (\Throwable) {
            throw new DataNotFoundException('Product not found by code from ProductService editProductByCode');
        }
    }

    public function getPageByCode(string $code): Page
    {
        try {
            return $this->repository->findOneBy(['code' => $code]);
        } catch (\Throwable) {
            throw new DataNotFoundException('Product not found by code from ProductService getUrlByCode code = ' . $code);
        }
    }

    public function getPageById(int $id): Page
    {
//        try {
            return $this->repository->findOneBy(['id' => $id]);
//        } catch (\Throwable) {
//            throw new DataNotFoundException('Product not found by code from ProductService getProductById = ' . $id);
//        }
    }

    public function getArrCategoryDetailById($page_id): array
    {
        $page = $this->getPageById($page_id);
        $categories = $page->getCategories();
        $result = [];

        foreach ($categories as $category) {
            $result[$category->getId()] = $category->getName();
        }

        return $result;
    }


    public function getPageByUser(?User $user = null): array
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

    public function getAllPage(): array
    {
//        try {
            return $this->repository->findAll();
//        } catch (\Throwable) {
//            echo 'throw';
//            throw new DataNotFoundException('Data not found by code');
//        }
    }
}