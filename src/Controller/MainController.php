<?php

namespace App\Controller;

use App\Service\SeoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(Request $request, SeoService $seoService): Response
    {
        $seo = $seoService->findByPath($request->getPathInfo());


        return $this->render('main/index.html.twig', [
            'controller_name' => 'Каркасыч (главная)',
            'seo' => $seo
        ]);
    }
}
