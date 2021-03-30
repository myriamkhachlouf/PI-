<?php

namespace App\Entity;

use App\Repository\CandidatureRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CandidatureRepository::class)
 */
class Candidature
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $date_candidature;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="candidatures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $candidat;

    /**
     * @ORM\ManyToOne(targetEntity=Offre::class, inversedBy="candidatures")
     * @ORM\JoinColumn(nullable=false)
     */
    private $offre;

    /**
     * @ORM\OneToOne(targetEntity=Entretien::class, mappedBy="cadidature", cascade={"persist", "remove"})
     */
    private $entretien;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Pdf;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateCandidature(): ?\DateTimeInterface
    {
        return $this->date_candidature;
    }

    public function setDateCandidature(\DateTimeInterface $date_candidature): self
    {
        $this->date_candidature = $date_candidature;

        return $this;
    }

    public function getCandidat(): ?users
    {
        return $this->candidat;
    }

    public function setCandidat(?users $candidat): self
    {
        $this->candidat = $candidat;

        return $this;
    }

    public function getOffre(): ?offre
    {
        return $this->offre;
    }

    public function setOffre(?offre $offre): self
    {
        $this->offre = $offre;

        return $this;
    }

    public function getEntretien(): ?Entretien
    {
        return $this->entretien;
    }

    public function setEntretien(Entretien $entretien): self
    {
        // set the owning side of the relation if necessary
        if ($entretien->getCadidature() !== $this) {
            $entretien->setCadidature($this);
        }

        $this->entretien = $entretien;

        return $this;
    }

    public function getPdf()
    {
        return $this->Pdf;
    }

    public function setPdf($Pdf)
    {
        $this->Pdf = $Pdf;

        return $this;
    }
}
