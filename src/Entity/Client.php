<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 * @ORM\Table(name="client")
 */
class Client
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="uuid", unique=true, nullable=false)
     */
    private ?UuidInterface $clientId = null;

    /**
     * @ORM\Column(type="string", length=255, unique=true, nullable=false)
     */
    private ?string $email = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private ?string $phone = null;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    private ?string $name = null;

    /**
     * @var ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="client", cascade={"remove"})
     */
    private $comments;

    /**
     * @ORM\Column(type="boolean")
     */
    private bool $deleted;

    public function __construct()
    {
        $this->comments = new ArrayCollection();
        $this->clientId = Uuid::uuid4();
        $this->deleted = false;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getClientId(): ?UuidInterface
    {
        return $this->clientId;
    }

    public function setClientId(?UuidInterface $clientId): void
    {
        $this->clientId = $clientId;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /*
     * @return Collection|RepairOrderTeam[]
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->comments->contains($comment)) {
            $this->comments[] = $comment;
            $comment->setClient($this);
        }

        return $this;
    }

    public function isDeleted(): bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): void
    {
        $this->deleted = $deleted;
    }
}