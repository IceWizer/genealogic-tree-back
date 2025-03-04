<?php

namespace App\Response\Person;

use App\Entity\Person;
use Doctrine\Common\Collections\Collection;

class Option
{
    public ?string $id;
    public ?string $name;
    public ?string $firstnames;

    public function __construct(
        Person $person
    ) {
        $this->id = $person->getId()->toRfc4122();
        $this->name = $person->getName();
        $this->firstnames = $person->getFirstnames();
    }
}
