<?php

namespace App\Response\Person;

use App\Entity\Person;
use Doctrine\Common\Collections\Collection;

class Show
{
    public ?string $id;
    public ?string $name;
    public ?string $firstNames;
    public ?string $birthName;
    public ?\DateTimeInterface $birthDate;
    public ?int $birthCertificate;
    public ?\DateTimeInterface $deathDate;
    public ?int $deathCertificate;
    public ?Collection $parents;
    public ?Collection $children;

    public function __construct(
        Person $person
    ) {
        $this->id = $person->getId()->toRfc4122();
        $this->name = $person->getName();
        $this->firstNames = $person->getFirstNames();
        $this->birthName = $person->getBirthName();
        $this->birthDate = $person->getBirthDate();
        $this->birthCertificate = $person->getBirthCertificate();
        $this->deathDate = $person->getDeathDate();
        $this->deathCertificate = $person->getDeathCertificate();
        $this->parents = $person->getParents()->map(fn($x) => new RelativeShow($x));
        $this->children = $person->getChildren()->map(fn($x) => new RelativeShow($x));
    }
}
