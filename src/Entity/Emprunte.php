<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\EmprunteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=EmprunteRepository::class)
 * @ApiResource(
 *     collectionOperations={
 *      "get_emprunt"={
 *          "method"="Get",
 *          "path"="/emprunt",
 *          "normalization_context"={"groups":"get_emprunt"},
 *          "access_control"="is_granted('ROLE_ADMIN')",
 *          "access_control_message"="Vous n'avez pas les droits"
 *     },
 *     "emprunt"={
 *          "method"="Post",
 *          "path"="/emprunt",
 *          "denormalization_context"={"groups":"add_emprunt"},
 *          "access_control"="is_granted('ROLE_ADMIN')",
 *          "access_control_message"="Vous n'avez pas les droits"
 *     },
 *     },
 *
 *     itemOperations={
 *     "RetourEmprunt"={
 *          "route_name"="RetourEmprunt",
 *          "method"="put",
 *          "path"="/emprunt/{id}",
 *          "denormalization_context"={"groups":"RetourEmprunt"},
 *          "access_control"="is_granted('ROLE_ADMIN')",
 *          "access_control_message"="Vous n'avez pas les droits"
 *     },
 *     "One_emprunt"={
 *          "method"="Get",
 *          "path"="/emprunt/{id}",
 *          "normalization_context"={"groups":"One_emprunt"},
 *          "access_control"="is_granted('ROLE_ADMIN')",
 *          "access_control_message"="Vous n'avez pas les droits"
 *     },
 *     "rappelEmprunt"={
 *          "method"="Put",
 *          "path"="/rappel/{id}",
 *          "denrmalization_context"={"groups":"One_emprunt"},
 *          "access_control"="is_granted('ROLE_ADMIN')",
 *          "access_control_message"="Vous n'avez pas les droits"
 *     }
 *     }
 * )
 *
 */
class Emprunte
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get_emprunt"})
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     * @Groups({"get_emprunt"})
     */
    private $datePret;



    /**
     * @ORM\ManyToOne(targetEntity=Livre::class, inversedBy="empruntes")
     * @Groups({"get_emprunt"})
     */
    private $livre;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="empruntes")
     * @Groups({"get_emprunt"})
     */
    private $adherent;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"get_emprunt"})
     */
    private $DateRetour;

    /**
     * @ORM\Column(type="string", length=255)
     *  @Groups({"get_emprunt"})
     */
    private $status;



    public function __construct()
    {
        $this->pret = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDatePret(): ?\DateTimeInterface
    {
        return $this->datePret;
    }

    public function setDatePret(\DateTimeInterface $datePret): self
    {
        $this->datePret = $datePret;

        return $this;
    }


    public function getLivre(): ?Livre
    {
        return $this->livre;
    }

    public function setLivre(?Livre $livre): self
    {
        $this->livre = $livre;

        return $this;
    }

    public function getAdherent(): ?User
    {
        return $this->adherent;
    }

    public function setAdherent(?User $adherent): self
    {
        $this->adherent = $adherent;

        return $this;
    }

    public function getDateRetour(): ?\DateTimeInterface
    {
        return $this->DateRetour;
    }

    public function setDateRetour(?\DateTimeInterface $DateRetour): self
    {
        $this->DateRetour = $DateRetour;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }



}
