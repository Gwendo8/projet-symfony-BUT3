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

        $product = $productRepository->find($productId);

        if (!$product) {
            $this->addFlash('error', 'Produit introuvable.');
            return $this->redirectToRoute('app_product_index');
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

        $this->addFlash('success', 'Produit ajouté au panier.');

        return $this->render('product_user/index.html.twig', [
            'products' => $productRepository->findAll(),
        ]);
    }
}
