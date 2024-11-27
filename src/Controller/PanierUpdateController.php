<?php
namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class PanierUpdateController extends AbstractController
{
    #[Route('/panier/modifier/{productId}', name: 'app_panier_modifier', methods: ['POST'])]
    // PanierUpdateController.php

public function modifierQuantitePanier($productId, Request $request, SessionInterface $session, LoggerInterface $logger): JsonResponse
{
    // Log pour déboguer la requête brute
    $logger->info("Requête brute reçue : ", ['request' => $request->request->all()]);

    // Récupération de la quantité depuis form-data
    $quantity = $request->request->get('quantity');
    $logger->info("Quantité reçue par le serveur : " . json_encode($quantity));

    if (!$quantity || $quantity < 1) {
        $logger->warning("Tentative de définir une quantité invalide : " . json_encode($quantity));
        return new JsonResponse(['success' => false, 'message' => 'La quantité doit être supérieure ou égale à 1.']);
    }

    // Gestion du panier
    $panier = $session->get('panier', []);
    
    if (isset($panier[$productId])) {
        // Mise à jour de la quantité du produit dans le panier
        $panier[$productId]['quantity'] = (int) $quantity;

        // Mise à jour du panier dans la session
        $session->set('panier', $panier);

        // Calcul du total du panier
        $total = 0;
        foreach ($panier as $product) {
            $total += $product['quantity'] * $product['price'];  // Multiplie la quantité par le prix unitaire
        }

        // Retourner une réponse JSON avec les données mises à jour
        return new JsonResponse([
            'success' => true,
            'message' => 'Quantité mise à jour.',
            'panier' => [
                'itemsHTML' => $this->renderView('panier/index.html.twig', ['panier' => $panier]),
                'total' => $total
            ]
        ]);
    } else {
        return new JsonResponse(['success' => false, 'message' => 'Produit non trouvé dans le panier.']);
    }
}
}
?>