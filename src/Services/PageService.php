<?php

namespace App\Services;

use App\Entity\Page;

use App\Exceptions\DataNotFoundException;
use App\Exceptions\ObjectCantSaveException;
use App\Repository\ProductRepository;
use Doctrine\Persistence\ObjectRepository;
//use phpDocumentor\Reflection\Types\Collection;
use Doctrine\Common\Collections\Collection;


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

    public function addNewCategoryToPage($page, $categories, $choices)
    {
        foreach ($categories as $category)
        {
            if(in_array($category->getId(), $choices))
            {
                $page->addCategory($category);
            }
        }
    }// add selected items

    public function removeCategoryFromPage($page, $choices)
    {
        $oldCategories = $page->getCategories();
        foreach ($oldCategories as $category)
        {
            if(!in_array($category->getId(), $choices))
            {
                $page->removeCategory($category);
            }
        }
    }// delete non selected items

    /**
     * @throws ObjectCantSaveException
     */
    public function createPage(array $categories, \Closure $getParam): Page
    {
//        try {
            $page = new Page();
            $choices = $getParam('choices');
            $range = (int)$getParam('range');

            $this->addNewCategoryToPage($page, $categories, $choices);

            return $this->updatePage($page, $getParam);

//        } catch (\Exception $e) {
//            throw new ObjectCantSaveException('Product not saved', previous: $e);
//        }
    }

    public function updatePage(Page $page, \Closure $getParam): Page
    {
        try {
            $page
                ->setName((string)$getParam('name'))
                ->setState((int)$getParam('state'))
                ->setRange((int)$getParam('range'));
            $this->save($page);

            return $page;
        } catch (\Throwable) {
            throw new DataNotFoundException('Product not found by code from ProductService editProductByCode');
        }
    }

    public function updatePageById(array $categories, \Closure $getParam, int $page_id): Page
    {
        $page = $this->getPageById($page_id);
        $choices = $getParam('choices');

        $this->removeCategoryFromPage($page, $choices);
        $this->addNewCategoryToPage($page, $categories, $choices);

        return $this->updatePage($page, $getParam);
    }

    public function getArrCategoryByPageId($page_id): array
    {
        $categories = $this->getPageById($page_id)->getCategories();
        $result = [];

        foreach ($categories as $category) {
            $result[$category->getId()] = $category->getName();
        }
        return $result;
    }

    public function deletePageById(int $page_id): void
    {
        $page = $this->getPageById($page_id);
//        try {
            $this->delete($page);
//        } catch (\Throwable) {
//            throw new DataNotFoundException('Product not found by code from ProductService editProductByCode');
//        }
    }

    public function getPageById(int $id): Page
    {
//        try {
            return $this->repository->findOneBy(['id' => $id]);
//        } catch (\Throwable) {
//            throw new DataNotFoundException('Product not found by code from ProductService getProductById = ' . $id);
//        }
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

    public function getPageByCode(string $code): Page
    {
        try {
            return $this->repository->findOneBy(['code' => $code]);
        } catch (\Throwable) {
            throw new DataNotFoundException('Product not found by code from ProductService getUrlByCode code = ' . $code);
        }
    }

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