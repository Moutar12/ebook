<?php

namespace App\Controller;

use App\Entity\Livre;
use App\Repository\GenreRepository;
use App\Repository\LivreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class LivreController extends AbstractController
{
    public function __construct(GenreRepository $genreRepository, SerializerInterface $serializer,
                                EntityManagerInterface $manager, LivreRepository $livreRepository)
    {
        $this->genreRepo = $genreRepository;
        $this->serialize = $serializer;
        $this->manger = $manager;
        $this->livreRepo = $livreRepository;
    }

    /**
     * @Route(
     *      name="adding",
     *     methods={"POST"},
     *      path="/api/livre",
     *      defaults={
     *          "__controller"="App\Controller\UserController::addUser",
     *          "__api_resource_class"=Livre::class,
     *          "__api_collection_operation_name"="post_admin"
     *     }
     *     )
     */
    public function addLivre(Request $request): Response
    {
        $info = json_decode($request->getContent(),true);
        $genre = $info['genre'];
        $genre = $this->genreRepo->findOneBy(['id' => $genre]);
        if (!$genre){
            return $this->json("No genre",Response::HTTP_CREATED);
        }
        $data = new Livre();
        $data->setTitre($info['titre'])
            ->setAuteur($info["auteur"])
            ->setDispo('disponible')
            ->setAnnee(date( $info["annee"]));
        $data->setGenre($this->genreRepo->findOneBy(['id' => $genre]));
        $this->manger->persist($data);
        $this->manger->flush();
        return $this->json("Livre ajoute",Response::HTTP_CREATED);
    }

    /**
     * @Route(
     *      name="editLivre",
     *     methods={"PUT"},
     *      path="/api/livre/{id}",
     *      defaults={
     *          "__controller"="App\Controller\UserController::editLivre",
     *          "__api_resource_class"=Livre::class,
     *          "__api_collection_operation_name"="editLivre"
     *     }
     *     )
     */
    public function editLivre(Request $request, $id){
        $info = json_decode($request->getContent(), true);
        $livre = $this->livreRepo->findOneBy(["id" => $id]);
        $livre->setTitre($info["titre"])
            ->setAnnee(date($info["annee"]))
            ->setAuteur($info["auteur"])
            ->setDispo('disponible')
            ->setGenre($this->genreRepo->findOneBy(["id" => $info["genreLivre"]]));
        $this->manger->persist($livre);
        $this->manger->flush();
        return $this->json("Livre modifié",Response::HTTP_CREATED);

    }
}
