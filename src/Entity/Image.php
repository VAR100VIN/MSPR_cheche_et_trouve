<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use phpDocumentor\Reflection\Types\Integer;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /*#[ORM\Column(type: Types::INTEGER)]
    private ?int $idplant = null;*/

    #[ORM\Column(type: Types::TEXT)]
    private ?string $url = null;

    #[ORM\ManyToOne(inversedBy: 'idImage')]
    private ?Plant $plant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    /*public function getIdplant(): ?int
    {
        return $this->idplant;
    }

    public function setIdplant(?int $idplant): self
    {
        $this->idplant = $idplant;
        return $this;
    }*/

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getPlant(): ?Plant
    {
        return $this->plant;
    }

    public function setPlant(?Plant $plant): self
    {
        $this->plant = $plant;

        return $this;
    }
}
