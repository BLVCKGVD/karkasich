<?php

namespace App\Service;

use App\Entity\Seo;
use Doctrine\ORM\EntityManagerInterface;
use Exception;

class DateService
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    /**
     * @throws Exception
     */
    public function getCurrentDateString()
    {
        $dateTime = new \DateTimeImmutable('now', new \DateTimeZone('Europe/Moscow'));
        return $dateTime->format('Y-m-d H:i:s');
    }

    /**
     * @throws Exception
     */
    public function getCurrentDate()
    {
        return new \DateTimeImmutable('now',new \DateTimeZone('Europe/Moscow'));
    }
}