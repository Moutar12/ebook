<?php

namespace App\Controller;

use App\Entity\Emprunte;
use App\Repository\AdherentRepository;
use App\Repository\EmprunteRepository;
use App\Repository\LivreRepository;
use App\Repository\ReservationRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Serializer\SerializerInterface;

class EmprunteController extends AbstractController
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

    /**
     * @Route(
     *     name="emprunt",
     *     methods={"POST"},
     *      path="/api/emprunt",
     *      defaults={
     *          "__controller"="App\Controller\EmprunteController::emprunt",
     *          "__api_resource_class"=Emprunte::class,
     *          "__api_collection_operation_name"="emprunt"
     *     }
     *     )
     */
    public function pretLivre(Request $request)
    {

        $info = json_decode($request->getContent(), true);
        $adherant = $this->personneRepo->findOneBy(["id" => $info["adherent"]]);
        //dd($adherant);
        $emprunt = new Emprunte();

        if (isset($info["adherant"]) && $info["adherant"] !== ""){
            if (!$adherant){
                return new JsonResponse("Cet adherant n'existe pas", Response::HTTP_BAD_REQUEST, []);
            }
        }
    if ($adherant->getProfil()->getLibelle() !== "ADHERENT"){
            return new JsonResponse("Ceci n'est pas un adherant", Response::HTTP_BAD_REQUEST, []);
        }else{
            $emprunt->setAdherent($adherant);
        }
        if (isset($info["livre"]) && $info["livre"] !== ""){
            $lvire = $this->livreRepo->findOneBy(["id" => $info["livre"]]);
        if ($lvire->getNbrLivre() <= 0){
                return new JsonResponse("Ce livre n'existe pas", Response::HTTP_BAD_REQUEST, []);
            }else{
                $lvire->setNbrLivre($lvire->getNbrLivre()-1);
                $this->manager->persist($lvire);
            }

        }
        $emprunt->setDatePret(new \DateTime($info['datePret']));
        //$dateRetourPrevu =date('Y-m-d H:m:n',strtotime('15 days',$emprunt->getDatePret()->getTimestamp()));
       // $dateRetourPrevu = \DateTime::createFromFormat('Y-m-d H:m:n',$dateRetourPrevu);



        $emprunt->setDateRetour(new \DateTime($info['dateRetour']));
        $emprunt->setStatus("pret");
        $emprunt->setLivre($lvire);

        $this->manager->persist($emprunt);
        $this->manager->flush();

        return $this->json("emprunte ajoute", Response::HTTP_CREATED);

    }

    /**
     * @Route(
     *     name="RetourEmprunt",
     *     methods={"PUT"},
     *      path="/api/emprunt/{id}",
     *      defaults={
     *          "__controller"="App\Controller\EmprunteController::RetourEmprunt",
     *          "__api_resource_class"=Emprunte::class,
     *          "__api_collection_operation_name"="RetourEmprunt"
     *     }
     *     )
     */
    public function RetourEmprunt(Request $request, $id){
        $emprunt = $this->empruntRepo->findOneBy(["id" => $id]);
//        if ($emprunt->getLivre()->getDispo() == 'disponible'){
//            return new JsonResponse("Livre déja disponible", Response::HTTP_BAD_REQUEST, []);
//        }
        $emprunt->getLivre()->setNbrLivre($emprunt->getLivre()->getNbrLivre() +1);
        $emprunt->setStatus("rendu");
        $this->manager->persist($emprunt);
        $this->manager->flush();
        return $this->json("Livre rendu", Response::HTTP_CREATED);
    }


    /**
     * @Route(
     *     name="rappelEmprunt",
     *     methods={"PUT"},
     *      path="/api/rappel/{id}",
     *      defaults={
     *          "__controller"="App\Controller\EmprunteController::rappelEmprunt",
     *          "__api_resource_class"=Emprunte::class,
     *          "__api_collection_operation_name"="rappelEmprunt"
     *     }
     *     )
     */
    public function rappelEmprunt(Request $request, $id){
        $rappel = $this->empruntRepo->findOneBy(['id' => $id]);
        $date= date_diff($rappel->getDateRetour(),new \DateTime('now'));
        dd($date);
        return new JsonResponse("bbb");
    }

}
