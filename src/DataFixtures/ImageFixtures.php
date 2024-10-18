<?php

namespace App\DataFixtures;

use App\Entity\Image;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ImageFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Récupérer tous les produits existants
        $products = $manager->getRepository(Product::class)->findAll();

        if (empty($products)) {
            throw new \Exception("Aucun produit trouvé. Veuillez créer des produits avant de créer des images.");
        }

        // Créer des images pour chaque produit
        foreach ($products as $product) {
            // Générer un nombre aléatoire d'images par produit
            for ($j = 0; $j < mt_rand(1, 5); $j++) {
                $image = new Image();
                $image->setUrl($faker->imageUrl()) // Générer une URL d'image aléatoire
                      ->setProduct($product); // Associer l'image au produit

                $manager->persist($image);
            }
        }

        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            AppFixtures::class,
        ];
    }
}
?>