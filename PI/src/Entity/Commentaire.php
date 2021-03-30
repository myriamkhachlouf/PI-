<?php

namespace App\Entity;

use App\Repository\CommentaireRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CommentaireRepository::class)
 */
class Commentaire
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $contenu;

    /**
     * @ORM\Column(type="date")
     */
    private $datecommentaire;

    /**
     * @ORM\Column(type="date")
     */
    private $datemodification;

    /**
     * @ORM\ManyToOne(targetEntity=entreprise::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $postedby;

    /**
     * @ORM\ManyToOne(targetEntity=publication::class, inversedBy="commentaires")
     * @ORM\JoinColumn(nullable=false)
     */
    private $publication;

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

    public function getDatecommentaire(): ?\DateTimeInterface
    {
        return $this->datecommentaire;
    }

    public function setDatecommentaire(\DateTimeInterface $datecommentaire): self
    {
        $this->datecommentaire = $datecommentaire;

        return $this;
    }

    public function getDatemodification(): ?\DateTimeInterface
    {
        return $this->datemodification;
    }

    public function setDatemodification(\DateTimeInterface $datemodification): self
    {
        $this->datemodification = $datemodification;

        return $this;
    }

    public function getPostedby(): ?entreprise
    {
        return $this->postedby;
    }

    public function setPostedby(?entreprise $postedby): self
    {
        $this->postedby = $postedby;

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
}
