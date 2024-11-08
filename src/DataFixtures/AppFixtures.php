<?php
// src/DataFixtures/AppFixtures.php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Address;
use App\Entity\Product;
use App\Entity\Order;
use App\Entity\OrderItem;
use App\Entity\Category;
use App\Entity\Image;
use App\Enum\ProductStatus;
use App\Enum\OrderStatus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        //User 1
        $user1 = new User();
        $user1->setEmail('gwendolinedardari7@gmail.com');
        $user1->setFirstName('Gwendoline');
        $user1->setLastName('Dardari');
        $user1->setRoles(['ROLE_ADMIN']);
        $user1->setPassword($this->passwordHasher->hashPassword($user1,'azerty'));
        $manager->persist($user1);

        //User 2
        $user2 = new User();
        $user2->setEmail('gabriel.dardari@orange.fr');
        $user2->setFirstName('Gabriel');
        $user2->setLastName('Dardari');
        $user2->setRoles(['ROLE_USER']);
        $user2->setPassword($this->passwordHasher->hashPassword($user2,'bonjour'));
        $manager->persist($user2);

        //User 3
        $user3 = new User();
        $user3->setEmail('claire.brevetti@gmail.com');
        $user3->setFirstName('Claire');
        $user3->setLastName('Brevetti');
        $user3->setRoles(['ROLE_USER']);
        $user3->setPassword($this->passwordHasher->hashPassword($user3,'test'));
        $manager->persist($user3);

        // Adresse pour user 1
        $address1 = new Address();
        $address1->setStreet('7 rue anne eugénie milleret');
        $address1->setPostalCode('57000');
        $address1->setCity('Metz');
        $address1->setCountry('France');
        $address1->setUser($user1); 
        $manager->persist($address1);

        // Adresse pour user 2
        $address2 = new Address();
        $address2->setStreet('2 rue des marguerites');
        $address2->setPostalCode('54000');
        $address2->setCity('Nancy');
        $address2->setCountry('France');
        $address2->setUser($user2); 
        $manager->persist($address2);

        // Adresse pour user 3
        $address3 = new Address();
        $address3->setStreet('8 rue saint marcel');
        $address3->setPostalCode('94000');
        $address3->setCity('Paris');
        $address3->setCountry('France');
        $address3->setUser($user3); 
        $manager->persist($address3);

        //Catégorie 1
        $category1 = new Category();
        $category1->setName('Naruto');
        $manager->persist($category1);

        //Catégorie 2
        $category2 = new Category();
        $category2->setName('Jujutsu Kaisen');
        $manager->persist($category2);

        //Catégorie 3
        $category3 = new Category();
        $category3->setName('Tokyo Ghoul');
        $manager->persist($category3);

        // Produit 1
        $product1 = new Product();
        $product1->setName('Pop Sasuke');
        $product1->setPrice(35.00);
        $product1->setDescription('Pop Sasuke de lanime Naruto');
        $product1->setStock(10);
        $product1->setStatus(ProductStatus::Disponible);
        $product1->setCategory($category1);
        $manager->persist($product1);

        // Produit 2
        $product2 = new Product();
        $product2->setName('Pop Naruto');
        $product2->setPrice(20.00);
        $product2->setDescription('Pop Naruto de lanime Naruto');
        $product2->setStock(50);
        $product2->setStatus(ProductStatus::Disponible);
        $product2->setCategory($category1);
        $manager->persist($product2);

        // Produit 3
        $product3 = new Product();
        $product3->setName('Pop Sakura');
        $product3->setPrice(10.00);
        $product3->setDescription('Pop Sakura de lanime Naruto');
        $product3->setStock(100);
        $product3->setStatus(ProductStatus::Disponible);
        $product3->setCategory($category1);
        $manager->persist($product3);

        // Commande User 1
        $order1 = new Order();
        $order1->setReference('ORD-54648');
        $order1->setCreatedAt(new \DateTime());
        $order1->setStatus(OrderStatus::Livre);
        $order1->setUser($user1); 
        $manager->persist($order1);

        // Commande User 2
        $order2 = new Order();
        $order2->setReference('ORD-7894');
        $order2->setCreatedAt(new \DateTime());
        $order2->setStatus(OrderStatus::Annuler);
        $order2->setUser($user2); 
        $manager->persist($order2);

        // Commande User 3
        $order3 = new Order();
        $order3->setReference('ORD-8974');
        $order3->setCreatedAt(new \DateTime());
        $order3->setStatus(OrderStatus::Preparation);
        $order3->setUser($user3); 
        $manager->persist($order3);

        // Order Item 1
        $orderItem1 = new OrderItem();
        $orderItem1->setQuantity(1);
        $orderItem1->setProductPrice($product1->getPrice());
        $orderItem1->setOrderr($order1);
        $orderItem1->setProduct($product1);
        $manager->persist($orderItem1);

        // Order Item 2
        $orderItem2 = new OrderItem();
        $orderItem2->setQuantity(5);
        $orderItem2->setProductPrice($product2->getPrice());
        $orderItem2->setOrderr($order2);
        $orderItem2->setProduct($product2);
        $manager->persist($orderItem2);

        // Order Item 3
        $orderItem3 = new OrderItem();
        $orderItem3->setQuantity(1);
        $orderItem3->setProductPrice($product3->getPrice());
        $orderItem3->setOrderr($order3);
        $orderItem3->setProduct($product3);
        $manager->persist($orderItem3);

        $imageUrl1 = 'images/pop-sasuke.jpg';  
        $image1 = new Image();
        $image1->setUrl($imageUrl1);  
        $image1->setProduct($product1); 
        $manager->persist($image1);  

        $imageUrl2 = 'images/pop-naruto.jpg';  
        $image2 = new Image();
        $image2->setUrl($imageUrl2);  
        $image2->setProduct($product2); 
        $manager->persist($image2);

        $imageUrl3 = 'images/pop-sakura.jpg';  
        $image3 = new Image();
        $image3->setUrl($imageUrl3);  
        $image3->setProduct($product3); 
        $manager->persist($image3);

        $manager->flush();
    }

}
?>