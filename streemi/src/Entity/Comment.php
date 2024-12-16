<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'text')]
    private ?string $content = null;

    #[ORM\Column]
    private ?string $status = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'childComments')]
    private ?self $parentComment = null;

    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parentComment')]
    private Collection $childComments;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $publisher = null;

    #[ORM\ManyToOne(targetEntity: Media::class, inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Media $media = null;

    public function __construct()
    {
        $this->childComments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getParentComment(): ?self
    {
        return $this->parentComment;
    }

    public function setParentComment(?self $parentComment): static
    {
        $this->parentComment = $parentComment;

        return $this;
    }

    public function getChildComments(): Collection
    {
        return $this->childComments;
    }

    public function addChildComment(self $childComment): static
    {
        if (!$this->childComments->contains($childComment)) {
            $this->childComments->add($childComment);
            $childComment->setParentComment($this);
        }

        return $this;
    }

    public function removeChildComment(self $childComment): static
    {
        if ($this->childComments->removeElement($childComment)) {
            if ($childComment->getParentComment() === $this) {
                $childComment->setParentComment(null);
            }
        }

        return $this;
    }

    public function getPublisher(): ?User
    {
        return $this->publisher;
    }

    public function setPublisher(?User $publisher): static
    {
        $this->publisher = $publisher;

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
