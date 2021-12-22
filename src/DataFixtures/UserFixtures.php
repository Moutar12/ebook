<?php

namespace App\DataFixtures;

use App\Entity\Personne;
use App\Entity\Profil;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    public function __construct(UserPasswordEncoderInterface $encoder){
        $this->encoder = $encoder;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create("fr_FR");
        $profils = ["ADMIN", "ADHERENT"];
        foreach ($profils as $key => $libelle){
            $profil = new Profil();
            $profil->setLibelle($libelle);
            $manager->persist($profil);
            $manager->flush();
            for ($i = 1; $i<=3; $i++){
                $user = new User();
                $user->setProfil($profil)
                    ->setEmail(strtolower($libelle). $i. "@gmail.com")
                    ->setPrenom($faker->firstName)
                    ->setNom($faker->lastName)
                    ->setStatus(true)
                    ->setAdresse($faker->address());
                $password = $this->encoder->encodePassword($user, "pass123");
                $user->setPassword($password);
                $manager->persist($user);
            }
        }

        $manager->flush();
    }
}
