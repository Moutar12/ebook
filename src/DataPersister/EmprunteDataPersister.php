<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;
use App\Entity\Emprunte;
use App\Repository\AdherentRepository;
use App\Repository\EmprunteRepository;
use App\Repository\LivreRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

class EmprunteDataPersister implements ContextAwareDataPersisterInterface
{
    public function __construct(TokenStorageInterface $tokenStorage, LivreRepository $livreRepository,
                                AdherentRepository $adherantRepository, EntityManagerInterface $manager,
                                UserRepository $userRepository, SerializerInterface $serializer,
                                EmprunteRepository $emprunteRepository, ReservationRepository $reserverRepository
    )
    {
        $this->token = $tokenStorage;
        $this->livreRepo = $livreRepository;
        $this->adherantRepo = $adherantRepository;
        $this->manager = $manager;
        $this->personneRepo = $userRepository;
        $this->serialize = $serializer;
        $this->empruntRepo = $emprunteRepository;
        $this->reserverRepo = $reserverRepository;
    }

    public function supports($data, array $context = []): bool
    {
        // TODO: Implement supports() method.
        return $data instanceof Emprunte;
    }

    public function persist($data, array $context = [])
    {
        // TODO: Implement persist() method.
    }

    public function remove($data, array $context = [])
    {
        // TODO: Implement remove() method.
    }
}