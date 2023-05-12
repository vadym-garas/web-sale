<?php

namespace App\Controller;

use App\Entity\Category;
use App\Entity\State;
use App\Entity\Unit;
use App\Services\ProductService;
use App\Services\CategoryService;
use App\Services\PageService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/admin')]
class AdminController extends AbstractController
{
//    /**
//     * @param IUrlEncoder $encoder
//     * @param IUrlDecoder $decoder
//     * @param AbstractEntityService $urlService
//     */
//    public function __construct(
//        protected IUrlEncoder $encoder,
//        protected IUrlDecoder $decoder,
//        protected AbstractEntityService $urlService
//    ){}

    public function __construct(
        protected ProductService $productService,
        protected CategoryService $categoryService,
        protected PageService $pageService
    ){}


    #[Route('/products', name: 'all_product', methods: ['get'])]
    public function allProductAction(Request $request): Response
    {
        $vars = [
            'form_title' => 'Товары',
            'btn_submit' => '+ Добавить новый товар',
        ];

        try {
            $products = $this->productService->getAllProduct();

            $vars = $vars + [
                    'form_action' => $this->generateUrl('add_product'),
                    'states' => array_flip(State::getArrStateConstant()),
                    'products' => $products,
                    'update_url' => $this->generateUrl('read_product'),
                ];

            $template = 'admin/list_product.html.twig';

        } catch (\Throwable $e) {
            $response = new Response($e->getMessage(), 400);
            $vars = $vars + [
                    'error' => $response
                ];
            $template = 'error.html.twig';
        }

        return $this->render($template, $vars+ [

            ]);
    }

    #[Route('/product', name: 'add_product', methods: ['get'])]
    public function addProductAction(Request $request): Response
    {
        $vars = [
            'form_title' => 'Добавить новый товар',
            'btn_submit'=> 'Сохранить',
        ];

        try {
            // $categories = $this->categoryService->getAllCategory();
            $categories = $this->categoryService->getArrValueCategoryDetail();

            $vars = $vars + [
                    'states' => State::getArrStateConstant(),
                    'state' => State::STATE_DISABLE,
                    'code' => null,
                    'name' => null,
                    'price' => null,
                    'cost' => null,
                    'categories' => $categories,
                    'select' => Category::WITHOUT_CATEGORY,
                ];

            $template = 'admin/card_product.html.twig';

        } catch (\Throwable $e) {
            $response = new Response($e->getMessage(), 400);
            $vars = $vars + [
                    'error' => $response
                ];
            $template = 'error.html.twig';
        }

        return $this->render($template, $vars + [
                'form_action' => $this->generateUrl('create_product'),
            ]);
    }

    #[Route('/product/create', name: 'create_product', methods: ['post'])]
    public function createProductAction(Request $request): Response
    {
//        print_r($request->request->all());
        try {
            $this->productService->createProduct(function ($key) use ($request) {
                return $request->request->get($key);
            });

            $response = $this->redirectToRoute('all_product');
        } catch (\Throwable $e) {
            $template = 'error.html.twig';
            $response = $this->render($template, [
                'error' => new Response($e->getMessage(), 400)
            ]);
        }
        return $response;
    }


    #[Route('/product/{product_id}', name: 'read_product', requirements: ['product_id' => '[0-9]{1,5}'], methods: ['get'])]
    public function readProductAction($product_id=0): Response
    {
        $vars = [
            'form_title' => 'Отредактировать существующий товар',
            'btn_submit'=> 'Сохранить изменения',
        ];

        try {
//            $categories = $this->categoryService->getArrPropertyFromCategory('name');
            //$categories = $this->categoryService->getAllCategory();

            $categories = $this->categoryService->getArrValueCategoryDetail();
            $product = $this->productService->getProductById($product_id);

            $vars = $vars + [
                    'states' => State::getArrStateConstant(),
                    'state' => $product->getState(),
                    'code' => $product->getCode(),
                    'name' => $product->getName(),
                    'price' => $product->getPrice(),
                    'cost' => $product->getCost(),
                    'categories' => $categories,
                    'select' => $product->getCategory()->getId(),
                ];

            $template = 'admin/card_product.html.twig';
        } catch (\Throwable $e) {
            $response = new Response($e->getMessage(), 400);
            $vars = $vars + [
                    'error' => $response
                ];
            $template = 'error.html.twig';
        }

        return $this->render($template, $vars + [
                'form_action' =>$this->generateUrl('update_product', array('product_id'=>$product_id))
            ]);
    }


    #[Route('/product/{product_id}/update', name: 'update_product', requirements: ['product_id' => '[0-9]{1,5}'], methods: ['post'])]
    public function updateProductAction(Request $request, $product_id): Response
    {
        try {

            'Category current: ' . $currCategoryId = $request->request->get('categories');
            $currCategory = $this->categoryService->getCategoryById($currCategoryId);

            $this->productService->updateProductById(function ($key) use ($request) {
                return $request->request->get($key);
            }, $currCategory, $product_id);

            $response = $this->redirectToRoute('all_product');
        } catch (\Throwable $e) {
            $template = 'error.html.twig';
            $response = $this->render($template, [
                'error' => new Response($e->getMessage(), 400)
            ]);
        }
        return $response;
    }


