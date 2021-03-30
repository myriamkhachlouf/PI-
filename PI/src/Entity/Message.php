<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     */
    private $content;

    /**
     * @ORM\Column(type="datetime")
     */
    private $sentAt;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="sendedMessages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $isFrom;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="recievedMessages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $isTo;

    /**
     * @ORM\ManyToOne(targetEntity=Conversation::class, inversedBy="messages")
     * @ORM\JoinColumn(nullable=false)
     */
    private $conversation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getSentAt(): ?\DateTimeInterface
    {
        return $this->sentAt;
    }

    public function setSentAt(\DateTimeInterface $sentAt): self
    {
        $this->sentAt = $sentAt;

        return $this;
    }

    public function getIsFrom(): ?Users
    {
        return $this->isFrom;
    }

    public function setIsFrom(?Users $isFrom): self
    {
        $this->isFrom = $isFrom;

        return $this;
    }

    public function getIsTo(): ?Users
    {
        return $this->isTo;
    }

    public function setIsTo(?Users $isTo): self
    {
        $this->isTo = $isTo;

        return $this;
    }

    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }

    public function setConversation(?Conversation $conversation): self
    {
        $this->conversation = $conversation;

        return $this;
    }
}
