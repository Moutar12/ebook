<?php

namespace App\DataFixtures;

use App\Entity\Emprunte;
use App\Entity\Genre;
use App\Entity\Livre;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{



    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");
        $genres = ["Livre Mathématique", "Oeuvre", "Livre Histoire"];
        foreach ($genres as $key => $libelle) {
            $genre = new  Genre();
            $genre->setLibelle($libelle);
            $manager->persist($genre);
            $manager->flush();
            for ($i = 1; $i < 3; $i++) {
                $livre = new  Livre();
                $livre->setGenre($genre);
                $livre->setTitre($faker->sentence($nbWords = 6, $variableNbWords = true))
                    ->setAuteur($faker->name())
                    ->setAnnee($faker->date('Y-m-d'))
                    ->setDispo(true);

                $manager->persist($livre);
            }

        }



        $manager->flush();
    }


}
