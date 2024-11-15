<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class PanierUpdateController extends AbstractController{
    #[Route('/panier/modifier/{productId}', name: 'app_panier_modifier', methods: ['POST'])]
    public function modifierQuantitePanier($productId, Request $request, SessionInterface $session): Response
    {
        $quantity = $request->request->get('quantity');

        if ($quantity < 1) {
            $this->addFlash('error', 'La quantité doit être supérieure ou égale à 1.');
            return $this->redirectToRoute('app_panier');
        }

        $panier = $session->get('panier', []);

        if (isset($panier[$productId])) {
            $panier[$productId]['quantity'] = $quantity;
            $session->set('panier', $panier);

            $this->addFlash('success', 'Quantité mise à jour.');
        } else {
            $this->addFlash('error', 'Produit non trouvé dans le panier.');
        }

        return $this->redirectToRoute('app_panier');
    }
}