<?php

namespace App\Entity;

use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: PersonRepository::class)]
class Person
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['person:read', 'person:show'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['person:read', 'person:show'])]
    private ?string $firstnames = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['person:read', 'person:show'])]
    private ?string $birthName = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $birthDate = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['person:show'])]
    private ?int $birthCertificate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups(['person:read', 'person:show'])]
    private ?\DateTimeInterface $deathDate = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['person:show'])]
    private ?int $deathCertificate = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children1')]
    #[ORM\JoinColumn(nullable: true)]
    private ?self $parent1 = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'children2')]
    #[ORM\JoinColumn(nullable: true)]
    private ?self $parent2 = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent1')]
    private Collection $children1;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'parent2')]
    private Collection $children2;

    /**
     * @var Collection<int, Wedding>
     */
    #[ORM\OneToMany(targetEntity: Wedding::class, mappedBy: 'person1', orphanRemoval: true)]
    private Collection $weddings1;

    /**
     * @var Collection<int, Wedding>
     */
    #[ORM\OneToMany(targetEntity: Wedding::class, mappedBy: 'person2', orphanRemoval: true)]
    private Collection $weddings2;

    #[ORM\ManyToOne(inversedBy: 'people')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    public function __construct()
    {
        $this->children1 = new ArrayCollection();
        $this->children2 = new ArrayCollection();
        $this->weddings1 = new ArrayCollection();
        $this->weddings2 = new ArrayCollection();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstnames(): ?string
    {
        return $this->firstnames;
    }

    public function setFirstnames(string $firstnames): static
    {
        $this->firstnames = $firstnames;

        return $this;
    }

    public function getBirthName(): ?string
    {
        return $this->birthName;
    }

    public function setBirthName(?string $birthName): static
    {
        $this->birthName = $birthName;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birthDate;
    }

    public function setBirthDate(?\DateTimeInterface $birthDate): static
    {
        $this->birthDate = $birthDate;

        return $this;
    }

    public function getBirthCertificate(): ?int
    {
        return $this->birthCertificate;
    }

    public function setBirthCertificate(?int $birthCertificate): static
    {
        $this->birthCertificate = $birthCertificate;

        return $this;
    }

    public function getDeathDate(): ?\DateTimeInterface
    {
        return $this->deathDate;
    }

    public function setDeathDate(?\DateTimeInterface $deathDate): static
    {
        $this->deathDate = $deathDate;

        return $this;
    }

    public function getDeathCertificate(): ?int
    {
        return $this->deathCertificate;
    }

    public function setDeathCertificate(?int $deathCertificate): static
    {
        $this->deathCertificate = $deathCertificate;

        return $this;
    }

    public function getParent1(): ?self
    {
        return $this->parent1;
    }

    public function setParent1(?self $parent1): static
    {
        $this->parent1 = $parent1;

        return $this;
    }

    public function getParent2(): ?self
    {
        return $this->parent2;
    }

    public function setParent2(?self $parent2): static
    {
        $this->parent2 = $parent2;

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChildren1(): Collection
    {
        return $this->children1;
    }

    public function addChildren1(self $children1): static
    {
        if (!$this->children1->contains($children1)) {
            $this->children1->add($children1);
            $children1->setParent1($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getChildren2(): Collection
    {
        return $this->children2;
    }

    public function addChildren2(self $children2): static
    {
        if (!$this->children2->contains($children2)) {
            $this->children2->add($children2);
            $children2->setParent2($this);
        }

        return $this;
    }

    public function removeChildren2(self $children2): static
    {
        if ($this->children2->removeElement($children2)) {
            // set the owning side to null (unless already changed)
            if ($children2->getParent2() === $this) {
                $children2->setParent2(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Wedding>
     */
    public function getWeddings1(): Collection
    {
        return $this->weddings1;
    }

    public function addWeddings1(Wedding $weddings1): static
    {
        if (!$this->weddings1->contains($weddings1)) {
            $this->weddings1->add($weddings1);
            $weddings1->setPerson1($this);
        }

        return $this;
    }

    public function removeWeddings1(Wedding $weddings1): static
    {
        if ($this->weddings1->removeElement($weddings1)) {
            // set the owning side to null (unless already changed)
            if ($weddings1->getPerson1() === $this) {
                $weddings1->setPerson1(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Wedding>
     */
    public function getWeddings2(): Collection
    {
        return $this->weddings2;
    }

    public function addWeddings2(Wedding $weddings2): static
    {
        if (!$this->weddings2->contains($weddings2)) {
            $this->weddings2->add($weddings2);
            $weddings2->setPerson2($this);
        }

        return $this;
    }

    public function removeWeddings2(Wedding $weddings2): static
    {
        if ($this->weddings2->removeElement($weddings2)) {
            // set the owning side to null (unless already changed)
            if ($weddings2->getPerson2() === $this) {
                $weddings2->setPerson2(null);
            }
        }

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Collection<int, Person>
     */
    public function getChildren(): Collection
    {
        $children = new ArrayCollection();
        foreach ($this->children1 as $child) {
            $children->add($child);
        }
        foreach ($this->children2 as $child) {
            $children->add($child);
        }
        return $children;
    }

    /**
     * @return Collection<int, Person>
     */
    public function getParents(): Collection
    {
        $parents = new ArrayCollection();
        if ($this->parent1 !== null) {
            $parents->add($this->parent1);
        }
        if ($this->parent2 !== null) {
            $parents->add($this->parent2);
        }
        return $parents;
    }

    /**
     * @return Collection<int, Wedding>
     */
    public function getWeddings(): Collection
    {
        return new ArrayCollection(
            array_merge(
                $this->weddings1->toArray(),
                $this->weddings2->toArray()
            )
        );
    }
}
