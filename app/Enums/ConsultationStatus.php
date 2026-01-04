<?php

namespace App\Enums;

enum ConsultationStatus: string
{
    case PENDING = 'pending';
    case ONGOING = 'ongoing';
    case FINISHED = 'finished';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'En attente',
            self::ONGOING => 'En cours',
            self::FINISHED => 'Terminée',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'gray',
            self::ONGOING => 'blue',
            self::FINISHED => 'green',
        };
    }
}

