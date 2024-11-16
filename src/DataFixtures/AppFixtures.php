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

        //Catégorie 4
        $category4 = new Category();
        $category4->setName('Boruto');
        $manager->persist($category4);

        // Produit 1
        $product1 = new Product();
        $product1->setName('Pop Uchiha Sasuke');
        $product1->setPrice(35.00);
        $product1->setDescription('Pop Sasuke de lanime Naruto');
        $product1->setStock(10);
        $product1->setStatus(ProductStatus::Disponible);
        $product1->setCategory($category1);
        $manager->persist($product1);

        // Produit 2
        $product2 = new Product();
        $product2->setName('Pop Uzumaki Naruto');
        $product2->setPrice(20.00);
        $product2->setDescription('Pop Naruto de lanime Naruto');
        $product2->setStock(50);
        $product2->setStatus(ProductStatus::Disponible);
        $product2->setCategory($category1);
        $manager->persist($product2);

        // Produit 3
        $product3 = new Product();
        $product3->setName('Pop Haruno Sakura');
        $product3->setPrice(10.00);
        $product3->setDescription('Pop Sakura de lanime Naruto');
        $product3->setStock(100);
        $product3->setStatus(ProductStatus::Disponible);
        $product3->setCategory($category1);
        $manager->persist($product3);

        // Produit 4
        $product4 = new Product();
        $product4->setName('Pop Hatake Kakashi');
        $product4->setPrice(17.00);
        $product4->setDescription('Pop Kakashi de lanime Naruto');
        $product4->setStock(100);
        $product4->setStatus(ProductStatus::Disponible);
        $product4->setCategory($category1);
        $manager->persist($product4);

        // Produit 5
        $product5 = new Product();
        $product5->setName('Pop Uchiha Shisui');
        $product5->setPrice(15.00);
        $product5->setDescription('Pop Shisui de lanime Naruto');
        $product5->setStock(100);
        $product5->setStatus(ProductStatus::Disponible);
        $product5->setCategory($category1);
        $manager->persist($product5);

        // Produit 6
        $product6 = new Product();
        $product6->setName('Pop Uzumaki Minato');
        $product6->setPrice(17.00);
        $product6->setDescription('Pop Minato de lanime Naruto');
        $product6->setStock(100);
        $product6->setStatus(ProductStatus::Disponible);
        $product6->setCategory($category1);
        $manager->persist($product6);

         // Produit 7
         $product7 = new Product();
         $product7->setName('Pop Uzumaki Boruto');
         $product7->setPrice(30.00);
         $product7->setDescription('Pop Boruto de lanime Boruto');
         $product7->setStock(100);
         $product7->setStatus(ProductStatus::Disponible);
         $product7->setCategory($category4);
         $manager->persist($product7);

         // Produit 8
         $product8 = new Product();
         $product8->setName('Pop Ôtsutsuki Boruto');
         $product8->setPrice(35.00);
         $product8->setDescription('Pop Boruto Ôtsutsuki de lanime Boruto');
         $product8->setStock(100);
         $product8->setStatus(ProductStatus::Disponible);
         $product8->setCategory($category4);
         $manager->persist($product8);

         // Produit 9
         $product9 = new Product();
         $product9->setName('Pop Ôtsutsuki Kawaki');
         $product9->setPrice(15.00);
         $product9->setDescription('Pop Kawaki de lanime Boruto');
         $product9->setStock(100);
         $product9->setStatus(ProductStatus::Disponible);
         $product9->setCategory($category4);
         $manager->persist($product9);

         // Produit 10
         $product10 = new Product();
         $product10->setName('Pop Ken Kaneki');
         $product10->setPrice(30.00);
         $product10->setDescription('Pop Kaneki de lanime Tokyo Ghoul');
         $product10->setStock(100);
         $product10->setStatus(ProductStatus::Disponible);
         $product10->setCategory($category3);
         $manager->persist($product10);

         // Produit 11
         $product11 = new Product();
         $product11->setName('Pop Kirishima Touka');
         $product11->setPrice(10.00);
         $product11->setDescription('Pop Touka de lanime Tokyo Ghoul');
         $product11->setStock(100);
         $product11->setStatus(ProductStatus::Disponible);
         $product11->setCategory($category3);
         $manager->persist($product11);

         // Produit 12
         $product12 = new Product();
         $product12->setName('Pop Kisho Arima');
         $product12->setPrice(15.00);
         $product12->setDescription('Pop Arima de lanime Tokyo Ghoul');
         $product12->setStock(100);
         $product12->setStatus(ProductStatus::Disponible);
         $product12->setCategory($category3);
         $manager->persist($product12);

         // Produit 13
         $product13 = new Product();
         $product13->setName('Pop Ryomen Sukuna');
         $product13->setPrice(30.00);
         $product13->setDescription('Pop Sukuna de lanime Jujutsu Kaisen');
         $product13->setStock(100);
         $product13->setStatus(ProductStatus::Disponible);
         $product13->setCategory($category3);
         $manager->persist($product13);
         
         // Produit 14
         $product14 = new Product();
         $product14->setName('Pop Saturo Gojo');
         $product14->setPrice(35.00);
         $product14->setDescription('Pop Gojo de lanime Jujutsu Kaisen');
         $product14->setStock(100);
         $product14->setStatus(ProductStatus::Disponible);
         $product14->setCategory($category3);
         $manager->persist($product14);

         // Produit 15
         $product15 = new Product();
         $product15->setName('Pop Fushiguro Megumi');
         $product15->setPrice(15.00);
         $product15->setDescription('Pop Megumi de lanime Jujutsu Kaisen');
         $product15->setStock(100);
         $product15->setStatus(ProductStatus::Disponible);
         $product15->setCategory($category3);
         $manager->persist($product15);

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

        //Image 
        $imageUrl1 = 'images/pop-sasu.jpg';  
        $image1 = new Image();
        $image1->setUrl($imageUrl1);  
        $image1->setProduct($product1); 
        $manager->persist($image1);  

        $imageUrl2 = 'images/pop-naru.jpg';  
        $image2 = new Image();
        $image2->setUrl($imageUrl2);  
        $image2->setProduct($product2); 
        $manager->persist($image2);

        $imageUrl3 = 'images/pop-sakura.jpg';  
        $image3 = new Image();
        $image3->setUrl($imageUrl3);  
        $image3->setProduct($product3); 
        $manager->persist($image3);

        $imageUrl4 = 'images/pop-kakashi.jpg';  
        $image4 = new Image();
        $image4->setUrl($imageUrl4);  
        $image4->setProduct($product4); 
        $manager->persist($image4);

        $imageUrl5 = 'images/pop-sishui.jpg';  
        $image5 = new Image();
        $image5->setUrl($imageUrl5);  
        $image5->setProduct($product5); 
        $manager->persist($image5);

        $imageUrl6 = 'images/pop-minato.jpg';  
        $image6 = new Image();
        $image6->setUrl($imageUrl6);  
        $image6->setProduct($product6); 
        $manager->persist($image6);

        $imageUrl7 = 'images/pop-minato.jpg';  
        $image6 = new Image();
        $image6->setUrl($imageUrl6);  
        $image6->setProduct($product6); 
        $manager->persist($image6);

        $imageUrl7 = 'images/pop-boruto.jpg';  
        $image7 = new Image();
        $image7->setUrl($imageUrl7);  
        $image7->setProduct($product7); 
        $manager->persist($image7);

        $imageUrl8 = 'images/pop-boruto-otso.jpg';  
        $image8 = new Image();
        $image8->setUrl($imageUrl8);  
        $image8->setProduct($product8); 
        $manager->persist($image8);

        $imageUrl9 = 'images/pop-kawaki.jpg';  
        $image9 = new Image();
        $image9->setUrl($imageUrl9);  
        $image9->setProduct($product9); 
        $manager->persist($image9);

        $imageUrl10 = 'images/pop-kaneki.jpg';  
        $image10 = new Image();
        $image10->setUrl($imageUrl10);  
        $image10->setProduct($product10); 
        $manager->persist($image10);

        $imageUrl11 = 'images/pop-touka.jpg';  
        $image11 = new Image();
        $image11->setUrl($imageUrl11);  
        $image11->setProduct($product11); 
        $manager->persist($image11);

        $imageUrl12 = 'images/pop-arima.jpg';  
        $image12 = new Image();
        $image12->setUrl($imageUrl12);  
        $image12->setProduct($product12); 
        $manager->persist($image12);

        $imageUrl13 = 'images/pop-sukuna.jpg';  
        $image13 = new Image();
        $image13->setUrl($imageUrl13);  
        $image13->setProduct($product13); 
        $manager->persist($image13);

        $imageUrl14 = 'images/pop-gojo.jpg';  
        $image14 = new Image();
        $image14->setUrl($imageUrl14);  
        $image14->setProduct($product14); 
        $manager->persist($image14);

        $imageUrl15 = 'images/pop-megumi.jpg';  
        $image15 = new Image();
        $image15->setUrl($imageUrl15);  
        $image15->setProduct($product15); 
        $manager->persist($image15);

        $manager->flush();
    }

}
?>