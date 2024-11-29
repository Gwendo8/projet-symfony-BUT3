<?php
// src/Controller/ProductSearchController.php
namespace App\Controller;

use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ProductSearchController extends AbstractController
{
    #[Route('/search/products', name: 'product_search', methods: ['GET'])]
    public function search(Request $request, ProductRepository $productRepository): JsonResponse
    {
        $query = $request->query->get('q', '');

        if (empty($query)) {
            return $this->json([]);
        }

        $products = $productRepository->findByPartialName($query);

        return $this->json($products);
    }
}
?>