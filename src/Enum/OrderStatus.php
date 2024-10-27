<?php
namespace App\Enum;

enum OrderStatus: string
{
    case InPreparation = 'en préparation';
    case Shipped = 'expédiée';
    case Delivered = 'livrée';
    case Canceled = 'annulée';

    public function toString(): string
    {
        return $this->value; 
    }
}
?>