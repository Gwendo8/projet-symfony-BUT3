<?php
// src/Controller/PanierDeleteController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;


class PanierDeleteController extends AbstractController
{
    #[Route('/panier/supprimer/{productId}', name: 'app_panier_supprimer', methods: ['POST'])]
public function supprimerDuPanier(int $productId, SessionInterface $session): JsonResponse
{
    $panier = $session->get('panier', []);
    if (!isset($panier[$productId])) {
        return $this->json(['error' => 'Produit non trouvé dans le panier'], Response::HTTP_NOT_FOUND);
    }

    unset($panier[$productId]);

    $session->set('panier', $panier);

    $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $panier));

    return $this->json([
        'success' => 'Produit supprimé du panier.',
        'total' => $total,
        'itemsHTML' => $this->renderView('panier/items.html.twig', ['panier' => $panier]),
    ]);
}
}
?>