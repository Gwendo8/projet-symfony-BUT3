<?php
namespace App\Enum;

enum ProductStatus: string
{
    case Disponible = 'disponible';
    case Rupture = 'en rupture';
    case Precommande = 'en précommande';

    public function toString(): string
    {
        return match ($this) {
            self::Disponible => 'Disponible',
            self::Rupture => 'En rupture',
            self::Precommande => 'En précommande',
        };
    }
}
?>