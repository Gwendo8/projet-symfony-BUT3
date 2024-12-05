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
        $queryBuilder = $productRepository->createQueryBuilder('p')
            ->leftJoin('p.images', 'i')  
            ->addSelect('i');  

        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('show_product/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}