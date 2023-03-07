<?php

namespace App\Entity;

use App\Repository\MainPageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MainPageRepository::class)]
class MainPage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $mainText = null;

    #[ORM\Column(length: 255)]
    private ?string $advantage1 = null;

    #[ORM\Column(length: 255)]
    private ?string $advantage2 = null;

    #[ORM\Column(length: 255)]
    private ?string $advantage3 = null;

    #[ORM\Column(type: Types::ARRAY, nullable: true)]
    private array $images = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMainText(): ?string
    {
        return $this->mainText;
    }

    public function setMainText(string $mainText): self
    {
        $this->mainText = $mainText;

        return $this;
    }

    public function getAdvantage1(): ?string
    {
        return $this->advantage1;
    }

    public function setAdvantage1(?string $advantage1): self
    {
        $this->advantage1 = $advantage1;

        return $this;
    }

    public function getAdvantage2(): ?string
    {
        return $this->advantage2;
    }

    public function setAdvantage2(?string $advantage2): self
    {
        $this->advantage2 = $advantage2;

        return $this;
    }

    public function getAdvantage3(): ?string
    {
        return $this->advantage3;
    }

    public function setAdvantage3(?string $advantage3): self
    {
        $this->advantage3 = $advantage3;

        return $this;
    }

    public function getImages(): ?array
    {
        return $this->images;
    }

    public function setImages(?array $images): self
    {
        $this->images = $images;

        return $this;
    }

    public function addImage($image)
    {
        $this->images[] = $image;

        return $this;
    }
}
