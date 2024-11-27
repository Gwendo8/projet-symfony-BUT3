<?php
// src/Controller/PanierDeleteController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PanierDeleteController extends AbstractController
{
    #[Route('/panier/supprimer/{productId}', name: 'app_panier_supprimer', methods: ['POST'])]
    public function supprimerDuPanier(int $productId, SessionInterface $session): Response
    {
        $panier = $session->get('panier', []);

        if (isset($panier[$productId])) {
            unset($panier[$productId]);
        }

        $session->set('panier', $panier);

        // Retourne les données sous forme de tableau
        return $this->json([
            'panier' => array_values($panier),  // Utilisation de array_values pour retourner un tableau indexé
            'success' => true,
        ]);
    }
}
?>