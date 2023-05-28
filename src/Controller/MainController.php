<?php

namespace App\Controller;


use App\Entity\State;
use App\FrameCalculator\Interfaces\ICalculateFrame;
use App\Services\AbstractEntityService;
use App\Services\CategoryService;
use App\Services\PageService;
use App\Services\ProductService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//#[Route('/category')]
class MainController extends AbstractController
{
    public function __construct(
        protected ProductService $productService,
        protected CategoryService $categoryService,
        protected PageService $pageService
        // protected ICalculateFrame $calculator,
    ){}


    #[Route('/hello/{test}', requirements: ['test' => '(\w+)?'], methods: ['GET'])]
    public function helloAction($test): Response
    {
        $vars = [
            'test' => $test,
        ];
        try {
            $response = new Response('Some data -- concatenation', 200);
            $vars = $vars + [
                    'url_info' => $response
                ];
            $template = 'url/url_hello.html.twig';

        } catch (\Throwable $e) {
            $response = new Response($e->getMessage(), 400);
            $vars = $vars + [
                    'error' => $response
                ];
            $template = 'error.html.twig';
        }
        return $this->render($template, $vars);
    }

    #[Route('/calculate', name: 'calculate_order', methods: ['get'])]
    public function calcOrderAction(Request $request): Response
    {
        $template = 'pages/calculate.html.twig';

        //print_r($request->toArray());
        $frame_height = 'WTF?'; //    ->get('frame_height');
        // echo $request->request->get('frame_height');

        // $calculate_price = $this->calculator->calculate(Request $request);

        return $this->render($template, [
            'form_action' => $this->container->get('router')->getRouteCollection()->get('result_order')->getPath(),
            'frame_height' => $frame_height
            //    'calculate_price' => $calculate_price
        ]);
    }

    #[Route('/result', name: 'result_order', methods: ['post'])]
    public function resultOrderAction(Request $request): Response
    {
        $template = 'pages/calculate.html.twig';
        print_r($request->request->all());
        $frame_height = 'WTF? ' . implode('; ', $request->request->all());
        // $frame_height = 'WTF?' . $request->request->get('frame_height');

        $params = $request->request->all();
        $calculate_price = $this->calculator->calculate($params);

        return $this->render($template, [
            'form_action' => $this->container->get('router')->getRouteCollection()->get('calculate_order')->getPath(),
            'calculate_price' => $calculate_price
        ]);
    }

    #[Route('/calculator/{page_id}', name: 'page_selector', requirements: ['page_id'=>'[0-9]{1,5}'], methods: ['get'])]
    public function pageSelectorAction(Request $request, $page_id=0): Response
    {
        $vars = [
            'form_title' => 'Расчет стоимости',
            'btn_submit' => 'рассчитать заказ',
        ];

        try {
            $pages = $this->pageService->getAllPage();
            $page = $this->pageService->getPageById($page_id);
            $categories = $page->getCategories();

            $vars = $vars + [
                    'categories' => $categories,
                    'pages' => $pages,
                    'state' => $page->getId(),
                ];

            $template = 'pages/main.html.twig';
        } catch (\Throwable $e) {
            $response = new Response($e->getMessage(), 400);
            $vars = $vars + [
                    'error' => $response
                ];
            $template = 'error.html.twig';
        }
        return $this->render($template, $vars + [
                //'form_action' => $this->generateUrl('calculate')
            ]);
    }

    #[Route('/calculator/post/', name: 'calculate',methods: ['post'])]
    public function calculateAction(Request $request): Response
    {
        try {
            //print_r($request->request->all());

            $response = $this->redirectToRoute('all_page');
        } catch (\Throwable $e) {
            $template = 'error.html.twig';
            $response = $this->render($template, [
                'error' => new Response($e->getMessage(), 400)
            ]);
        }
        return $response;
    }

    #[Route('/', methods: ['GET'])]
    public function mainAction(): Response
    {
        return new Response('<html><body><h1>Main Page</h1></body></html>');
    }


//    #[Route('/', methods: ['GET'])]
//    public function mainAction(): Response
//    {
//
//        $vars = [
//            'form_title' => 'Baguette Category Constructor',
//            'btn_submit' => '+ Add new Product',
//        ];
//
//        try {
//            $categories = $this->categoryService->getAllCategory();
//
//            $vars = $vars + [
//                    'state'=>array_flip(State::getArrStateConstant()),
//                    'categories'=>$categories,
//                ];
//
//
//            $template = 'pages/main.html.twig';
//
//        } catch (\Throwable $e) {
//            $response = new Response($e->getMessage(), 400);
//            $vars = $vars + [
//                    'error' => $response
//                ];
//            $template = 'error.html.twig';
//        }
//
//        return $this->render($template, $vars+ [
//
//            ]);
//    }
}