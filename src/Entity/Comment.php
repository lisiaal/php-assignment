<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="comment")
 */
class Comment
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=1000, nullable=false)
     */
    private ?string $comment = null;

    /**
     * @ORM\ManyToOne(targetEntity=Client::class, inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     */
    private ?Client $client = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): void
    {
        $this->comment = $comment;
    }

    public function setClient(?Client $client): void
    {
        $this->client = $client;
    }
}