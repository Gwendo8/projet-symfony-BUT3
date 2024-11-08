<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;


class ShowProductController extends AbstractController
{
    #[Route('/admin/products', name: 'app_admin_products')]
    public function listProducts(PaginatorInterface $paginator, Request $request, ProductRepository $productRepository): Response
    {
        // Récupérer tous les produits avec leurs images associées
        $queryBuilder = $productRepository->createQueryBuilder('p')
            ->leftJoin('p.images', 'i')  // Effectuer une jointure avec la table des images
            ->addSelect('i');  // Inclure les images dans la requête

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        // Passer les produits avec les images à la vue
        return $this->render('show_product/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}