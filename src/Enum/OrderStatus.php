<?php
namespace App\Enum;

enum OrderStatus: string
{
    case Preparation = 'en préparation';
    case Expedie = 'expédiée';
    case Livre = 'livrée';
    case Annuler = 'annulée';
    case Validee = 'validée';

    public function toString(): string
    {
        return $this->value; 
    }
}
?>