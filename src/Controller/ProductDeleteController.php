<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;

class ProductDeleteController extends AbstractController
{
    #[Route('/product/delete/{id}', name: 'app_product_delete')]
    public function delete(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if ($this->isCsrfTokenValid('delete' . $product->getId(), $request->request->get('_token'))) {
            if ($product->getOrderItems()->isEmpty()) {
                $entityManager->remove($product);
                $entityManager->flush();
                $this->addFlash('success', 'Produit supprimé avec succès.');
            } else {
                $this->addFlash('error', 'Impossible de supprimer ce produit car il fait partie d\'une commande.');
            }
        }

        return $this->redirectToRoute('app_admin_products'); 
    }

}
