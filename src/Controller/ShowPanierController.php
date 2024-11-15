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
use App\Enum\OrderStatus;

class ShowPanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function afficherPanier(SessionInterface $session, EntityManagerInterface $entityManager): Response
    {
        $panier = $session->get('panier', []);

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
}
?>