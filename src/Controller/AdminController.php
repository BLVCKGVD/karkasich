<?php

namespace App\Controller;

use App\Form\SeoType;
use App\Repository\SeoRepository;
use App\Service\SeoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin_login')]
    public function login(): Response
    {
        return $this->render('admin/login.html.twig', [
            'controller_name' => 'Каркасыч (Админ - логин)',
            'description' => 'Авторизация к админке'
        ]);
    }

    #[Route('/admin/main', name: 'app_admin_main')]
    public function main(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'Каркасыч (Админ - главная)',
            'description' => 'Админка'
        ]);
    }

    #[Route('/admin/seo', name: 'app_admin_seo')]
    public function seo(Request       $request,
                        SeoService    $seoService,
                        SeoRepository $seoRepository): Response
    {
        $seoCreateForm = $this->createForm(SeoType::class);
        $seoCreateForm->handleRequest($request);
        if ($seoCreateForm->isSubmitted() && $seoCreateForm->isValid()) {
            $seoService->create($seoCreateForm->getData());
        }
        $seo = $seoRepository->findAll();
        return $this->render('admin/seo.html.twig', [
            'controller_name' => 'Каркасыч (Админ - SEO)',
            'description' => 'Админка',
            'seo' => $seo,
            'seoCreateForm' => $seoCreateForm->createView(),

        ]);
    }

    #[Route('/admin/seo/delete/{id}', name: 'app_admin_seo_delete')]
    public function seoDelete(SeoRepository $seoRepository,
                              SeoService    $seoService,
                                            $id)
    {
        $seoService->delete($seoRepository->find($id));
        return $this->redirectToRoute('app_admin_seo');
    }
}
