<?php

namespace App\Service;

use App\Entity\Order;
use App\Entity\Seo;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class OrderService
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }



    public function getOrdersCount()
    {
        $orders = $this->entityManager->getRepository(Order::class)->findBy([
            "status" => "new"
        ]);
        return count($orders);
    }
}