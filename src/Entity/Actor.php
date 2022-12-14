<?php

namespace App\Entity;

use App\Repository\ActorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\File;
use DateTime;
use DateTimeInterface;


#[ORM\Entity(repositoryClass: ActorRepository::class)]
#[Vich\Uploadable]
#[Assert\EnableAutoMapping]
class Actor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Program::class, inversedBy: 'actors', cascade:['persist'])]
    private Collection $programs;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

     #[Vich\UploadableField(mapping: 'actor_photo', fileNameProperty: 'photo')]
     #[Assert\File(
        maxSize: '1M',
        mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
    )]
     private ?File $photoFile = null;

     #[ORM\Column(type: 'datetime')]
    private ?DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->programs = new ArrayCollection();
        
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

    /**
     * @return Collection<int, Program>
     */
    public function getPrograms(): Collection
    {
        return $this->programs;
    }

    public function addProgram(Program $program): self
    {
        if (!$this->programs->contains($program)) {
            $this->programs->add($program);
        }

        return $this;
    }

    public function removeProgram(Program $program): self
    {
        $this->programs->removeElement($program);

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;
        return $this;
    }

    public function setPhotoFile(File $image = null): Actor
    {
        $this->photoFile = $image;
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
        return $this;
    }


    public function getPhotoFile(): ?File
    {
        return $this->photoFile;
    }

    public function setUpdatedAt(DateTime $updatedAt = null): Actor
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
    public function getUpdatedAt(): DateTimeInterface|null
    {
        return $this->updatedAt;
    }
}
