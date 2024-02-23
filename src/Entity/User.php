<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $username = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $profilePictures = null;

    #[ORM\Column(nullable: true)]
    private ?bool $Gender = null;

    #[ORM\Column(type: "datetime_immutable",nullable: true)]
    private ?\DateTimeImmutable $createdDate = null;

     /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="sender")
     */
    private Collection $sentMessages;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="receiver")
     */
    private Collection $receivedMessages;

    /**
     * @ORM\OneToMany(targetEntity=Game::class, mappedBy="user")
     */
    private Collection $games;

     /**
     * @ORM\OneToMany(targetEntity="Friendship", mappedBy="requester")
     */
    private Collection $sentFriendRequests;

    /**
     * @ORM\OneToMany(targetEntity="Friendship", mappedBy="addressee")
     */
    private Collection $receivedFriendRequests;

    #[ORM\Column(type: "boolean")]
    private ?bool $isAdmin = false;

    #[ORM\Column(type: "boolean")]
    private ?bool $isBlocked = false;

    #[ORM\Column(type: "boolean")]
    private ?bool $canSendMessages = true;

    #[ORM\Column(type: "boolean")]
    private ?bool $canSendFriendRequests = true;

    public function getSentFriendRequests(): Collection
    {
        return $this->sentFriendRequests;
    }

    public function getReceivedFriendRequests(): Collection
    {
        return $this->receivedFriendRequests;
    }

    public function __construct()
    {
        $this->games = new ArrayCollection();
          // Initialize collections
        $this->sentFriendRequests = new ArrayCollection();
        $this->receivedFriendRequests = new ArrayCollection();
        $this->sentMessages = new ArrayCollection();
        $this->receivedMessages = new ArrayCollection();
        $this->canSendMessages = false; // false for some reason = 0
        $this->canSendFriendRequests = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getProfilePictures(): ?string
    {
        return $this->profilePictures;
    }

    public function setProfilePictures(?string $profilePictures): static
    {
        $this->profilePictures = $profilePictures;

        return $this;
    }

    public function isGender(): ?bool
    {
        return $this->Gender;
    }

    public function setGender(bool $Gender): static
    {
        $this->Gender = $Gender;

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeImmutable
    {
        return $this->createdDate;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedDateValue(): void
    {
        $this->createdDate = new \DateTimeImmutable();
    }

   /**
     * @return Collection<int, Game>
     */
    public function getGames(): Collection
    {
        return $this->games;
    }
    public function addGame(Game $game): self
    {
        if (!$this->games->contains($game)) {
            $this->games[] = $game;
            $game->setUser($this);
        }

        return $this;
    }

    public function removeGame(Game $game): self
    {
        if ($this->games->removeElement($game)) {
            // set the owning side to null (unless already changed)
            if ($game->getUser() === $this) {
                $game->setUser(null);
            }
        }

        return $this;
    }
    /**
     * @return Collection|Message[]
     */
    public function getSentMessages(): Collection
    {
        return $this->sentMessages;
    }

    /**
     * @return Collection|Message[]
     */
    public function getReceivedMessages(): Collection
    {
        return $this->receivedMessages;
    }

    public function addSentMessage(Message $message): self
    {
        if (!$this->sentMessages->contains($message)) {
            $this->sentMessages[] = $message;
            $message->setSender($this);
        }

        return $this;
    }

    public function removeSentMessage(Message $message): self
    {
        if ($this->sentMessages->removeElement($message)) {
            if ($message->getSender() === $this) {
              // check if the message still references this user as the sender
            }
        }
    
        return $this;
    }

    public function addReceivedMessage(Message $message): self
    {
        if (!$this->receivedMessages->contains($message)) {
            $this->receivedMessages[] = $message;
            $message->setReceiver($this);
        }

        return $this;
    }

    public function removeReceivedMessage(Message $message): self
    {
        if ($this->receivedMessages->removeElement($message)) {
            if ($message->getReceiver() === $this) {
                // Similar to above, Doctrine handles the relationship,
                // and you don't need to set the receiver to null.
            }
        }
    
        return $this;
        }
    public function getIsAdmin(): ?bool
    {
        return $this->isAdmin;
    }

    public function setIsAdmin(bool $isAdmin): self
    {
        $this->isAdmin = $isAdmin;
        return $this;
    }

    public function getIsBlocked(): ?bool
    {
        return $this->isBlocked;
    }

    public function setIsBlocked(bool $isBlocked): self
    {
        $this->isBlocked = $isBlocked;
        return $this;
    }

    public function getCanSendMessages(): ?bool
    {
        return $this->canSendMessages;
    }

    public function setCanSendMessages(bool $canSendMessages): self
    {
        $this->canSendMessages = $canSendMessages;
        return $this;
    }

    public function getCanSendFriendRequests(): ?bool
    {
        return $this->canSendFriendRequests;
    }

    public function setCanSendFriendRequests(bool $canSendFriendRequests): self
    {
        $this->canSendFriendRequests = $canSendFriendRequests;
        return $this;
    }
}

