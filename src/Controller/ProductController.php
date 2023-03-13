<?php

namespace App\Controller;

use App\Form\OrderType;
use App\Repository\ProductRepository;
use App\Service\SeoService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    #[Route('/product/{id}', name: 'app_product')]
    public function index(EntityManagerInterface $entityManager,
                          Request                $request,
                          SeoService             $seoService,
                                                 $id,
                          ProductRepository      $productRepository): Response
    {
        $seo = $seoService->findByPath($request->getPathInfo());
        $product = $productRepository->find($id);
        $orderForm = $this->createForm(OrderType::class);
        $orderForm->handleRequest($request);
        if ($orderForm->isSubmitted()) {
            if ($orderForm->isValid()) {
                echo $orderForm->get('phone')->getData();
                $order = $orderForm->getData();
                $order->setCreatedAt(new \DateTimeImmutable('now', new \DateTimeZone('Europe/Moscow')));
                $entityManager->persist($order);
                $entityManager->flush();
                return $this->redirect($request->getUri());
            }
        }

        return $this->render('main/product.html.twig', [
            'controller_name' => 'Каркасыч (главная)',
            'seo' => $seo,
            'product' => $product,
            'orderForm' => $orderForm->createView()
        ]);
    }
}
