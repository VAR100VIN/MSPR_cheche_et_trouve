<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
class Image
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(mappedBy: 'idImage', cascade: ['persist', 'remove'])]
    private ?Plant $idPlant = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $url = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdPlant(): ?Plant
    {
        return $this->idPlant;
    }

    public function setIdPlant(?Plant $idPlant): self
    {
        // unset the owning side of the relation if necessary
        if ($idPlant === null && $this->idPlant !== null) {
            $this->idPlant->setIdImage(null);
        }

        // set the owning side of the relation if necessary
        if ($idPlant !== null && $idPlant->getIdImage() !== $this) {
            $idPlant->setIdImage($this);
        }

        $this->idPlant = $idPlant;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }
}
