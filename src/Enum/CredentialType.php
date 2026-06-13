<?php

declare(strict_types=1);

namespace App\Enum;

enum CredentialType: string
{
    case Degree = 'degree';
    case Certification = 'certification';
    case Training = 'training';
}
