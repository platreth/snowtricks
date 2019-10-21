<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FileRepository")
 */
class File
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $originaleName;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $sizeOrigin;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Trick", inversedBy="images")
     */
    private $trick_image;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Trick", inversedBy="videos")
     */
    private $trick_video;


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

    public function getOriginaleName(): ?string
    {
        return $this->originaleName;
    }

    public function setOriginaleName(string $originaleName): self
    {
        $this->originaleName = $originaleName;

        return $this;
    }

    public function getSizeOrigin(): ?string
    {
        return $this->sizeOrigin;
    }

    public function setSizeOrigin(string $sizeOrigin): self
    {
        $this->sizeOrigin = $sizeOrigin;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

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

    public function getTrickImage(): ?Trick
    {
        return $this->trick_image;
    }

    public function setTrickImage(?Trick $trick_image): self
    {
        $this->trick_image = $trick_image;

        return $this;
    }

    public function getTrickVideo(): ?Trick
    {
        return $this->trick_video;
    }

    public function setTrickVideo(?Trick $trick_video): self
    {
        $this->trick_video = $trick_video;

        return $this;
    }

}
