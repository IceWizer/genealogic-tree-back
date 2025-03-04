<?php

namespace App\Request\Person;

use Symfony\Component\Validator\Constraints as Assert;

class Create
{
    #[Assert\NotBlank(message: "name.not_blank")]
    #[Assert\Length(max: 255, maxMessage: "name.max_length")]
    public string $name;

    #[Assert\NotBlank(message: "firstnames.not_blank")]
    #[Assert\Length(max: 255, maxMessage: "firstnames.max_length")]
    public string $firstnames;

    #[Assert\Length(max: 255, maxMessage: "birthname.max_length")]
    public ?string $birthname = null;

    #[Assert\Date(message: 'birthDate.invalid')]
    public ?string $birthDate = null;

    #[Assert\Positive(message: 'birthCertificate.positive')]
    public ?int $birthCertificate = null;

    #[Assert\Date(message: 'deathDate.invalid')]
    public ?string $deathDate = null;

    #[Assert\Positive(message: 'deathCertificate.positive')]
    public ?int $deathCertificate = null;
}
