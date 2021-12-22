<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\GenreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=GenreRepository::class)
 * @ApiResource(
 *     collectionOperations={
 *          "get_GenreLivre"={
 *          "method"="Get",
 *          "path"="/genres",
 *          "normalization_context"={"groups":"get_genrelivres"},
 *          "access_control"="is_granted('ROLE_ADMIN')",
 *          "access_control_message"="Vous n'avez pas les droits"
 *     },
 *     "add_GenreLivre"={
 *          "method"="Post",
 *          "path"="/genres",
 *          "denormalization_context"={"groups":"add_genrelivres"},
 *          "access_control"="is_granted('ROLE_ADMIN')",
 *          "access_control_message"="Vous n'avez pas les droits"
 *     }
 *     }
 * )
 */
class Genre
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"get_livres","livre_id","get_genrelivres","add_genrelivres"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"get_livres","livre_id","get_genrelivres","add_genrelivres"})
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Livre::class, mappedBy="genre")
     */
    private $genre;

    public function __construct()
    {
        $this->genre = new ArrayCollection();
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
     * @return Collection|Livre[]
     */
    public function getGenre(): Collection
    {
        return $this->genre;
    }

    public function addGenre(Livre $genre): self
    {
        if (!$this->genre->contains($genre)) {
            $this->genre[] = $genre;
            $genre->setGenre($this);
        }

        return $this;
    }

    public function removeGenre(Livre $genre): self
    {
        if ($this->genre->removeElement($genre)) {
            // set the owning side to null (unless already changed)
            if ($genre->getGenre() === $this) {
                $genre->setGenre(null);
            }
        }

        return $this;
    }
}
