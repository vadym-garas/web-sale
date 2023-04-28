<?php

namespace App\Controller;

use App\Entity\Category;
use App\Services\CategoryService;
use App\Entity\State;
use App\Form\CardCheckboxType;
use App\Services\PageService;
use App\Services\ProductService;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/api')]
class ApiController extends AbstractController
{

    public function __construct(
        protected CategoryService $categoryService,
    ){}

    #[Route('/get', name: 'api_get', methods: ['get'])]
    public function apiAction(): JsonResponse
    {
        return new JsonResponse([
            'status' => true,
            'message' => 'API is available'
        ]);
    }

    #[Route('/checkbox', name: 'checkbox_group', methods: ['get', 'post'])]
    public function readCheckboxAction(Request $request): Response
    {
        $categories = ['Category 1', 'Category 2', 'Category 3'];

        $form = $this->createFormBuilder()
            ->add('categories', ChoiceType::class, [
                'choices' => array_combine($categories, $categories),
                'multiple' => true,
            ])
            ->add('submit', SubmitType::class, ['label' => 'Submit'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $selectedCategories = $data['categories'];
            // Handle selected categories
            print_r($selectedCategories);
        }

        return $this->render('admin/card_checkbox.html.twig', [
            'form' => $form->createView(),
        ]);
    }

//    #[Route('/product', name: 'add_product', methods: ['get', 'post'])]
//    public function addProductAction(Request $request): Response
//    {
//        $vars = [
//            'form_title' => 'Добавить новый товар',
//            'btn_submit'=> 'Сохранить',
//        ];
//        $form = $this->createFormBuilder()
//            ->add('submit', SubmitType::class, ['label' => 'Submit'])
//            ->getForm();
//
//        try {
//            if ($form->isSubmitted()) {
//                $this->productService->createProduct(function ($key) use ($request) {
//                    return $request->request->get($key);
//                });
//                $vars = $vars + [
//                        'form_action' => $this->generateUrl('all_product'),
//                    ];
//            } else {
//                // $categories = $this->categoryService->getAllCategory();
//                $categories = $this->categoryService->getArrValueCategoryDetail();
//
//                $vars = $vars + [
//                        'states' => State::getArrStateConstant(),
//                        'state' => State::STATE_DISABLE,
//                        'code' => null,
//                        'name' => null,
//                        'price' => null,
//                        'cost' => null,
//                        'categories' => $categories,
//                        'select' => Category::WITHOUT_CATEGORY,
//                        'form' => $form->createView(),
//                        'form_action' => $this->generateUrl('add_product'),
//                    ];
//            }
//            $template = 'admin/card_product.html.twig';
//
//        } catch (\Throwable $e) {
//            $response = new Response($e->getMessage(), 400);
//            $vars = $vars + [
//                    'error' => $response
//                ];
//            $template = 'error.html.twig';
//        }
//
//        return $this->render($template, $vars);
//    }
}