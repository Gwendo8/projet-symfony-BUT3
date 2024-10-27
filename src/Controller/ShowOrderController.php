<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\OrderRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;


class ShowOrderController extends AbstractController
{
    #[Route('/admin/orders', name: 'app_admin_orders')]
    public function listOrders(PaginatorInterface $paginator, Request $request, OrderRepository $orderRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $queryBuilder = $orderRepository->createQueryBuilder('o');
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('show_order/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}
