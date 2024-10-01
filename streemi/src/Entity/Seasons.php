<?php

namespace App\Entity;

use App\Repository\SeasonsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SeasonsRepository::class)]
class Seasons
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $seasonNumber = null;

    #[ORM\ManyToOne(inversedBy: 'seasons')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Serie $SerieId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeasonNumber(): ?int
    {
        return $this->seasonNumber;
    }

    public function setSeasonNumber(int $seasonNumber): static
    {
        $this->seasonNumber = $seasonNumber;

        return $this;
    }

    public function getSerieId(): ?Serie
    {
        return $this->SerieId;
    }

    public function setSerieId(?Serie $SerieId): static
    {
        $this->SerieId = $SerieId;

        return $this;
    }
}
