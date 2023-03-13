<?php

namespace App\Controller;

use App\Form\MainPageType;
use App\Form\ProductEditType;
use App\Form\ProductType;
use App\Form\SeoEditType;
use App\Form\SeoType;
use App\Repository\MainPageRepository;
use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
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
            return $this->redirectToRoute($request->get('_route'),$request->query->all());
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
            return $this->redirectToRoute($request->get('_route'),$request->query->all());
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
            return $this->redirectToRoute($request->get('_route'),$request->query->all());
        }
        return $this->render('admin/pages/mainPage.html.twig', [
            'controller_name' => 'Каркасыч (Админ - Страницы - Главная)',
            'description' => 'Админка',
            'mainPage' => $mainPage,
            'mainPageForm' => $mainPageForm->createView(),
        ]);
    }

    #[Route('/admin/pages/mainPage/delete/image/{name}', name: 'app_admin_pages_main_page_delete_image')]
    public function deleteMainPageImage(
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
            unlink($destination . $name);
        }
        $mainPage->setImages($images);
        $entityManager->persist($mainPage);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_pages_main_page');
    }

    #[Route('/admin/orders/', name: 'app_admin_orders')]
    public function getOrders(OrderRepository $orderRepository): Response
    {
        $newOrders = $orderRepository->findBy([
            "status" => "new"
        ]);
        $readOrders = $orderRepository->findBy([
            "status" => "read"
        ]);
        return $this->render('admin/orders.html.twig', [
            'controller_name' => 'Каркасыч (Админ - Заказы)',
            'description' => 'Админка',
            'newOrders' => $newOrders,
            'readOrders' => $readOrders
        ]);
    }

    #[Route('/admin/orders/read/{id}', name: 'app_admin_orders_read')]
    public function readOrder(OrderRepository $orderRepository, $id, EntityManagerInterface $entityManager): Response
    {
        $order = $orderRepository->find($id);
        $order->setStatus("read");
        $entityManager->persist($order);
        $entityManager->flush();
        return $this->redirectToRoute("app_admin_orders");
    }

    #[Route('/admin/products', name: 'app_admin_products')]
    public function products(Request $request, ProductRepository $productRepository, EntityManagerInterface $entityManager)
    {
        $productCreateForm = $this->createForm(ProductType::class);
        $productCreateForm->handleRequest($request);
        if ($productCreateForm->isSubmitted() && $productCreateForm->isValid()) {
            $product = $productCreateForm->getData();
            $images = $productCreateForm->get('images')->getData();
            foreach ($images as $image) {
                $uploadedFile = $image;
                $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $product->addImage($newFilename);
            }
            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirectToRoute($request->get('_route'),$request->query->all());
        }
        $productEditForm = $this->createForm(ProductEditType::class);
        $productEditForm->handleRequest($request);
        if ($productEditForm->isSubmitted() && $productEditForm->isValid()) {
            $product = $productRepository->find($productEditForm->get('id')->getData());
            if ($product) {
                $product
                    ->setName($productEditForm->get('name')->getData())
                    ->setDescription($productEditForm->get('description')->getData())
                    ->setCost($productEditForm->get('cost')->getData())
                    ->setInMain($productEditForm->get('inMain')->getData())
                    ->setIsEnabled($productEditForm->get('isEnabled')->getData());
            }
            $images = $productEditForm->get('images')->getData();
            foreach ($images as $image) {
                $uploadedFile = $image;
                $destination = $this->getParameter('kernel.project_dir') . '/public/uploads';
                $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();
                $uploadedFile->move(
                    $destination,
                    $newFilename
                );
                $product->addImage($newFilename);
            }
            $entityManager->persist($product);
            $entityManager->flush();
            return $this->redirectToRoute($request->get('_route'),$request->query->all());
        }
        $products = $productRepository->findAll();
        return $this->render('admin/products.html.twig',
            [
                'productCreateForm' => $productCreateForm,
                'productEditForm' => $productEditForm,
                'products' => $products
            ]);
    }

    #[Route('/admin/product/delete/{id}', name: 'app_admin_product_delete')]
    public function productDelete(ProductRepository $productRepository, $id, EntityManagerInterface $entityManager)
    {
        $product = $productRepository->find($id);
        $entityManager->remove($product);
        $entityManager->flush();
        return $this->redirectToRoute('app_admin_products');
    }

    #[Route('/admin/pages/products/{id}/delete/image/{name}', name: 'app_admin_pages_product_delete_image')]
    public function deleteProductImage(
        $id,
        $name,
        ProductRepository $productRepository,
        EntityManagerInterface $entityManager): Response
    {
        $product = $productRepository->find($id);
        $images = $product->getImages();
        if (($key = array_search($name, $images)) !== false) {
            unset($images[$key]);
        }
        if ($name != 'caroulsel_void.jpg') {
            $destination = $this->getParameter('kernel.project_dir') . '/public/uploads/';
            unlink($destination . $name);
        }
        $product->setImages($images);
        $entityManager->persist($product);
        $entityManager->flush();

        return $this->redirectToRoute('app_admin_products');
    }
}
