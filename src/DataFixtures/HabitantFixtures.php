<?php

namespace App\DataFixtures;

use App\Entity\Habitant;
use App\Entity\Adresse;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class HabitantFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 100; $i++) {
            $habitant = new Habitant();
            $habitant->setNom($faker->lastName)
                     ->setPrenom($faker->firstName)
                     ->setDateNaissance($faker->dateTimeBetween('-100 years', '-18 years'))
                     ->setGenre($faker->randomElement(['homme', 'femme', 'autre']));

            // Create a fake Adresse and associate it with the Habitant
            $adresse = new Adresse();
            $adresse->setNumero($faker->buildingNumber)
                    ->setNomRue($faker->streetName);

            $manager->persist($adresse);
            $habitant->setAdresseHabitant($adresse);

            $manager->persist($habitant);
        }

        $manager->flush();
    }
}
