<?php

namespace App\Controller;

use App\Repository\MainPageRepository;
use App\Service\SeoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainPageController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(Request $request, SeoService $seoService, MainPageRepository $mainPageRepository): Response
    {
        $seo = $seoService->findByPath($request->getPathInfo());
        $page = $mainPageRepository->findAll()[0];

        return $this->render('main/index.html.twig', [
            'controller_name' => 'Каркасыч (главная)',
            'seo' => $seo,
            'page' => $page
        ]);
    }
}
