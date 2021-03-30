<?php

namespace App\Entity;

use App\Repository\StageRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=StageRepository::class)
 */
class Stage
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Offre::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $offre;
    /**
     * @ORM\Column(type="date")
     */
    private $date_debut;
    /**
     * @ORM\Column(type="date")
     */
    private $date_fin;
    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type_du_stage;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom_encadrant;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getOffre(): ?offre
    {
        return $this->offre;
    }

    public function setOffre(offre $offre): self
    {
        $this->offre = $offre;

        return $this;
    }
    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(\DateTimeInterface $date_debut): self
    {
        $this->date_debut = $date_debut;

        return $this;
    }
    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(\DateTimeInterface $date_fin): self
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getTypeDuStage(): ?string
    {
        return $this->type_du_stage;
    }

    public function setTypeDuStage(string $type_du_stage): self
    {
        $this->type_du_stage = $type_du_stage;

        return $this;
    }

    public function getNomEncadrant(): ?string
    {
        return $this->nom_encadrant;
    }

    public function setNomEncadrant(string $nom_encadrant): self
    {
        $this->nom_encadrant = $nom_encadrant;

        return $this;
    }
}
