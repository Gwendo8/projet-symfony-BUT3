<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Enum\OrderStatus;

class CommandeController extends AbstractController{
    #[Route('/checkout', name: 'checkout', methods: ['POST'])]
    public function checkout(SessionInterface $session, ProductRepository $productRepository, EntityManagerInterface $entityManager): Response
    {
        $panier = $session->get('panier', []);
        if (empty($panier)) {
            $this->addFlash('error', 'Votre panier est vide.');
            return $this->redirectToRoute('app_panier');
        }
    
        $order = new Order();
        $order->setUser($this->getUser());
    
        $orderCount = $entityManager->getRepository(Order::class)->count([]);
        $year = (new \DateTime())->format('Y');
        $reference = sprintf('ORD-%s-%04d', $year, $orderCount + 1);
        $order->setReference($reference);
    
        $order->setCreatedAt(new \DateTime());
        $order->setStatus(OrderStatus::Preparation);
    
        foreach ($panier as $productId => $details) {
            $product = $productRepository->find($productId);
    
            if (!$product || $product->getStock() < $details['quantity']) {
                $this->addFlash('error', 'Le stock du produit ' . $product->getName() . ' est insuffisant.');
                return $this->redirectToRoute('app_panier');
            }
    
            $orderItem = new OrderItem();
            $orderItem->setProduct($product);
            $orderItem->setQuantity($details['quantity']);
            $orderItem->setProductPrice($details['price']);
            $orderItem->setOrderr($order);
    
            $entityManager->persist($orderItem);
    
            $product->setStock($product->getStock() - $details['quantity']);
            $entityManager->persist($product);
        }
    
        $entityManager->persist($order);
        $entityManager->flush();
    
        $session->remove('panier');
    
        $this->addFlash('success', 'Commande passée avec succès. Voici votre numéro de commande : ' . $order->getReference());
    
        return $this->redirectToRoute('app_panier');
    }
}