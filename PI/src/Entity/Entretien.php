<?php

namespace App\Entity;

use App\Repository\EntretienRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EntretienRepository::class)
 */
class Entretien
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Candidature::class, inversedBy="entretien", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $cadidature;

    /**
     * @ORM\ManyToOne(targetEntity=Recruteur::class, inversedBy="entretiens")
     * @ORM\JoinColumn(nullable=false)
     */
    private $recruteur;

    /**
     * @ORM\Column(type="date")
     */
    private $date;

    /**
     * @ORM\Column(type="time")
     */
    private $horaire;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $lieu;

    /**
     * @ORM\Column(type="boolean")
     */
    private $confirmation;

    /**
     * @ORM\Column(type="boolean")
     */
    private $etat;

    /**
     * @ORM\OneToOne(targetEntity=GrilleEvaluation::class, mappedBy="entretien", cascade={"persist", "remove"})
     */
    private $grilleEvaluation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCadidature(): ?candidature
    {
        return $this->cadidature;
    }

    public function setCadidature(candidature $cadidature): self
    {
        $this->cadidature = $cadidature;

        return $this;
    }

    public function getRecruteur(): ?recruteur
    {
        return $this->recruteur;
    }

    public function setRecruteur(?recruteur $recruteur): self
    {
        $this->recruteur = $recruteur;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getHoraire(): ?\DateTimeInterface
    {
        return $this->horaire;
    }

    public function setHoraire(\DateTimeInterface $horaire): self
    {
        $this->horaire = $horaire;

        return $this;
    }

    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    public function setLieu(string $lieu): self
    {
        $this->lieu = $lieu;

        return $this;
    }

    public function getConfirmation(): ?bool
    {
        return $this->confirmation;
    }

    public function setConfirmation(bool $confirmation): self
    {
        $this->confirmation = $confirmation;

        return $this;
    }

    public function getEtat(): ?bool
    {
        return $this->etat;
    }

    public function setEtat(bool $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getGrilleEvaluation(): ?GrilleEvaluation
    {
        return $this->grilleEvaluation;
    }

    public function setGrilleEvaluation(GrilleEvaluation $grilleEvaluation): self
    {
        // set the owning side of the relation if necessary
        if ($grilleEvaluation->getEntretien() !== $this) {
            $grilleEvaluation->setEntretien($this);
        }

        $this->grilleEvaluation = $grilleEvaluation;

        return $this;
    }
}
