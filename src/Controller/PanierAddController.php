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

    $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $panier));
    $cartCount = array_sum(array_map(fn($item) => $item['quantity'], $panier));

    $itemsHTML = $this->renderView('panier/items.html.twig', [
        'panier' => $panier,
    ]);
    return $this->json([
        'success' => true,
        'total' => $total,
        'cartCount' => $cartCount,
        'itemsHTML' => $itemsHTML,
    ]);
}
}
?>