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
public function modifierPanier(Request $request, string $productId, SessionInterface $session, LoggerInterface $logger): JsonResponse
{
    $logger->info("Modification de panier pour le produit {$productId}");

    $quantity = (int) $request->request->get('quantity');
    $panier = $session->get('panier', []);
    
    $logger->info("Quantité reçue : {$quantity}");
    $logger->info("État actuel du panier : " . json_encode($panier));

    if (isset($panier[$productId])) {
        $panier[$productId]['quantity'] = $quantity; 
        $session->set('panier', $panier);

        $logger->info("Quantité mise à jour pour le produit {$productId}");
    } else {
        $logger->warning("Produit {$productId} non trouvé dans le panier.");
    }

    $total = array_sum(array_map(fn($item) => $item['quantity'] * $item['price'], $panier));

    $logger->info("Total calculé : {$total}");

    return $this->json([
        'itemsHTML' => $this->renderView('panier/items.html.twig', ['panier' => $panier]), 
        'total' => $total,
    ]);
}
}
?>