    #[Route('/product/{product_id}/delete', name: 'delete_product', requirements: ['product_id' => '[0-9]{1,5}'], methods: ['get'])]
    public function deleteProductAction($product_id): Response
    {
        try {
            $this->productService->deleteProductById($product_id);

            $response = $this->redirectToRoute('all_product');
        } catch (\Throwable $e) {
            $template = 'error.html.twig';
            $response = $this->render($template, [
                'error' => new Response($e->getMessage(), 400)
            ]);
        }
        return $response;
    }


    #[Route('/categories', name: 'all_category', methods: ['get'])]
    public function allCategoryAction(Request $request): Response
    {
        $vars = [
            'form_title' => 'Категории',
            'btn_submit' => '+ Создать новую категорию',
        ];

        try {
            $categories = $this->categoryService->getAllCategory();

            $vars = $vars + [
                    'states'=>array_flip(State::getArrStateConstant()),
                    'categories'=>$categories,
                    'update_url'=>$this->generateUrl('read_category'),
                ];

            $template = 'admin/list_category.html.twig';

        } catch (\Throwable $e) {
            $response = new Response($e->getMessage(), 400);
            $vars = $vars + [
                    'error' => $response
                ];
            $template = 'error.html.twig';
        }

        return $this->render($template, $vars + [
                'form_action' => $this->generateUrl('add_category')
            ]);
    }


    #[Route('/category', name: 'add_category', methods: ['get'])]
    public function addCategoryAction(Request $request): Response
    {
        $vars = [
            'form_title' => 'Создать новую категорию',
            'btn_submit' => 'Сохранить',
            'states' => State::getArrStateConstant(),
            'state' => State::STATE_DISABLE,
            'units' => Unit::getArrUnitConstant(),
            'unit' => Unit::UNDEFINED,
            'name' => '',
        ];

        $template = 'admin/card_category.html.twig';

        return $this->render($template, $vars + [
                'form_action' => $this->generateUrl('create_category')
            ]);
    }

    #[Route('/category/create', name: 'create_category', methods: ['post'])]
    public function createCategoryAction(Request $request): Response
    {
        try {
            $this->categoryService->createCategory(function ($key) use ($request) {
                return $request->request->get($key);
            });

            $response = $this->redirectToRoute('all_category');
        } catch (\Throwable $e) {
            $template = 'error.html.twig';
            $response = $this->render($template, [
                'error' => new Response($e->getMessage(), 400)
            ]);
        }
        return $response;
    }

    #[Route('/category/{category_id}', name: 'read_category', requirements: ['category_id' => '[0-9]{1,5}'], methods: ['get'])]
    public function readCategoryAction($category_id=0): Response
    {
        $vars = [
            'form_title' => 'Обновит существующую категорию',
            'btn_submit' => 'Сохранить изменения',
        ];

        try {
            $category = $this->categoryService->getCategoryById($category_id);

            $vars = $vars + [
                    'states' => State::getArrStateConstant(),
                    'state' => $category->getState(),
                    'name' => $category->getName(),
                    'units' => Unit::getArrUnitConstant(),
                    'unit' => $category->getUnit(),
            ];

            $template = 'admin/card_category.html.twig';
        } catch (\Throwable $e) {
            $response = new Response($e->getMessage(), 400);
            $vars = $vars + [
                    'error' => $response
                ];
            $template = 'error.html.twig';
        }

        return $this->render($template, $vars + [
                'form_action' =>$this->generateUrl('update_category', array('category_id'=>$category_id))
            ]);
    }

    #[Route('/category/{category_id}/update', name: 'update_category', requirements: ['category_id' => '[0-9]{1,5}'], methods: ['post'])]
    public function updateCategoryAction(Request $request, $category_id): Response
    {
        try {
////////////////////////////////////////////////////////////////
            $this->categoryService->updateCategoryById($category_id, function ($key) use ($request) {
                return $request->request->get($key);
            });

            $response = $this->redirectToRoute('all_category');
        } catch (\Throwable $e) {
            $template = 'error.html.twig';
            $response = $this->render($template, [
                'error' => new Response($e->getMessage(), 400)
            ]);
        }
        return $response;
    }

    #[Route('/category/{category_id}/delete', name: 'delete_category', requirements: ['category_id' => '[0-9]{1,5}'], methods: ['get'])]
    public function deleteCategoryAction($category_id): Response
    {
        try {
            $this->categoryService->deleteCategoryById($category_id);

            $response = $this->redirectToRoute('all_product');
        } catch (\Throwable $e) {
            $template = 'error.html.twig';
            $response = $this->render($template, [
                'error' => new Response($e->getMessage(), 400)
            ]);
        }
        return $response;
    }

