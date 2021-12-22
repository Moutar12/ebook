<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\ProfilRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=ProfilRepository::class)
 *  @ApiResource(
 *     collectionOperations={
 *       "get_profil"={
 *          "method"="GET",
 *          "path":"/profil",
 *          "normalization_context"={"groups":"get_profil"},
 *          "access_control"="is_granted('ROLE_ADMIN')",
 *          "access_control_message"="Vous n'avez pas les droits"
 *     }
 *     }
 * )
 */
class Profil
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"get_profil","get_personne",})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"get_personne","get_profil","get_personne_id"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=User::class, mappedBy="profil")
     */
    private $profil;

    public function __construct()
    {
        $this->profil = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getProfil(): Collection
    {
        return $this->profil;
    }

    public function addProfil(User $profil): self
    {
        if (!$this->profil->contains($profil)) {
            $this->profil[] = $profil;
            $profil->setProfil($this);
        }

        return $this;
    }

    public function removeProfil(User $profil): self
    {
        if ($this->profil->removeElement($profil)) {
            // set the owning side to null (unless already changed)
            if ($profil->getProfil() === $this) {
                $profil->setProfil(null);
            }
        }

        return $this;
    }
}
