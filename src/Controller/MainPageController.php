<?php

namespace App\Controller;

use App\Form\OrderType;
use App\Repository\MainPageRepository;
use App\Service\SeoService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainPageController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(EntityManagerInterface $entityManager, Request $request, SeoService $seoService, MainPageRepository $mainPageRepository): Response
    {
        $seo = $seoService->findByPath($request->getPathInfo());
        $page = $mainPageRepository->findAll()[0];
        $orderForm = $this->createForm(OrderType::class);
        $orderForm->handleRequest($request);
        if ($orderForm->isSubmitted()) {
            if ($orderForm->isValid()) {
                echo $orderForm->get('phone')->getData();
                $order = $orderForm->getData();
                $order->setCreatedAt(new \DateTimeImmutable('now',new \DateTimeZone('Europe/Moscow')));
                $entityManager->persist($order);
                $entityManager->flush();
                return $this->redirect($request->getUri());
            }
        }

        return $this->render('main/index.html.twig', [
            'controller_name' => 'Каркасыч (главная)',
            'seo' => $seo,
            'page' => $page,
            'orderForm' => $orderForm->createView()
        ]);
    }
}
