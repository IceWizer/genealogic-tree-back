<?php

namespace App\Entity;

use App\Repository\WeddingRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: WeddingRepository::class)]
class Wedding
{
    #[ORM\Id]
    #[ORM\Column(type: UuidType::NAME, unique: true)]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\CustomIdGenerator(class: 'doctrine.uuid_generator')]
    private ?Uuid $id = null;

    #[ORM\ManyToOne(inversedBy: 'weddings1')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Person $person1 = null;

    #[ORM\ManyToOne(inversedBy: 'weddings2')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Person $person2 = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $weddingDate = null;

    #[ORM\Column(nullable: true)]
    private ?int $weddingCertificate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $divorsedDate = null;

    #[ORM\Column(nullable: true)]
    private ?int $divorsedCertificate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPerson1(): ?Person
    {
        return $this->person1;
    }

    public function setPerson1(?Person $person1): static
    {
        $this->person1 = $person1;

        return $this;
    }

    public function getPerson2(): ?Person
    {
        return $this->person2;
    }

    public function setPerson2(?Person $person2): static
    {
        $this->person2 = $person2;

        return $this;
    }

    public function getWeddingDate(): ?\DateTimeInterface
    {
        return $this->weddingDate;
    }

    public function setWeddingDate(?\DateTimeInterface $weddingDate): static
    {
        $this->weddingDate = $weddingDate;

        return $this;
    }

    public function getWeddingCertificate(): ?int
    {
        return $this->weddingCertificate;
    }

    public function setWeddingCertificate(?int $weddingCertificate): static
    {
        $this->weddingCertificate = $weddingCertificate;

        return $this;
    }

    public function getDivorsedDate(): ?\DateTimeInterface
    {
        return $this->divorsedDate;
    }

    public function setDivorsedDate(\DateTimeInterface $divorsedDate): static
    {
        $this->divorsedDate = $divorsedDate;

        return $this;
    }

    public function getDivorsedCertificate(): ?int
    {
        return $this->divorsedCertificate;
    }

    public function setDivorsedCertificate(?int $divorsedCertificate): static
    {
        $this->divorsedCertificate = $divorsedCertificate;

        return $this;
    }
}
