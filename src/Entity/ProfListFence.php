<?php

namespace App\Entity;

use App\Repository\ProfListFenceRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfListFenceRepository::class)]
class ProfListFence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $widthCost = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getWidthCost(): ?int
    {
        return $this->widthCost;
    }

    public function setWidthCost(int $widthCost): self
    {
        $this->widthCost = $widthCost;

        return $this;
    }
}
