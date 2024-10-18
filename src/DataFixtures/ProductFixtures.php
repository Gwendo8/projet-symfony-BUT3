<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Image;
use App\Enum\ProductStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProductFixtures extends Fixture
{
    public const PRODUCT_REF = "product_ref";
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 10; $i++) {
            $product = new Product();
            $product->setName($faker->word)
                ->setPrice($faker->randomFloat(2, 10, 100))
                ->setDescription($faker->text)
                ->setStatus($faker->randomElement(ProductStatus::cases())); 

            for ($j = 0; $j < mt_rand(1, 3); $j++) {
                $image = new Image();
                $image->setUrl($faker->imageUrl()); 
                $product->addImage($image);
                $manager->persist($image);
            }

            $manager->persist($product);
            $this->addReference(self::PRODUCT_REF."_".$i, $product);
        }

        $manager->flush();
    }
}
?>