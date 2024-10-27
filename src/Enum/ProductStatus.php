<?php
namespace App\Enum;

enum ProductStatus: string
{
    case Available = 'disponible';
    case OutOfStock = 'en rupture';
    case PreOrder = 'en précommande';

    public function toString(): string
    {
        return match ($this) {
            self::Available => 'Disponible',
            self::OutOfStock => 'En rupture',
            self::PreOrder => 'En précommande',
        };
    }
}
?>