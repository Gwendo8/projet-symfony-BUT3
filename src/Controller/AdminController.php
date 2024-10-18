<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;

class AdminController extends AbstractController
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

        return $this->render('admin/index.html.twig', [
            'pagination' => $pagination,
        ]);
    }

    #[Route('/user/edit/{id}', name: 'app_user_edit')]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        // Créez le formulaire avec l'utilisateur existant
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Ici, le flush mettra à jour tous les champs, y compris les rôles
            $entityManager->flush();

            return $this->redirectToRoute('app_admin');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/user/delete/{id}', name: 'app_user_delete')]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {

        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_admin');
    }
}
