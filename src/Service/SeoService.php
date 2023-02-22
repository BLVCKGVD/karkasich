<?php

namespace App\Service;

use App\Entity\Seo;
use Doctrine\ORM\EntityManagerInterface;

class SeoService
{
    private EntityManagerInterface $entityManager;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function create(Seo $seo): void
    {
        $this->entityManager->persist($seo);
        $this->entityManager->flush();
    }

    public function delete(Seo $seo): void
    {
        $this->entityManager->remove($seo);
        $this->entityManager->flush();
    }

    public function findByPath(string $path): Seo
    {
        return $this->entityManager->getRepository(Seo::class)->findOneBy([
            'path' => $path
        ]);
    }
}