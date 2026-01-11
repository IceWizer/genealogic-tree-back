<?php

namespace App\Response\Person;

use App\Entity\Person;
use Doctrine\Common\Collections\Collection;

class Read
{
    public ?string $id;
    public ?string $name;
    public ?string $firstNames;
    public ?string $birthName;
    public ?\DateTimeInterface $birthDate;
    public ?\DateTimeInterface $deathDate;
    public ?bool $hasChildren;

    public function __construct(
        Person $person
    ) {
        $this->id = $person->getId()->toRfc4122();
        $this->name = $person->getName();
        $this->firstNames = $person->getFirstNames();
        $this->birthName = $person->getBirthName();
        $this->birthDate = $person->getBirthDate();
        $this->deathDate = $person->getDeathDate();
        $this->hasChildren = !$person->getChildren()->isEmpty();
    }
}
