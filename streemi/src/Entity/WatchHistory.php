<?php

namespace App\Entity;

use App\Repository\WatchHistoryRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: WatchHistoryRepository::class)]
class WatchHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $lastWatched = null;

    #[ORM\Column]
    private ?int $numberOfViews;

    #[ORM\ManyToOne(inversedBy: 'watchHistories')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'watchHistories')]
    private ?Media $media = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLastWatched(): ?\DateTimeInterface
    {
        return $this->lastWatched;
    }

    public function setLastWatched(\DateTimeInterface $lastWatched): static
    {
        $this->lastWatched = $lastWatched;

        return $this;
    }

    public function getNumberOfViews(): ?int
    {
        return $this->numberOfViews;
    }

    public function setNumberOfViews(int $numberOfViews): static
    {
        $this->numberOfViews = $numberOfViews;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getMedia(): ?Media
    {
        return $this->media;
    }

    public function setMedia(?Media $media): static
    {
        $this->media = $media;

        return $this;
    }
}