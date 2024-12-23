<?php

namespace App\Controller;

use App\Form\ProductType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Product;
use App\Entity\Image;


class ProductAddController extends AbstractController
{
    #[Route('/admin/product/add', name: 'app_product_add')]
    public function addProduct(Request $request, EntityManagerInterface $entityManager): Response
{
    $this->denyAccessUnlessGranted('ROLE_ADMIN'); 

    $product = new Product();
    $form = $this->createForm(ProductType::class, $product);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $imageFile = $form->get('image_file')->getData();
    
        if ($imageFile) {
            $uploadsDirectory = $this->getParameter('kernel.project_dir') . '/public/uploads/images';
            
            $newFilename = uniqid() . '.' . $imageFile->guessExtension();
    
            $imageFile->move($uploadsDirectory, $newFilename);
            $image = new Image();
            $image->setUrl('/uploads/images/' . $newFilename);
            $image->setProduct($product);
            $entityManager->persist($image);
        }
    
        $entityManager->persist($product);
        $entityManager->flush();
    
        $this->addFlash('success', 'Produit ajouté avec succès.');
        return $this->redirectToRoute('app_admin_products');
    }

    return $this->render('product_add/index.html.twig', [
        'form' => $form->createView(),
        'product' => $product,
    ]);
}
}
?>