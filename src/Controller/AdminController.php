<?php

namespace App\Controller;

use App\Entity\State;
use App\Services\ProductService;
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

    public function __construct(protected ProductService $productService)
    {
    }


    #[Route('/products', name: 'all_product', methods: ['get'])]
    public function allProductAction(Request $request): Response
    {
        $vars = [
            'form_title' => 'All products table'
        ];

        try {
//          $response = new Response('Some data -- concatenation', 200);
            $products = $this->productService->getAllProduct();

            $vars = $vars + [
                    'products'=>$products,
                    'update_url'=>$this->generateUrl('read_product'),
                ];

            $template = 'admin/list_product.html.twig';

        } catch (\Throwable $e) {
            $response = new Response($e->getMessage(), 400);
            $vars = $vars + [
                    'error' => $response
                ];
            $template = 'error.html.twig';
        }

        return $this->render($template, $vars);
    }


    #[Route('/product', name: 'add_product', methods: ['get'])]
    public function addProductAction(Request $request): Response
    {
        $vars = [
            'form_title' => 'Create new product',
            'states' => State::getArrStateConstant(),
            'state' => State::STATE_DISABLE,
            'product_code' => '',
            'category_id' => '',
            'count' => '',
        ];

        $template = 'admin/card_product.html.twig';

        return $this->render($template, $vars + [
                'form_action' => $this->generateUrl('create_product')
            ]);
    }

    #[Route('/product/create', name: 'create_product', methods: ['post'])]
    public function createProductAction(Request $request): Response
    {
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
            'form_title' => 'Update an existing product',
        ];

        try {
            // $response = new Response('Some data -- concatenation', 200);

            $product = $this->productService->getProductById($product_id);

//            $class_vars = $product->getClassVars();
//            print_r($class_vars);

            $vars = $vars + [
                    'states' => State::getArrStateConstant(),
                    'state' => $product->getState(),
                    'product_code' => $product->getProductCode(),
                    'category_id' => $product->getCategories(),
                    'count' => $product->getCount(),
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


    #[Route('/product/update/{product_id}', name: 'update_product', requirements: ['product_id' => '[0-9]{1,5}'], methods: ['post'])]
    public function updateProductAction(Request $request, $product_id): Response
    {
        try {

            $this->productService->updateProductById($product_id, function ($key) use ($request) {
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


    #[Route('/product/delete/{product_id}', name: 'delete_product', requirements: ['product_id' => '[0-9]{1,5}'], methods: ['get'])]
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


    #[Route('/category/add', name: 'add_category', methods: ['get'])]
    public function addCategoryAction(Request $request): Response
    {
        $vars = [
            'form_title' => 'Create new category'
        ];

        try {
            $response = new Response('Some data -- concatenation', 200);

            $vars = $vars + [
                    'states' => State::getArrStateConstant()
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
            'form_action' => $this->generateUrl('new_category')
        ]);
    }

}