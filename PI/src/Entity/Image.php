<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ImageRepository::class)
 */
class Image
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     *  @Groups("publications:read")
     */
    private $mainUrl;

    /**
     * @ORM\Column(type="text")
     * @Groups("publications:read")
     */
    private $coverUrl;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMainUrl(): ?string
    {
        return $this->mainUrl;
    }

    public function setMainUrl(string $mainUrl): self
    {
        $this->mainUrl = $mainUrl;

        return $this;
    }

    public function getCoverUrl(): ?string
    {
        return $this->coverUrl;
    }

    public function setCoverUrl(string $coverUrl): self
    {
        $this->coverUrl = $coverUrl;

        return $this;
    }
}
