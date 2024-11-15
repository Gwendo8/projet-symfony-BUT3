<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class PanierDeleteController extends AbstractController{
    #[Route('/panier/supprimer/{productId}', name: 'app_panier_supprimer', methods: ['POST'])]
    public function supprimerDuPanier(int $productId, SessionInterface $session): Response
    {
        $panier = $session->get('panier', []);

        if (isset($panier[$productId])) {
            unset($panier[$productId]);
        }

        $session->set('panier', $panier);

        $this->addFlash('success', 'Produit supprimé du panier.');

        return $this->redirectToRoute('app_panier');
    }
}