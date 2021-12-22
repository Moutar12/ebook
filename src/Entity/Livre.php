<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\LivreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LivreRepository::class)
 * @ApiResource(
 *     attributes={
 *          "order"={
 *      "titre" : "ASC"
 *  }
 *     },
 *     collectionOperations={
 *      "get_livre"={
 *          "method"="Get",
 *          "path"="/livre",
 *          "normalization_context"={"groups":"get_livres"},
 *          "access_control"="is_granted('ROLE_ADMIN')",
 *          "access_control_message"="Vous n'avez pas les droits"
 *     },
 *     "livre"={
 *          "method"="Post",
 *          "path"="/livre",
 *          "denormalization_context"={"groups":"add_livres"},
 *          "access_control"="is_granted('ROLE_ADMIN')",
 *          "access_control_message"="Vous n'avez pas les droits"
 *     },
 *     },
 *     itemOperations={
 *       "livre_id"={
 *          "method"="Get",
 *          "path"="/livre/{id}",
 *          "normalization_context"={"groups":"livre_id"},
 *          "access_control"="is_granted('ROLE_ADMIN')",
 *          "access_control_message"="Vous n'avez pas les droits"
 *     },
 *     "editLivre"={
 *          "method"="Put",
 *          "path"="/livre/{id}",
 *          "denormalization_context"={"groups":"editLivre"},
 *          "access_control"="is_granted('ROLE_ADMIN')",
 *          "access_control_message"="Vous n'avez pas les droits"
 *     },
 *     "editLivre"={
 *          "method"="Delete",
 *          "path"="/livre/{id}",
 *          "denormalization_context"={"groups":"editLivre"},
 *          "access_control"="is_granted('ROLE_ADMIN')",
 *          "access_control_message"="Vous n'avez pas les droits"
 *     },
 *
 *     }
 * )
 * @UniqueEntity(
 *  fields={"titre"},
 *  errorPath="port",
 *  message = "Ce libelle existe déja"
 * )
 */
class Livre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get_livres","livre_id"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_livres","livre_id","get_emprunt"})
     * @Assert\Length(
     *   min=2,
     *   max=50,
     *   minMessage="Le Messsage doit contenir au moins {{ limit }} caractéres",
     *   maxMessage="Le Messsage doit contenir au plus {{ limit }} caractéres"
     * )
     */
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"get_livres","livre_id","get_emprunt"})
     */
    private $auteur;


    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups({"get_livres","livre_id","get_emprunt"})
     */
    private $annee;

    /**
     * @ORM\ManyToOne(targetEntity=Genre::class, inversedBy="genre")
     * @Groups({"get_livres","livre_id","get_emprunt"})
     */
    private $genre;


    /**
     * @ORM\OneToMany(targetEntity=Emprunte::class, mappedBy="livre")
     */
    private $empruntes;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="livre")
     */
    private $reservations;


    /**
     * @ORM\Column(type="integer")
     * @Groups({"get_livres","livre_id","get_emprunt"})
     */
    private $nbrLivre;



    public function __construct()
    {
        $this->empruntes = new ArrayCollection();
        $this->reservations = new ArrayCollection();


    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getAuteur(): ?string
    {
        return $this->auteur;
    }

    public function setAuteur(string $auteur): self
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getGenre(): ?Genre
    {
        return $this->genre;
    }

    public function setGenre(?Genre $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * @return Collection|Emprunte[]
     */
    public function getEmpruntes(): Collection
    {
        return $this->empruntes;
    }

    public function addEmprunte(Emprunte $emprunte): self
    {
        if (!$this->empruntes->contains($emprunte)) {
            $this->empruntes[] = $emprunte;
            $emprunte->setLivre($this);
        }

        return $this;
    }

    public function removeEmprunte(Emprunte $emprunte): self
    {
        if ($this->empruntes->removeElement($emprunte)) {
            // set the owning side to null (unless already changed)
            if ($emprunte->getLivre() === $this) {
                $emprunte->setLivre(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setLivre($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getLivre() === $this) {
                $reservation->setLivre(null);
            }
        }

        return $this;
    }

    public function getAnnee(): ?string
    {
        return $this->annee;
    }

    public function setAnnee(string $annee): self
    {
        $this->annee = $annee;

        return $this;
    }


    public function getNbrLivre(): ?int
    {
        return $this->nbrLivre;
    }

    public function setNbrLivre(int $nbrLivre): self
    {
        $this->nbrLivre = $nbrLivre;

        return $this;
    }

}
