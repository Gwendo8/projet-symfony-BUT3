<?php
// src/DataFixtures/OrderFixtures.php
namespace App\DataFixtures;

use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Product; // Assurez-vous d'importer l'entité Product
use App\Entity\User;
use App\Enum\OrderStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class OrderFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $users = $manager->getRepository(User::class)->findAll();
        $products = $manager->getRepository(Product::class)->findAll(); // Récupérer tous les produits

        if (empty($users)) {
            throw new \Exception("Aucun utilisateur trouvé. Veuillez créer des utilisateurs avant de créer des commandes.");
        }

        if (empty($products)) {
            throw new \Exception("Aucun produit trouvé. Veuillez créer des produits avant de créer des commandes.");
        }

        for ($i = 0; $i < 10; $i++) {
            $order = new Order();
            $order->setReference($faker->unique()->uuid)
                ->setCreatedAt($faker->dateTimeThisYear) 
                ->setStatus($faker->randomElement(OrderStatus::cases())) 
                ->setUser($faker->randomElement($users)); 

            for ($j = 0; $j < mt_rand(1, 5); $j++) {
                $orderItem = new OrderItem();
                $product = $this->getReference(ProductFixtures::PRODUCT_REF."_".$j); 
                $orderItem->setQuantity(mt_rand(1, 3))
                    ->setProductPrice($product->getPrice())
                    ->setProduct($product)
                    ->setOrderr($order); 

                $manager->persist($orderItem);
            }

            $manager->persist($order);
        }

        $manager->flush();
    }
    public function getDependencies()
    {
        return [
            ProductFixtures::class,
        ];
    }
}
?>