<?php

namespace App\Controller;

use App\Form\MainPageType;
use App\Form\SeoEditType;
use App\Form\SeoType;
use App\Repository\MainPageRepository;
use App\Repository\SeoRepository;
use App\Service\SeoService;
use Doctrine\ORM\EntityManagerInterface;
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
    public function seo(Request                $request,
                        SeoService             $seoService,
                        SeoRepository          $seoRepository,
                        EntityManagerInterface $entityManager): Response
    {
        $seoCreateForm = $this->createForm(SeoType::class);
        $seoCreateForm->handleRequest($request);
        if ($seoCreateForm->isSubmitted() && $seoCreateForm->isValid()) {
            $seoService->create($seoCreateForm->getData());
        }

        $seoEditForm = $this->createForm(SeoEditType::class);
        $seoEditForm->handleRequest($request);
        if ($seoEditForm->isSubmitted() && $seoEditForm->isValid()) {
            $seo = $seoRepository->find($seoEditForm->get('id')->getData());
            if ($seo) {
                $seo
                    ->setTitle($seoEditForm->get('title')->getData())
                    ->setDescription($seoEditForm->get('description')->getData())
                    ->setPath($seoEditForm->get('path')->getData())
                    ->setRobots($seoEditForm->get('robots')->getData());
            }
            $seoService->create($seo);
        }
        $seo = $seoRepository->findAll();
        return $this->render('admin/seo.html.twig', [
            'controller_name' => 'Каркасыч (Админ - SEO)',
            'description' => 'Админка',
            'seo' => $seo,
            'seoCreateForm' => $seoCreateForm->createView(),
            'seoEditForm' => $seoEditForm->createView(),

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

    #[Route('/admin/calc/profList/', name: 'app_admin_calc_profList')]
    public function calcProfList(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'Каркасыч (Админ - Калькулятор проф листа)',
            'description' => 'Админка'
        ]);
    }

    #[Route('/admin/pages/mainPage', name: 'app_admin_pages_main_page')]
    public function mainPage(Request                $request,
                             MainPageRepository     $mainPageRepository,
                             EntityManagerInterface $entityManager): Response
    {
        $mainPage = $mainPageRepository->findAll()[0];
        $mainPageForm = $this->createForm(MainPageType::class, $mainPage);
        $mainPageForm->handleRequest($request);
        if ($mainPageForm->isSubmitted() && $mainPageForm->isValid()) {
            $mainPage = $mainPageForm->getData();
            $images = $mainPageForm->get('images')->getData();
            foreach ($images as $image) {
                $uploadedFile = $image;
                $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $mainPage->addImage($newFilename);
            }
            $entityManager->persist($mainPage);
            $entityManager->flush();
        }
        return $this->render('admin/pages/mainPage.html.twig', [
            'controller_name' => 'Каркасыч (Админ - Страницы - Главная)',
            'description' => 'Админка',
            'mainPage' => $mainPage,
            'mainPageForm' => $mainPageForm->createView(),
        ]);
    }

    #[Route('/admin/pages/mainPage/delete/image/{name}', name: 'app_admin_pages_main_page_delete_image')]
    public function deleteImage(
        $name,
        MainPageRepository $mainPageRepository,
        EntityManagerInterface $entityManager): Response
    {
        $mainPage = $mainPageRepository->findAll()[0];
        $images = $mainPage->getImages();
        if (($key = array_search($name, $images)) !== false) {
            unset($images[$key]);
        }
        if ($name != 'caroulsel_void.jpg') {
            $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/';
            unlink($destination.$name);
        }
        $mainPage->setImages($images);
        $entityManager->persist($mainPage);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_pages_main_page');
    }
}
