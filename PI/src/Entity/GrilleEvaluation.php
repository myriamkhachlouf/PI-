<?php

namespace App\Entity;

use App\Repository\GrilleEvaluationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=GrilleEvaluationRepository::class)
 */
class GrilleEvaluation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @ORM\OneToOne(targetEntity=Entretien::class, mappedBy="Grille_Evaluation", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $entretien;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $commentaire;

    /**
     * @ORM\Column(type="boolean")
     */
    private $admission;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntretien(): ?entretien
    {
        return $this->entretien;
    }

    public function setEntretien(entretien $entretien): self
    {
        $this->entretien = $entretien;

        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): self
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getAdmission(): ?bool
    {
        return $this->admission;
    }

    public function setAdmission(bool $admission): self
    {
        $this->admission = $admission;

        return $this;
    }
}
