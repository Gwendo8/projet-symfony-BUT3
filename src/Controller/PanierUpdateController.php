<?php
namespace App\Controller;

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;

class PanierUpdateController extends AbstractController
{
    #[Route('/panier/modifier/{productId}', name: 'app_panier_modifier', methods: ['POST'])]
    // Exemple de contrôleur mis à jour
public function modifierPanier(Request $request, string $productId, SessionInterface $session, LoggerInterface $logger): JsonResponse
{
    // Log de débogage pour vérifier l'entrée
    $logger->info("Modification de panier pour le produit {$productId}");

    $quantity = (int) $request->request->get('quantity');
    $panier = $session->get('panier', []);
    
    // Log de débogage pour vérifier la quantité et l'état du panier
    $logger->info("Quantité reçue : {$quantity}");
    $logger->info("État actuel du panier : " . json_encode($panier));

    if (isset($panier[$productId])) {
        $panier[$productId]['quantity'] = $quantity; // Met à jour la quantité
        $session->set('panier', $panier);

        // Log de confirmation de mise à jour
        $logger->info("Quantité mise à jour pour le produit {$productId}");
    } else {
        // Log si le produit n'est pas dans le panier
        $logger->warning("Produit {$productId} non trouvé dans le panier.");
    }

    $total = array_sum(array_map(fn($item) => $item['quantity'] * $item['price'], $panier));

    // Log du total calculé
    $logger->info("Total calculé : {$total}");

    // Retourne uniquement les éléments HTML mis à jour et le total
    return $this->json([
        'itemsHTML' => $this->renderView('panier/items.html.twig', ['panier' => $panier]), // Utilisez un fragment pour les lignes du panier
        'total' => $total,
    ]);
}
}
?>