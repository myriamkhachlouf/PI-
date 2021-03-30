<?php

namespace App\Entity;

use App\Repository\ReclamationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\UploadedFile;



/**
 * @ORM\Entity(repositoryClass=ReclamationRepository::class)
 * @Vich\Uploadable
 */
class Reclamation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("reclamations:read")

     */
    private $id;





    /**
     * @ORM\Column(type="text")
     * @Groups("reclamations:read")
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateceation", type="datetime", nullable=true)
     */
    private $date;


    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Reclamation
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @ORM\Column(type="text",nullable=true)
     * @Groups("reclamations:read")
     */
    private $etat;





    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * @var string
     * @Groups("reclamations:read")
     */
    private $image;
    /**
     * @Vich\UploadableField(mapping="reclamations", fileNameProperty="image")
     * @var File
     */
    private $imageFile;





    /**
     * @ORM\Column(type="datetime")
     * @var \DateTimeInterface|null
     */
    private $updatedAt;

    // ...

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }



    /**
     * @ORM\Column(type="string", length=255)
     * @Groups("reclamations:read")
     * @Assert\NotBlank
     */
    private $objet;



    /**
     * @var int
     *
     * @ORM\Column(name="target_id", type="integer",nullable=true)
     */
    private $target_id;


    /**
     * @var int
     *
     * @ORM\Column(name="target_idf", type="integer",nullable=true)
     */
    private $target_idf;

    /**
     * @return int
     */
    public function getTarget_idf()
    {
        return $this->target_idf;
    }

    /**
     * @param int $target_id
     */
    public function setTarget_idf($target_idf)
    {
        $this->target_idf = $target_idf;
    }

    /**
     * @return int
     */
    public function getTarget_id()
    {
        return $this->target_id;
    }

    /**
     * @param int $target_id
     */
    public function setTarget_id($target_id)
    {
        $this->target_id = $target_id;
    }

    /**
     * @ORM\ManyToOne(targetEntity=Formation::class,inversedBy="reclamations",cascade={"persist"})
     * @ORM\JoinColumn(name="formations_id", referencedColumnName="id")
     */
    private $formations;

    /**
     * @ORM\ManyToOne(targetEntity=Formation::class,inversedBy="reclamations",cascade={"persist"})
     * @ORM\JoinColumn(name="targetf", referencedColumnName="id")
     */
    private $targetf;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups("reclamations:read")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="Reclamation",cascade={"persist"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="Reclamation",cascade={"persist"})
     * @ORM\JoinColumn(name="target", referencedColumnName="id")
     */
    private $target;









    protected $captchaCode;


    public function __construct()
    {
        $this->candidat = new ArrayCollection();
        $this->updatedAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Candidat[]
     */







    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }



    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }
    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function getObjet(): ?string
    {
        return $this->objet;
    }

    public function setObjet(string $objet): self
    {
        $this->objet = $objet;

        return $this;
    }





    public function getFormations(): ?Formation
    {
        return $this->formations;
    }

    public function setFormations(?Formation $formations): self
    {
        $this->formations = $formations;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getUser(): ?Users
    {
        return $this->user;
    }

    public function setUser(?Users $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getTarget(): ?Users
    {
        return $this->target;
    }

    public function setTarget(?Users $target): self
    {
        $this->target = $target;

        return $this;
    }

    public function getTargetf(): ?Formation
    {
        return $this->targetf;
    }

    public function setTargetf(?Formation $targetf): self
    {
        $this->targetf = $targetf;

        return $this;
    }

    public function getCaptchaCode()
    {
        return $this->captchaCode;
    }

    public function setCaptchaCode($captchaCode)
    {
        $this->captchaCode = $captchaCode;
    }



}
