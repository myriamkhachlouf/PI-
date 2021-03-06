<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use App\Entity\Images;
use App\Entity\Publication;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;


/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class Users implements UserInterface
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     *  @Groups("users:read")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180)
     *  @Groups("users:read")
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=180)
     *  @Groups("users:read")
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     *  @Groups("users:read")
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=180)
     *  @Groups("users:read")
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=180 )
     *  @Groups("users:read")
     */
    private $domaine;


    /**
     * @ORM\Column(type="json")
     *  @Groups("users:read")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     *  @Groups("users:read")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $activation_token;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     */
    private $reset_token;


    protected $captchaCode;

    /**
     * @ORM\OneToOne(targetEntity=Images::class, mappedBy="Users", cascade={"persist", "remove"})
     *  @Groups("users:read")
     */
    private $images;


/**
     * @var boolean
     *
     * @ORM\Column(name="enabled", type="boolean", nullable=false)
     */
    private $enabled;

    /**
     *@ORM\Column(name="score",type="integer", nullable=true)
     */
    private $score = 0;
 /**
     * @ORM\OneToMany(targetEntity=Commentaire::class, mappedBy="postedby")
     */
    private $commentaires;
    /**
     * @ORM\OneToMany(targetEntity=Publication::class, mappedBy="postedby")
     */
    private $publications;
    /**
     * @return Collection|Publication[]
     */
    public function getPublications(): Collection
    {
        return $this->publications;
    }

    public function addPublication(Publication $publication): self
    {
        if (!$this->publications->contains($publication)) {
            $this->publications[] = $publication;
            $publication->setPostedby($this);
        }

        return $this;
    }

    public function removePublication(Publication $publication): self
    {
        if ($this->publications->removeElement($publication)) {
            // set the owning side to null (unless already changed)
            if ($publication->getPostedby() === $this) {
                $publication->setPostedby(null);
            }
        }

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
            $commentaire->setPostedby($this);
        }

        return $this;
    }

    public function removeCommentaire(Commentaire $commentaire): self
    {
        if ($this->commentaires->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getPostedby() === $this) {
                $commentaire->setPostedby(null);
            }
        }

        return $this;
    }

/**
     * @return Collection|Reclamation[]
     */
    public function getReclamation(): Collection
    {
        return $this->Reclamation;
    }

    public function addReclamation(Reclamation $reclamation): self
    {
        if (!$this->Reclamation->contains($reclamation)) {
            $this->Reclamation[] = $reclamation;
            $reclamation->setUsers($this);
        }

        return $this;
    }

/**
     * Set score
     *
     * @param string $score
     *
     * @return User
     */
    public function setScore($score)
    {
        $this->score = $score;

        return $this;
    }

    /**
     * Get score
     *
     * @return string
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     *
     * @return User
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get enabled
     *
     * @return boolean
     */
    public function getEnabled()
    {
        return $this->enabled;
    }



    /**
     * @return Collection|Conversation[]
     */
    public function getConversations(): Collection
    {
        return $this->conversations;
    }

    public function addConversation(Conversation $conversation): self
    {
        if (!$this->conversations->contains($conversation)) {
            $this->conversations[] = $conversation;
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): self
    {
        $this->conversations->removeElement($conversation);

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getSendedMessages(): Collection
    {
        return $this->sendedMessages;
    }

    public function addSendedMessage(Message $sendedMessage): self
    {
        if (!$this->sendedMessages->contains($sendedMessage)) {
            $this->sendedMessages[] = $sendedMessage;
            $sendedMessage->setIsFrom($this);
        }

        return $this;
    }

    public function removeSendedMessage(Message $sendedMessage): self
    {
        if ($this->sendedMessages->removeElement($sendedMessage)) {
            // set the owning side to null (unless already changed)
            if ($sendedMessage->getIsFrom() === $this) {
                $sendedMessage->setIsFrom(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getRecievedMessages(): Collection
    {
        return $this->recievedMessages;
    }

    public function addRecievedMessage(Message $recievedMessage): self
    {
        if (!$this->recievedMessages->contains($recievedMessage)) {
            $this->recievedMessages[] = $recievedMessage;
            $recievedMessage->setIsTo($this);
        }

        return $this;
    }

    public function removeRecievedMessage(Message $recievedMessage): self
    {
        if ($this->recievedMessages->removeElement($recievedMessage)) {
            // set the owning side to null (unless already changed)
            if ($recievedMessage->getIsTo() === $this) {
                $recievedMessage->setIsTo(null);
            }
        }

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

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param mixed $telephone
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }

    /**
     * @return mixed
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param mixed $adresse
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }

    /**
     * @return mixed
     */
    public function getDomaine()
    {
        return $this->domaine;
    }

    /**
     * @param mixed $domaine
     */
    public function setDomaine($domaine)
    {
        $this->domaine = $domaine;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
    function addRole($role) {
        $this->roles[] = $role;
    }

    public function getActivationToken(): ?string
    {
        return $this->activation_token;
    }

    public function setActivationToken(?string $activation_token): self
    {
        $this->activation_token = $activation_token;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->reset_token;
    }

    public function setResetToken(?string $reset_token): self
    {
        $this->reset_token = $reset_token;

        return $this;
    }

    public function getImages(): ?Images
    {
        return $this->images;
    }

    public function addImage(?Images $images): self
    {
        // unset the owning side of the relation if necessary
        if ($images === null && $this->images !== null) {
            $this->images->setUsers(null);
        }

        // set the owning side of the relation if necessary
        if ($images !== null && $images->getUsers() !== $this) {
            $images->setUsers($this);
        }

        $this->images = $images;

        return $this;
    }




}
