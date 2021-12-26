<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;


/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(name="`user`")
 * @ORM\InheritanceType("SINGLE_TABLE")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"admin"="Admin", "adherent"="Adherent", "user"="User"})
 * @ApiFilter(SearchFilter::class, properties={"status": "partial", "profil": "exact"})
 * @ApiResource(
 *     collectionOperations={
 *        "get_personne"={
 *          "method"="GET",
 *          "path"="/user",
 *          "normalization_context"={"groups":"get_personne"},
 *          "access_control"="is_granted('ROLE_ADMIN')",
 *          "access_control_message"="Vous n'avez pas les droits"
 *     },
 *      "AddUser"={
 *          "method"="Post",
 *          "path"="/user",
 *          "denormalization_context"={"groups":"AddUser"},
 *          "access_control"="is_granted('ROLE_ADMIN')",
 *          "access_control_message"="Vous n'avez pas les droits"
 *     }
 *     },
 *     itemOperations={
 *     "deletePersonne"={
 *            "method"="Delete",
 *           "path":"/user/{id}",
 *          "access_control"="is_granted('ROLE_ADMIN')",
 *          "access_control_message"="Vous n'avez pas les droits"
 *     },
 *     "get_personne_id"={
 *            "method"="GET",
 *           "path":"user/{id}",
 *           "normalization_context"={"groups":"get_personne_id"},
 *          "access_control"="is_granted('ROLE_ADMIN')",
 *          "access_control_message"="Vous n'avez pas les droits"
 * },
 *
 *     "updateUser"={
 *            "method"="PUT",
 *           "path":"/user/{id}",
 *           "normalization_context"={"groups":"updateUser"},
 *          "access_control"="is_granted('ROLE_Bibliothecaire')",
 *          "access_control_message"="Vous n'avez pas les droits"
 *     },
 *     }
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"get_personne","get_personne_id"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups ({"get_personne","get_personne_id"})
     */
    private $email;


    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"get_personne","get_personne_id","get_emprunt"})
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"get_personne","get_personne_id","get_emprunt"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups ({"get_personne","get_personne_id"})
     */
    private $adresse;

    /**
     * @ORM\Column(type="boolean", nullable="true")
     * @Groups ({"get_personne","get_personne_id"})
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="profil")
     * @Groups ({"get_personne","get_personne_id"})
     */
    private $profil;

    /**
     * @ORM\OneToMany(targetEntity=Emprunte::class, mappedBy="adherent")
     */
    private $empruntes;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="adherent")
     */
    private $reservations;

    public function __construct()
    {
        $this->empruntes = new ArrayCollection();
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_'. $this->getProfil()->getLibelle();

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

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
            $emprunte->setAdherent($this);
        }

        return $this;
    }

    public function removeEmprunte(Emprunte $emprunte): self
    {
        if ($this->empruntes->removeElement($emprunte)) {
            // set the owning side to null (unless already changed)
            if ($emprunte->getAdherent() === $this) {
                $emprunte->setAdherent(null);
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
            $reservation->setAdherent($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getAdherent() === $this) {
                $reservation->setAdherent(null);
            }
        }

        return $this;
    }
}
