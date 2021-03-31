<?php

namespace App\Entity;

use App\Repository\PublicationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PublicationRepository::class)
 * @UniqueEntity(fields={"title"},
 *     message="This title is already used")
 * @ORM\HasLifecycleCallbacks
 */
class Publication
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups("publications:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255,unique=true)
     * @Groups("publications:read")
     */
    private $title;

    /**
     * @ORM\Column(type="text")
     *  @Assert\NotBlank
     * @Groups("publications:read")
     */
    private $contenu;

    /**
     * @ORM\Column(type="datetime")
     * @Groups("publications:read")
     */
    private $createdAt;
    /**
     * @ORM\Column(type="datetime")
     * @Groups("publications:read")
     */
    private $updatedAt;



    /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="publication",cascade={"remove"})
     * @Groups("publications:read",onDelete="CASCADE")
     */
    private $commentaires;

    /**
     * @ORM\OneToOne(targetEntity=Image::class, cascade={"persist", "remove"})
     * @Groups("publications:read", onDelete="CASCADE")
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity=Users::class, inversedBy="publications")
     * @ORM\JoinColumn(nullable=false)
     * @Groups("publications:read")
     */
    private $postedby;
    protected $captchaCode;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $views;

    /**
     * @ORM\ManyToMany(targetEntity=Users::class)
     */
    private $seenby;



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




    public function __construct()
    {
        $this->commentaires = new ArrayCollection();
        $this->image = new ArrayCollection();
        $this->seenby = new ArrayCollection();
    }

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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
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

    /**
     * @return Collection|Commentaire[]
     */
    public function getCommentaires(): Collection
    {
        return $this->commentaires;
    }

    public function addCommentaire(Commentaire $commentaire): self
    {
        if (!$this->commentaires->contains($commentaire)) {
            $this->commentaires[] = $commentaire;
            $commentaire->setPublication($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getPublication() === $this) {
                $commentaire->setPublication(null);
            }
        }

        return $this;
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

    public function getImage(): ?Image
    {
        return $this->image;
    }

    public function setImage(?Image $image): self
    {
        $this->image = $image;

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

    public function getViews(): ?int
    {
        return $this->views;
    }

    public function setViews(?int $views): self
    {
        $this->views = $views;

        return $this;
    }

    /**
     * @return Collection|Users[]
     */
    public function getSeenby(): Collection
    {
        return $this->seenby;
    }

    /**
     * @param Users $seenby
     * @return $this
     */
    public function addSeenby(Users $seenby): self
    {
        if (!$this->seenby->contains($seenby)) {
            $this->seenby[] = $seenby;
        }

        return $this;
    }

    /**
     * @param Users $seenby
     * @return $this
     */
    public function removeSeenby(Users $seenby): self
    {
        $this->seenby->removeElement($seenby);

        return $this;
    }
}
