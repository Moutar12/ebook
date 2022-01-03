<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Repository\ProfilRepository;
use App\service\SendMail;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    public function __construct(UserPasswordEncoderInterface $encoder, EntityManagerInterface $manager,
                                ProfilRepository $profilRepository, UserRepository $userRepository,
                                SendMail $sendMail
    ){
        $this->encoder = $encoder;
        $this->profilRepo = $profilRepository;
        $this->personneRepo = $userRepository;
        $this->manager = $manager;
        $this->sendMail = $sendMail;
    }
    /**
     * @Route(
     *      name="AddUser",
     *      methods={"POST"},
     *      path="/api/user",
     *      defaults={
     *          "__controller"="App\Controller\UserController::AddUser",
     *          "__api_resource_class"=User::class,
     *          "__api_collection_operation_name"="AddUser"
     *     }
     *     )
     */
    public function AddUser(Request $request): Response
    {
        $info = json_decode($request->getContent(), true);
        $profil = $info["profil"];

        $personne = new User();
        $personne->setPrenom($info["prenom"])
            ->setNom($info['nom'])
            ->setAdresse($info["adresse"])
            ->setStatus(true)
            ->setEmail($info["email"])
            ->setProfil($this->profilRepo->findOneBy(["id" => $profil]))
            ->setPassword($this->encoder->encodePassword($personne, $info['password']));
        $this->manager->persist($personne);
        $this->manager->flush();
        $this->sendMail->send($personne->getEmail(), 'resgistration', "Registration success");
        return $this->json("Personne ajoute",Response::HTTP_CREATED);

    }

    /**
     * @Route(
     *      name="updateUser",
     *      methods={"PUT"},
     *      path="/api/user/{id}",
     *      defaults={
     *          "__controller"="App\Controller\UserController::updateUser",
     *          "__api_resource_class"=User::class,
     *          "__api_collection_operation_name"="updateUser"
     *     }
     *     )
     */

    public function updateUser(Request $request, $id){

        $info = json_decode($request->getContent(), true);
        $personne = $this->personneRepo->findOneBy(["id" => $id]);
        $personne->setPrenom($info["prenom"])
            ->setNom($info["nom"])
            ->setEmail($info["email"])
            ->setStatus(true)
            ->setAdresse($info["adresse"])
            ->setProfil($this->profilRepo->findOneBy(["id" => $info['profil']]))
            ->setPassword($this->encoder->encodePassword($personne, $info["password"]));
        $this->manager->persist($personne);
        $this->manager->flush();
        return $this->json("Personne modifié",Response::HTTP_CREATED);


    }
}
