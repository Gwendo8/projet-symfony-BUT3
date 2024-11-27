<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;

class PanierAddController extends AbstractController{
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
            $panier[$productId]['quantity'] += $quantity;
        } else {
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

        // Retourner une réponse JSON pour le front-end
        return $this->json([
            'success' => 'Produit ajouté au panier.',
            'total' => $total,
            'itemsHTML' => $this->renderView('panier/index.html.twig', ['panier' => $panier]),
        ]);
    }
}