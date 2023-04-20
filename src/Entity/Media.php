<?php

namespace App\Entity;

use App\Repository\MediaRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MediaRepository::class)]
class Media
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $pictures = null;

    #[ORM\Column(length: 255)]
    private ?string $video = null;

    #[ORM\ManyToOne(inversedBy: 'media')]
    private ?Trick $trickId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPictures(): ?string
    {
        return $this->pictures;
    }

    public function setPictures(string $pictures): self
    {
        $this->pictures = $pictures;

        return $this;
    }

    public function getVideo(): ?string
    {
        return $this->video;
    }

    public function setVideo(string $video): self
    {
        $this->video = $video;

        return $this;
    }

    public function getTrickId(): ?Trick
    {
        return $this->trickId;
    }

    public function setTrickId(?Trick $trickId): self
    {
        $this->trickId = $trickId;

        return $this;
    }
}
