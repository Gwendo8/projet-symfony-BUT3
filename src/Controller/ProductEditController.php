<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Form\ProductType;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;
use Symfony\Component\HttpFoundation\Request;

class ProductEditController extends AbstractController
{
    #[Route('/admin/product/edit/{id}', name: 'app_product_edit')]
    public function editProduct(Request $request, EntityManagerInterface $entityManager, Product $product = null): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        if (!$product) {
            $product = new Product();
        }

        $form = $this->createForm(ProductType::class, $product);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($product);
            $entityManager->flush();
            $this->addFlash('success', 'Produit mis à jour avec succès.');

            return $this->redirectToRoute('app_admin_products');
        }

        return $this->render('product_edit/index.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
        ]);
    }

}
