<?php

namespace App\Entity;

use App\Repository\EmploiRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EmploiRepository::class)
 */
class Emploi
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
     * @ORM\Column(type="integer")
     */
    private $salaire;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type_contrat;

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

    public function getSalaire(): ?int
    {
        return $this->salaire;
    }

    public function setSalaire(int $salaire): self
    {
        $this->salaire = $salaire;

        return $this;
    }

    public function getTypeContrat(): ?string
    {
        return $this->type_contrat;
    }

    public function setTypeContrat(string $type_contrat): self
    {
        $this->type_contrat = $type_contrat;

        return $this;
    }
}
