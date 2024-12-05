<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ProductUserController extends AbstractController
{
    #[Route('/produits/user', name: 'app_product_index')]
public function index(SessionInterface $session, ProductRepository $productRepository)
{
    $products = $productRepository->findAll();
    $panier = $session->get('panier', []);
    $cartCount = array_sum(array_map(fn($item) => $item['quantity'], $panier));

    return $this->render('product_user/index.html.twig', [
        'products' => $products,
        'cartCount' => $cartCount, 
    ]);
}
}
