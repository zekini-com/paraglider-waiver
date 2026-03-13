<?php

namespace App\Enums;

enum EmergencyContactRelationship: string
{
    case Spouse = 'Spouse';
    case Parent = 'Parent';
    case Sibling = 'Sibling';
    case Child = 'Child';
    case Friend = 'Friend';
    case Other = 'Other';

    public function label(): string
    {
        return $this->value;
    }
}
