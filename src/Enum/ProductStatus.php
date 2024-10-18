<?php
namespace App\Enum;

enum ProductStatus: string
{
    case Available = 'disponible';
    case OutOfStock = 'en rupture';
    case PreOrder = 'en précommande';
}
?>