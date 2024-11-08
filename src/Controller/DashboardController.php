<?php
// src/Controller/DashboardController.php

namespace App\Controller;

use App\Repository\OrderRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function index(OrderRepository $orderRepository, ProductRepository $productRepository): Response
    {
        $productsCategory = $productRepository->countProductsCategory();
        $lastOrders = $orderRepository->findLastFiveOrders();
        $productsStatus = $productRepository->countProductStatus();
        $salesMonth = $orderRepository->salesMonth();

        return $this->render('dashboard/index.html.twig', [
            'productsCategory' => $productsCategory,
            'lastOrders' => $lastOrders,
            'productsStatus'=> $productsStatus,
            'salesMonth' => $salesMonth,
        ]);
    }
}
?>