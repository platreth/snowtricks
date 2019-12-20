<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\TrickRepository")
 */
class Trick implements \JsonSerializable
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
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date_create;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="tricks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Category", inversedBy="tricks")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\File", mappedBy="trick_image", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $images;
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\File", mappedBy="trick_video", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $videos;



    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->videos = new ArrayCollection();
    }

    public function jsonSerialize() {
        return array(
            'id' => $this->id,
            'picture' => $this->images[0]->getName(),
            'name' => $this->name,
            'date_create' =>  $this->date_create->format('d-m-Y H:i'),
//            'pictureName' => $this->image->name
            );
    }


    public function getId(): ?int
    {
        return $this->id;
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

    public function getDateCreate(): ?\DateTimeInterface
    {
        return $this->date_create;
    }

    public function setDateCreate(\DateTimeInterface $date_create): self
    {
        $this->date_create = $date_create;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): self
    {
        $this->category = $category;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|File[]
     */
    public function getFile(): Collection
    {
        return $this->file;
    }

    public function addFile(File $file): self
    {
        if (!$this->file->contains($file)) {
            $this->file[] = $file;
            $file->setTrick($this);
        }

        return $this;
    }

    public function removeFile(File $file): self
    {
        if ($this->file->contains($file)) {
            $this->file->removeElement($file);
            // set the owning side to null (unless already changed)
            if ($file->getTrick() === $this) {
                $file->setTrick(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|File[]
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(File $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images[] = $image;
            $image->setTrick($this);
        }

        return $this;
    }

    public function removeImage(File $image): self
    {
        if ($this->images->contains($image)) {
            $this->images->removeElement($image);
            // set the owning side to null (unless already changed)
            if ($image->getTrickImage() === $this) {
                $image->setTrickImage(null);
            }
        }

        return $this;
    }

    public function setImages($image): self
    {
        $this->images = $image;
        return $this;
    }

    /**
     * @return Collection|File[]
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(File $video): self
    {
        if (!$this->videos->contains($video)) {
            $this->videos[] = $video;
            $video->setTrick($this);
        }

        return $this;
    }

    public function removeVideo(File $video): self
    {
        if ($this->videos->contains($video)) {
            $this->videos->removeElement($video);
            // set the owning side to null (unless already changed)
            if ($video->getTrickVideo() === $this) {
                $video->setTrickVideo(null);
            }
        }

        return $this;
    }

    public function setVideos($video): self
    {
        $this->videos = $video;
        return $this;
    }

}
