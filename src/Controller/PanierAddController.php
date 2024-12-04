<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;

class PanierAddController extends AbstractController
{
    #[Route('/panier/ajouter', name: 'app_panier_ajouter', methods: ['POST'])]
public function ajouterAuPanier(Request $request, ProductRepository $productRepository, SessionInterface $session): Response
{
    $productId = $request->request->get('product_id');
    $quantity = $request->request->get('quantity');

    $product = $productRepository->find($productId);

    if (!$product) {
        return $this->json(['error' => 'Produit introuvable.'], Response::HTTP_NOT_FOUND);
    }

    // Récupérer le panier existant dans la session
    $panier = $session->get('panier', []);

    if (isset($panier[$productId])) {
        $panier[$productId]['quantity'] += $quantity;
    } else {
        $panier[$productId] = [
            'name' => $product->getName(),
            'price' => $product->getPrice(),
            'quantity' => $quantity,
        ];
    }

    $session->set('panier', $panier);

    // Calculer le total et le nombre d'articles
    $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $panier));
    $cartCount = array_sum(array_map(fn($item) => $item['quantity'], $panier));

    // Générer le HTML mis à jour du panier
    $itemsHTML = $this->renderView('panier/items.html.twig', [
        'panier' => $panier,
    ]);

    return $this->json([
        'success' => true,
        'total' => $total,
        'cartCount' => $cartCount,
        'itemsHTML' => $itemsHTML,  // Renvoie le HTML mis à jour
    ]);
}
}
?>