    #[Route('/pages', name: 'all_page', methods: ['get'])]
    public function allPageAction(Request $request): Response
    {
        $vars = [
            'form_title' => 'Страницы калькулятора',
            'btn_submit' => '+ Создать новую страницу',
        ];

        try {
            $pages = $this->pageService->getAllPage();

            $vars = $vars + [
                    'states'=>array_flip(State::getArrStateConstant()),
                    'pages'=>$pages,
                    'update_url'=>$this->generateUrl('read_page'),
                ];

            $template = 'admin/list_page.html.twig';

        } catch (\Throwable $e) {
            $response = new Response($e->getMessage(), 400);
            $vars = $vars + [
                    'error' => $response
                ];
            $template = 'error.html.twig';
        }

        return $this->render($template, $vars + [
                'form_action' => $this->generateUrl('add_page')
            ]);
    }


    #[Route('/page', name: 'add_page', methods: ['get'])]
    public function addPageAction(Request $request): Response
    {
        $vars = [
            'form _title' => 'Создать новую страницу',
            'btn_submit' => 'Сохранить',
            'states' => State::getArrStateConstant(),
            'state' => State::STATE_DISABLE,
            'name' => '',
            'selected' => [],
        ];

        try {
            $categories = $this->categoryService->getAllCategory();
            $vars = $vars + [
                    'categories' => $categories,
                    //'select' => $product->getCategory()->getId(),
                ];

            $template = 'admin/card_page.html.twig';
        } catch (\Throwable $e) {
            $response = new Response($e->getMessage(), 400);
            $vars = $vars + [
                    'error' => $response
                ];
            $template = 'error.html.twig';
        }

        return $this->render($template, $vars + [
                'form_action' => $this->generateUrl('create_page')
            ]);
    }

    #[Route('/page/create', name: 'create_page', methods: ['post'])]
    public function createPageAction(Request $request): Response
    {
        try {
            $this->pageService->createPage(function ($key) use ($request) {
                return $request->request->get($key);
            });

            $response = $this->redirectToRoute('all_page');
        } catch (\Throwable $e) {
            $template = 'error.html.twig';
            $response = $this->render($template, [
                'error' => new Response($e->getMessage(), 400)
            ]);
        }
        return $response;
    }

    #[Route('/page/{page_id}', name: 'read_page', requirements: ['page_id' => '[0-9]{1,5}'], methods: ['get'])]
    public function readPageAction($page_id=0): Response
    {
        $vars = [
            'form_title' => 'Обновит существующую страницу',
            'btn_submit' => 'Сохранить изменения',
        ];

        try {
            $categories = $this->categoryService->getArrValueCategoryDetail();//getAllCategory();
            $page = $this->pageService->getPageById($page_id);
//            $result = $page->getCategories();
//            foreach($result as $option)
//            {
//                echo $option->getId();
//            }
            $selected = $this->pageService->getArrCategoryDetailById($page_id);

            $vars = $vars + [
                    'states' => State::getArrStateConstant(),
                    'state' => $page->getState(),
                    'name' => $page->getName(),
                    'categories' => $categories,
                    'selected' => array_keys($selected),
                    'form_action' =>$this->generateUrl('update_page', array('page_id'=>$page_id)),
                ];

            $template = 'admin/card_page.html.twig';
        } catch (\Throwable $e) {
            $response = new Response($e->getMessage(), 400);
            $vars = $vars + [
                    'error' => $response
                ];
            $template = 'error.html.twig';
        }

        return $this->render($template, $vars);
    }

    #[Route('/page/{page_id}/update', name: 'update_page', requirements: ['page_id' => '[0-9]{1,5}'], methods: ['post'])]
    public function updatePageAction(Request $request, $page_id): Response
    {
        $result = $request->request->all();
        $formSelected = $result['choices']; //$request->request->get('selected');

        try {

            $allCategories = $this->categoryService->getAllCategory();
            $currPage = $this->pageService->getPageById($page_id);
            $pageCategories = $this->pageService->getArrCategoryDetailById($page_id);

            foreach ($allCategories as $category)
            {
                $category_id = $category->getId();

                if(in_array($category_id, $formSelected))
                {// если есть на форме
                    if(!array_key_exists($category_id, $pageCategories)){
                        // если нет на странице
                        // echo 'Add to page ' . $category_id , '; ';
                        $currPage->addCategory($category);
                    }
                } else {
                    if(array_key_exists($category_id, $pageCategories)){
                        $currPage->removeCategory($category);
                    }
                }
            }

            $this->pageService->updatePage($currPage);
//////////////////////////////////////////////////////////
            $response = $this->redirectToRoute('all_page');
        } catch (\Throwable $e) {
            $template = 'error.html.twig';
            $response = $this->render($template, [
                'error' => new Response($e->getMessage(), 400)
            ]);
        }
        return $response;
    }

    #[Route('/page/{page_id}/delete', name: 'delete_page', requirements: ['page_id' => '[0-9]{1,5}'], methods: ['get'])]
    public function deletePageAction($page_id): Response
    {
        try {
            $this->categoryService->deleteCategoryById($page_id);

            $response = $this->redirectToRoute('all_page');
        } catch (\Throwable $e) {
            $template = 'error.html.twig';
            $response = $this->render($template, [
                'error' => new Response($e->getMessage(), 400)
            ]);
        }
        return $response;
    }
}