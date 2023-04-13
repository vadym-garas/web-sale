<?php

namespace App\Controller;


use App\FrameCalculator\Interfaces\ICalculateFrame;
use App\Services\AbstractEntityService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

//#[Route('/category')]
class MainController extends AbstractController
{
    /**
     * @param ICalculateFrame $calcFrame
     */
    public function __construct(
        protected ICalculateFrame $calculator,
//        protected AbstractEntityService $calcService
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


    #[Route('/', methods: ['GET'])]
    public function mainAction(): Response
    {
        //        return new Response('<html><content><h1>Hello World!</h1></content></html>');
        return $this->render('pages/main.html.twig');
    }
}