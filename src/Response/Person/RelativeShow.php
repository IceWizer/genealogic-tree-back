<?php

namespace App\Response\Person;

use App\Entity\Person;
use Doctrine\Common\Collections\Collection;

class RelativeShow
{
    public ?string $id;
    public ?string $name;
    public ?string $firstnames;
    public ?string $birthname;
    public ?\DateTimeInterface $birthDate;
    public ?int $birthCertificate;
    public ?\DateTimeInterface $deathDate;
    public ?int $deathCertificate;

    public function __construct(
        Person $person
    ) {
        $this->id = $person->getId()->toRfc4122();
        $this->name = $person->getName();
        $this->firstnames = $person->getFirstnames();
        $this->birthname = $person->getBirthname();
        $this->birthDate = $person->getBirthDate();
        $this->birthCertificate = $person->getBirthCertificate();
        $this->deathDate = $person->getDeathDate();
        $this->deathCertificate = $person->getDeathCertificate();
    }
}
