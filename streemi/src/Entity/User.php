<?php

namespace App\Entity;

use App\Enum\UserAccountStatusEnum;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $username = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(enumType: UserAccountStatusEnum::class)]
    private ?UserAccountStatusEnum $accountStatus = UserAccountStatusEnum::INACTIVE;

    #[ORM\Column(type: "json")]
    private array $roles = [];

    #[ORM\ManyToOne(inversedBy: 'users')]
    private ?Subscription $currentSubscription = null;

    #[ORM\OneToMany(targetEntity: Playlist::class, mappedBy: 'user', cascade: ['persist', 'remove'])]
    private Collection $ownedPlaylists;

    #[ORM\OneToMany(targetEntity: Playlist::class, mappedBy: 'creator', cascade: ['persist', 'remove'])]
    private Collection $createdPlaylists;

  

    public function __construct()
    {
        $this->ownedPlaylists = new ArrayCollection();
        $this->createdPlaylists = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getAccountStatus(): ?UserAccountStatusEnum
    {
        return $this->accountStatus;
    }

    public function setAccountStatus(UserAccountStatusEnum $accountStatus): self
    {
        $this->accountStatus = $accountStatus;

        return $this;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials(): void
    {
        // Clear sensitive data if needed
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getCurrentSubscription(): ?Subscription
    {
        return $this->currentSubscription;
    }

    public function setCurrentSubscription(?Subscription $subscription): self
    {
        $this->currentSubscription = $subscription;

        return $this;
    }

    public function getOwnedPlaylists(): Collection
    {
        return $this->ownedPlaylists;
    }

    public function addOwnedPlaylist(Playlist $playlist): self
    {
        if (!$this->ownedPlaylists->contains($playlist)) {
            $this->ownedPlaylists->add($playlist);
            $playlist->setUser($this);
        }

        return $this;
    }

    public function removeOwnedPlaylist(Playlist $playlist): self
    {
        if ($this->ownedPlaylists->removeElement($playlist)) {
            if ($playlist->getUser() === $this) {
                $playlist->setUser(null);
            }
        }

        return $this;
    }

    public function getCreatedPlaylists(): Collection
    {
        return $this->createdPlaylists;
    }

    public function addCreatedPlaylist(Playlist $playlist): self
    {
        if (!$this->createdPlaylists->contains($playlist)) {
            $this->createdPlaylists->add($playlist);
            $playlist->setCreator($this);
        }

        return $this;
    }

    public function removeCreatedPlaylist(Playlist $playlist): self
    {
        if ($this->createdPlaylists->removeElement($playlist)) {
            if ($playlist->getCreator() === $this) {
                $playlist->setCreator(null);
            }
        }

        return $this;
    }

    
}