<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Personne;
use App\Entity\User;
use App\Repository\PersonneRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

class UserDataPersister implements ContextAwareDataPersisterInterface
{

    public function __construct(EntityManagerInterface $manager, UserRepository $userRepository){
        $this->manager =$manager;
        $this->personneRepo =$userRepository;
    }
    public function supports($data, array $context = []): bool
    {
        // TODO: Implement supports() method.
        return $data instanceof User;
    }

    public function persist($data, array $context = [])
    {
        // TODO: Implement persist() method.
        return $data;
    }

    public function remove($data, array $context = [])
    {
        // TODO: Implement remove() method.
        $data->setStatus(false);
        $this->manager->persist($data);
        $this->manager->flush();
    }
}