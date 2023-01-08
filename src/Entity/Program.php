<?php

namespace App\Entity;

use App\Repository\ProgramRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
//Ici on importe le package Vich, que l’on utilisera sous l’alias “Vich”
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Validator\Constraints as Assert;
use DateTime;
use DateTimeInterface;

#[ORM\Entity(repositoryClass: ProgramRepository::class)]
#[Vich\Uploadable]
#[Assert\EnableAutoMapping]

class Program
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: 'Ne me laisse pas tout vide')] /*
    #[Assert\Length(
        max: 255,
        message: 'Le programme saisi {{ value }} est trop longu, iil ne devrait pas dépasser {{ limit }} caractères'
        )] */
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Ne me laisse pas tout vide')]/*
    #[Assert\Length(
        max: 1000,
        message: 'Le programme saisi {{ value }} est trop longu, il ne devrait pas dépasser {{ limit }} caractères'
        )] */
    private ?string $synopsis = null;

    #[ORM\Column(length: 255, nullable: true)] /*
    #[Assert\Length(
        max: 255,
        message: 'Le nom {{ value }} de fichier est trop longu, il ne devrait pas dépasser {{ limit }} caractères'
        )] */
    private ?string $poster = null;

    // On va créer un nouvel attribut à notre entité, qui ne sera pas lié à une colonne
     // Tu peux d’ailleurs voir que l’attribut ORM column n’est pas spécifié car
     // On ne rajoute pas de données de type file en bdd
     #[Vich\UploadableField(mapping: 'poster_file', fileNameProperty: 'poster')]
     #[Assert\File(
        maxSize: '1M',
        mimeTypes: ['image/jpeg', 'image/png', 'image/webp'],
    )]
     private ?File $posterFile = null;

    #[ORM\ManyToOne(targetEntity: Category::class, inversedBy: 'programs')]
 //   #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: 'Ne me laisse pas tout vide')] /*
    #[Assert\Length(
        max: 255,
        maxMessage: 'La catégorie saisie {{ value }} est trop longue, elle ne devrait pas dépasser {{ limit }} caractères',
        )] */
    private ?Category $category = null;

    #[ORM\OneToMany(mappedBy: 'program', targetEntity: Season::class)]
    private Collection $seasons;

    #[ORM\ManyToMany(targetEntity: Actor::class, mappedBy: 'programs', cascade:['persist'])]
    private Collection $actors;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;
/*
    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?DatetimeInterface $updatedAt = null;
*/

    #[ORM\Column(type: 'datetime')]
    private ?\DateTimeInterface $updatedAt;

    #[ORM\ManyToOne(inversedBy: 'programs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    public function __construct()
    {
        $this->seasons = new ArrayCollection();
        $this->actors = new ArrayCollection();
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

    public function getSynopsis(): ?string
    {
        return $this->synopsis;
    }

    public function setSynopsis(string $synopsis): self
    {
        $this->synopsis = $synopsis;

        return $this;
    }

    public function getPoster(): ?string
    {
        return $this->poster;
    }

    public function setPoster(?string $poster): self
    {
        $this->poster = $poster;

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

    /**
     * @return Collection<int, Season>
     */
    public function getSeasons(): Collection
    {
        return $this->seasons;
    }

    public function addSeason(Season $season): self
    {
        if (!$this->seasons->contains($season)) {
            $this->seasons->add($season);
            $season->setProgram($this);
        }

        return $this;
    }

    public function removeSeason(Season $season): self
    {
        if ($this->seasons->removeElement($season)) {
            // set the owning side to null (unless already changed)
            if ($season->getProgram() === $this) {
                $season->setProgram(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Actor>
     */
    public function getActors(): Collection
    {
        return $this->actors;
    }

    public function addActor(Actor $actor): self
    {
        if (!$this->actors->contains($actor)) {
            $this->actors->add($actor);
            $actor->addProgram($this);
        }

        return $this;
    }

    public function removeActor(Actor $actor): self
    {
        if ($this->actors->removeElement($actor)) {
            $actor->removeProgram($this);
        }
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

    public function setPosterFile(File $image = null): Program

    {
        $this->posterFile = $image;
        if ($image) {
            $this->updatedAt = new \DateTime('now');
        }
        return $this;
    }


    public function getPosterFile(): ?File
    {
        return $this->posterFile;
    }

    
/**
 * Get the value of updatedAt
 */
public function getUpdatedAt(): DateTimeInterface|null
{
    return $this->updatedAt;
}

/**
* Set the value of updatedAt
*
* @return  self
*/
public function setUpdatedAt(DateTime $updatedAt): self
{
    $this->updatedAt = $updatedAt;
    return $this;
}


    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

}
