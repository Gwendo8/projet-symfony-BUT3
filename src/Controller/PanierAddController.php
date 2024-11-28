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
    // PanierAddController.php
#[Route('/panier/ajouter', name: 'app_panier_ajouter', methods: ['POST'])]
public function ajouterAuPanier(Request $request, ProductRepository $productRepository, SessionInterface $session): Response
{
    $productId = $request->request->get('product_id');
    $quantity = $request->request->get('quantity');

    // Vérification du produit dans la base de données
    $product = $productRepository->find($productId);

    if (!$product) {
        return $this->json([
            'error' => 'Produit introuvable.',
        ], Response::HTTP_NOT_FOUND);
    }

    // Récupération du panier depuis la session
    $panier = $session->get('panier', []);

    if (isset($panier[$productId])) {
        // Si le produit est déjà dans le panier, on augmente la quantité
        $panier[$productId]['quantity'] += $quantity;
    } else {
        // Si le produit n'est pas dans le panier, on l'ajoute
        $panier[$productId] = [
            'name' => $product->getName(),
            'price' => $product->getPrice(),
            'quantity' => $quantity,
        ];
    }

    // Mise à jour du panier dans la session
    $session->set('panier', $panier);

    // Calcul du total du panier
    $total = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $panier));

    // Calcul du nombre d'articles dans le panier
    $cartCount = array_sum(array_map(fn($item) => $item['quantity'], $panier));

    // Retourner une réponse JSON pour le front-end avec le total, le nombre d'articles et la mise à jour du panier
// Retour de la réponse avec cartCount dans la réponse JSON
return $this->json([
    'success' => 'Produit ajouté au panier.',
    'total' => $total,
    'cartCount' => $cartCount,  // Nombre d'articles dans le panier
    'itemsHTML' => $this->renderView('panier/index.html.twig', ['panier' => $panier, 'cartCount' => $cartCount]),
]);
}
}
?>