<?php
namespace App\DataFixtures;

use App\Entity\Address;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class AddressFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        $userRepository = $manager->getRepository(User::class);
        $users = $userRepository->findAll();
        
        if (empty($users)) {
            throw new \Exception("Aucun utilisateur trouvé pour associer une adresse.");
        }

        for ($i = 0; $i < 10; $i++) {
            $address = new Address();
            $address->setStreet($faker->streetAddress)
                ->setPostalCode($faker->postcode)
                ->setCity($faker->city)
                ->setCountry($faker->country)
                ->setUser($faker->randomElement($users)); 

            $manager->persist($address);
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