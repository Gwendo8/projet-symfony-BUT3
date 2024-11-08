<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Order;
use App\Entity\OrderItem;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function afficherPanier(SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        $panier = $session->get('panier', []);

        // Création d'un objet Order temporaire basé sur le panier
        $order = new Order();
        foreach ($panier as $productId => $details) {
            $product = $entityManager->getRepository(Product::class)->find($productId);
            if ($product) {
                $orderItem = new OrderItem();
                $orderItem->setProduct($product);
                $orderItem->setQuantity($details['quantity']);
                $orderItem->setProductPrice($details['price']);
                $order->addOrderItem($orderItem);
            }
        }

        return $this->render('panier/index.html.twig', [
            'panier' => $panier,
            'order' => $order,
        ]);
    }

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

    #[Route('/order/validate/{id}', name: 'order_validate')]
    public function validateOrder(int $id, EntityManagerInterface $entityManager): Response
    {
        $order = $entityManager->getRepository(Order::class)->find($id);

        if (!$order) {
            throw $this->createNotFoundException("Commande non trouvée.");
        }

        if ($order->validateOrder()) {
            $entityManager->flush();
            $this->addFlash('success', 'Commande validée avec succès.');
        } else {
            $this->addFlash('error', 'Stock insuffisant pour certains produits.');
        }

        return $this->redirectToRoute('order_detail', ['id' => $id]);
    }
}
?>