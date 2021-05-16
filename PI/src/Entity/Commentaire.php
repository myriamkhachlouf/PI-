<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=CommentaireRepository::class)
 ** @ORM\HasLifecycleCallbacks
 */
class Commentaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("commentJson:read")
     */
    private $id;
    /**
     * @Groups("commentJson:read")
     */
    private $postedby_id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("commentJson:read")
     */
    private $contenu;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("commentJson:read")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("commentJson:read")
     */
    private $updatedAt;



    /**
     * @ORM\ManyToOne(targetEntity=Publication::class, inversedBy="commentaires")
     * @ORM\JoinColumn(name="publication_id",referencedColumnName="id")
     */
    private $publication;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="commentaires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $postedby;
    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps(): void
    {
        $this->setUpdatedAt(new \DateTime('now'));
        if ($this->getCreatedAt() === null) {
            $this->setCreatedAt(new \DateTime('now'));
        }
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): self
    {
        $this->contenu = $contenu;

        return $this;
    }



    public function getPublication(): ?publication
    {
        return $this->publication;
    }

    public function setPublication(?publication $publication): self
    {
        $this->publication = $publication;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     */
    public function setUpdatedAt($updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * @return mixed
     */
    public function getPostedby()
    {
        return $this->postedby;
    }

    /**
     * @param mixed $postedby
     */
    public function setPostedby($postedby): void
    {
        $this->postedby = $postedby;
    }

    /**
     * @return mixed
     */
    public function getPostedbyId()
    {
        return $this->postedby_id;
    }

    /**
     * @param mixed $postedby_id
     */
    public function setPostedbyId($postedby_id): void
    {
        $this->postedby_id = $postedby_id;
    }
}
