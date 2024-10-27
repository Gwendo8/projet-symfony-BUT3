<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class ShowUserController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function admin(PaginatorInterface $paginator, Request $request, UserRepository $userRepository): Response
    {
        $queryBuilder = $userRepository->createQueryBuilder('u');
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('show_user/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }
}